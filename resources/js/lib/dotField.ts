/**
 * Interactive dot grid drawn on a canvas.
 *
 * Dots are pushed away from the cursor (or finger) and spring back, a tap or click sends a
 * ripple through the grid, and capable devices get a slow "breathing" wave while idle.
 *
 * Performance notes:
 * - dot state lives in typed arrays and dots are drawn in a handful of batched paths
 *   (one per size/colour bucket), not one fill per dot;
 * - the loop sleeps once everything has settled, and pauses while the canvas is off screen
 *   or the tab is hidden;
 * - weak devices (few cores, little memory, data saver) get fewer dots, a 1x canvas and no
 *   idle animation, and any device that can't hold a frame rate drops to that tier at runtime.
 */

type Rgb = [number, number, number];

interface Palette {
    base: Rgb;
    baseAlpha: number;
    accent: Rgb;
    accentAlpha: number;
    halo: string;
}

interface Ripple {
    x: number;
    y: number;
    radius: number;
    strength: number;
}

interface Quality {
    spacing: number;
    maxDots: number;
    maxPixelRatio: number;
    ambient: boolean;
}

const TAU = Math.PI * 2;
const BUCKETS = 8;
const FRAME_MS = 1000 / 60;
const AMBIENT_FRAME_MS = 1000 / 30;

const SPRING = 0.06;
const DAMPING = 0.86;
const PUSH_FORCE = 2.4;
const RIPPLE_SPEED = 9;
const RIPPLE_WIDTH = 46;
const RIPPLE_FORCE = 1.6;
const MAX_RIPPLES = 3;

const LIGHT_PALETTE: Palette = {
    base: [150, 138, 190],
    baseAlpha: 0.4,
    accent: [132, 80, 236],
    accentAlpha: 0.95,
    halo: '132, 80, 236',
};

const DARK_PALETTE: Palette = {
    base: [128, 116, 170],
    baseAlpha: 0.32,
    accent: [205, 178, 255],
    accentAlpha: 0.95,
    halo: '180, 150, 255',
};

function isWeakDevice(): boolean {
    const nav = navigator as Navigator & {
        deviceMemory?: number;
        connection?: { saveData?: boolean };
    };

    return (
        (nav.hardwareConcurrency ?? 8) <= 4 ||
        (nav.deviceMemory ?? 8) <= 4 ||
        nav.connection?.saveData === true
    );
}

function pickQuality(isWeak: boolean, isCoarse: boolean): Quality {
    if (isWeak) {
        return {
            spacing: isCoarse ? 34 : 32,
            maxDots: 900,
            maxPixelRatio: 1,
            ambient: false,
        };
    }

    return {
        spacing: isCoarse ? 30 : 26,
        maxDots: isCoarse ? 1400 : 2600,
        maxPixelRatio: 1.75,
        ambient: true,
    };
}

export class DotField {
    private readonly ctx: CanvasRenderingContext2D;
    private readonly isReducedMotion: boolean;
    private readonly isCoarse: boolean;
    private quality: Quality;
    private hasDegraded = false;

    private width = 0;
    private height = 0;
    private count = 0;
    private originX = new Float32Array(0);
    private originY = new Float32Array(0);
    private offsetX = new Float32Array(0);
    private offsetY = new Float32Array(0);
    private velocityX = new Float32Array(0);
    private velocityY = new Float32Array(0);
    private bucket = new Uint8Array(0);

    private palette: Palette = LIGHT_PALETTE;
    private bucketStyles: string[] = [];
    private bucketRadii: number[] = [];

    private pointerTargetX = 0;
    private pointerTargetY = 0;
    private pointerX = 0;
    private pointerY = 0;
    private pointerStrength = 0;
    private isPointerActive = false;
    private pointerRadius = 150;
    private ripples: Ripple[] = [];

    private frameId: number | null = null;
    private lastFrameAt = 0;
    private lastDrawAt = 0;
    private isVisible = true;
    private slowFrames = 0;
    private sampledFrames = 0;

    private readonly cleanups: Array<() => void> = [];

    constructor(private readonly canvas: HTMLCanvasElement) {
        const ctx = canvas.getContext('2d', { alpha: true });
        if (!ctx) {
            throw new Error('2D canvas context is not available.');
        }
        this.ctx = ctx;

        this.isReducedMotion = window.matchMedia(
            '(prefers-reduced-motion: reduce)',
        ).matches;
        this.isCoarse = window.matchMedia('(pointer: coarse)').matches;
        this.quality = pickQuality(isWeakDevice(), this.isCoarse);

        this.applyTheme();
        this.layout();
        this.observe();

        if (!this.isReducedMotion) {
            this.listen();
        }

        this.wake();
    }

    destroy(): void {
        this.sleep();
        this.cleanups.forEach((cleanup) => cleanup());
        this.cleanups.length = 0;
    }

    private observe(): void {
        let resizeFrame: number | null = null;
        const resizeObserver = new ResizeObserver(() => {
            if (resizeFrame !== null) {
                cancelAnimationFrame(resizeFrame);
            }
            resizeFrame = requestAnimationFrame(() => {
                resizeFrame = null;
                this.layout();
                this.wake();
            });
        });
        resizeObserver.observe(this.canvas);

        const intersectionObserver = new IntersectionObserver(([entry]) => {
            this.isVisible = entry.isIntersecting;
            if (this.isVisible) {
                this.wake();
            } else {
                this.sleep();
            }
        });
        intersectionObserver.observe(this.canvas);

        const themeObserver = new MutationObserver(() => {
            this.applyTheme();
            this.wake();
        });
        themeObserver.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class'],
        });

        const onVisibilityChange = () => {
            if (document.hidden) {
                this.sleep();
            } else {
                this.wake();
            }
        };
        document.addEventListener('visibilitychange', onVisibilityChange);

        this.cleanups.push(() => {
            if (resizeFrame !== null) {
                cancelAnimationFrame(resizeFrame);
            }
            resizeObserver.disconnect();
            intersectionObserver.disconnect();
            themeObserver.disconnect();
            document.removeEventListener(
                'visibilitychange',
                onVisibilityChange,
            );
        });
    }

    private listen(): void {
        const passive = { passive: true } as const;

        const onPointerMove = (event: PointerEvent) => {
            if (event.pointerType === 'touch') {
                return;
            }
            this.setPointer(event.clientX, event.clientY);
        };

        const onPointerDown = (event: PointerEvent) => {
            this.addRipple(event.clientX, event.clientY);
        };

        const onPointerOut = (event: PointerEvent) => {
            if (!event.relatedTarget && event.pointerType !== 'touch') {
                this.isPointerActive = false;
            }
        };

        /*
         * Touch uses touch events rather than pointer events: pointer events are cancelled as
         * soon as the page starts scrolling, touchmove keeps firing, so dots follow the finger
         * while the visitor scrolls past the hero.
         */
        const onTouch = (event: TouchEvent) => {
            const touch = event.touches[0];
            if (touch) {
                this.setPointer(touch.clientX, touch.clientY);
            }
        };

        const onTouchEnd = () => {
            this.isPointerActive = false;
        };

        window.addEventListener('pointermove', onPointerMove, passive);
        window.addEventListener('pointerdown', onPointerDown, passive);
        document.addEventListener('pointerout', onPointerOut, passive);
        window.addEventListener('touchstart', onTouch, passive);
        window.addEventListener('touchmove', onTouch, passive);
        window.addEventListener('touchend', onTouchEnd, passive);
        window.addEventListener('touchcancel', onTouchEnd, passive);

        this.cleanups.push(() => {
            window.removeEventListener('pointermove', onPointerMove);
            window.removeEventListener('pointerdown', onPointerDown);
            document.removeEventListener('pointerout', onPointerOut);
            window.removeEventListener('touchstart', onTouch);
            window.removeEventListener('touchmove', onTouch);
            window.removeEventListener('touchend', onTouchEnd);
            window.removeEventListener('touchcancel', onTouchEnd);
        });
    }

    private setPointer(clientX: number, clientY: number): void {
        if (!this.isVisible) {
            return;
        }

        const rect = this.canvas.getBoundingClientRect();
        const x = clientX - rect.left;
        const y = clientY - rect.top;
        const isInside =
            x >= 0 && y >= 0 && x <= rect.width && y <= rect.height;

        if (
            isInside &&
            (!this.isPointerActive || this.pointerStrength < 0.01)
        ) {
            this.pointerX = x;
            this.pointerY = y;
        }

        this.pointerTargetX = x;
        this.pointerTargetY = y;
        this.isPointerActive = isInside;

        if (isInside) {
            this.wake();
        }
    }

    private addRipple(clientX: number, clientY: number): void {
        if (!this.isVisible) {
            return;
        }

        const rect = this.canvas.getBoundingClientRect();
        const x = clientX - rect.left;
        const y = clientY - rect.top;
        if (x < 0 || y < 0 || x > rect.width || y > rect.height) {
            return;
        }

        if (this.ripples.length >= MAX_RIPPLES) {
            this.ripples.shift();
        }
        this.ripples.push({ x, y, radius: 0, strength: 1 });
        this.wake();
    }

    private applyTheme(): void {
        this.palette = document.documentElement.classList.contains('dark')
            ? DARK_PALETTE
            : LIGHT_PALETTE;

        const { base, baseAlpha, accent, accentAlpha } = this.palette;
        this.bucketStyles = Array.from({ length: BUCKETS }, (_, index) => {
            const mix = index / (BUCKETS - 1);
            const channel = (i: number) =>
                Math.round(base[i] + (accent[i] - base[i]) * mix);
            const alpha = baseAlpha + (accentAlpha - baseAlpha) * mix;

            return `rgba(${channel(0)}, ${channel(1)}, ${channel(2)}, ${alpha.toFixed(3)})`;
        });

        if (!this.frameId) {
            this.draw(performance.now());
        }
    }

    private layout(): void {
        const width = this.canvas.clientWidth;
        const height = this.canvas.clientHeight;
        if (!width || !height) {
            return;
        }

        const pixelRatio = Math.min(
            window.devicePixelRatio || 1,
            this.quality.maxPixelRatio,
        );
        this.width = width;
        this.height = height;
        this.canvas.width = Math.round(width * pixelRatio);
        this.canvas.height = Math.round(height * pixelRatio);
        this.ctx.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);

        const spacing = Math.max(
            this.quality.spacing,
            Math.sqrt((width * height) / this.quality.maxDots),
        );
        const columns = Math.floor(width / spacing) + 1;
        const rows = Math.floor(height / spacing) + 1;
        const marginX = (width - (columns - 1) * spacing) / 2;
        const marginY = (height - (rows - 1) * spacing) / 2;

        this.count = columns * rows;
        this.originX = new Float32Array(this.count);
        this.originY = new Float32Array(this.count);
        this.offsetX = new Float32Array(this.count);
        this.offsetY = new Float32Array(this.count);
        this.velocityX = new Float32Array(this.count);
        this.velocityY = new Float32Array(this.count);
        this.bucket = new Uint8Array(this.count);

        let index = 0;
        for (let row = 0; row < rows; row++) {
            for (let column = 0; column < columns; column++) {
                this.originX[index] = marginX + column * spacing;
                this.originY[index] = marginY + row * spacing;
                index++;
            }
        }

        const baseRadius = spacing < 28 ? 1.1 : 1.25;
        const maxRadius = baseRadius + (this.isCoarse ? 2.2 : 2.6);
        this.bucketRadii = Array.from(
            { length: BUCKETS },
            (_, i) =>
                baseRadius + ((maxRadius - baseRadius) * i) / (BUCKETS - 1),
        );
        this.pointerRadius = Math.min(
            this.isCoarse ? 130 : 170,
            Math.max(100, width * 0.18),
        );

        this.draw(performance.now());
    }

    private wake(): void {
        if (
            this.frameId !== null ||
            this.isReducedMotion ||
            !this.isVisible ||
            document.hidden
        ) {
            return;
        }
        this.lastFrameAt = performance.now();
        this.frameId = requestAnimationFrame(this.tick);
    }

    private sleep(): void {
        if (this.frameId !== null) {
            cancelAnimationFrame(this.frameId);
            this.frameId = null;
        }
    }

    private readonly tick = (now: number): void => {
        const elapsed = now - this.lastFrameAt;
        this.lastFrameAt = now;
        this.monitorFrameRate(elapsed);

        const dt = Math.min(elapsed / FRAME_MS, 3);
        const energy = this.step(dt);
        const isInteracting =
            this.isPointerActive ||
            this.pointerStrength > 0.01 ||
            this.ripples.length > 0 ||
            energy > 0.02;

        if (!isInteracting && !this.quality.ambient) {
            this.draw(now);
            this.frameId = null;
            return;
        }

        // Idle "breathing" only needs 30fps; interaction gets the full frame rate.
        if (isInteracting || now - this.lastDrawAt >= AMBIENT_FRAME_MS) {
            this.draw(now);
        }

        this.frameId = requestAnimationFrame(this.tick);
    };

    /**
     * Drops to the weak-device tier once if the device can't keep up (frames averaging
     * slower than ~35fps while the field is busy).
     */
    private monitorFrameRate(elapsed: number): void {
        if (this.hasDegraded || elapsed > 250) {
            return;
        }

        this.sampledFrames++;
        if (elapsed > 28) {
            this.slowFrames++;
        }

        if (this.sampledFrames >= 90) {
            if (this.slowFrames / this.sampledFrames > 0.5) {
                this.hasDegraded = true;
                this.quality = pickQuality(true, this.isCoarse);
                this.layout();
            }
            this.sampledFrames = 0;
            this.slowFrames = 0;
        }
    }

    /**
     * Advances the simulation and returns the largest dot speed, used to decide when to sleep.
     */
    private step(dt: number): number {
        const ease = 1 - Math.pow(0.7, dt);
        this.pointerX += (this.pointerTargetX - this.pointerX) * ease;
        this.pointerY += (this.pointerTargetY - this.pointerY) * ease;
        const strengthTarget = this.isPointerActive ? 1 : 0;
        this.pointerStrength +=
            (strengthTarget - this.pointerStrength) * (1 - Math.pow(0.88, dt));

        for (const ripple of this.ripples) {
            ripple.radius += RIPPLE_SPEED * dt;
            ripple.strength *= Math.pow(0.965, dt);
        }
        const reach = Math.hypot(this.width, this.height);
        this.ripples = this.ripples.filter(
            (ripple) => ripple.strength > 0.03 && ripple.radius < reach,
        );

        const damping = Math.pow(DAMPING, dt);
        const radius = this.pointerRadius;
        const radiusSquared = radius * radius;
        const pointerX = this.pointerX;
        const pointerY = this.pointerY;
        const strength = this.pointerStrength;
        const isPointerInfluencing = strength > 0.005;
        let maxSpeed = 0;

        for (let i = 0; i < this.count; i++) {
            const originX = this.originX[i];
            const originY = this.originY[i];
            let offsetX = this.offsetX[i];
            let offsetY = this.offsetY[i];
            let velocityX = this.velocityX[i];
            let velocityY = this.velocityY[i];
            let glow = 0;

            if (isPointerInfluencing) {
                const deltaX = originX + offsetX - pointerX;
                const deltaY = originY + offsetY - pointerY;
                const distanceSquared = deltaX * deltaX + deltaY * deltaY;
                if (distanceSquared < radiusSquared) {
                    const distance = Math.sqrt(distanceSquared) || 1;
                    const falloff = 1 - distance / radius;
                    const push = falloff * falloff * PUSH_FORCE * strength * dt;
                    velocityX += (deltaX / distance) * push;
                    velocityY += (deltaY / distance) * push;
                    glow = falloff * strength;
                }
            }

            for (const ripple of this.ripples) {
                const deltaX = originX - ripple.x;
                const deltaY = originY - ripple.y;
                const distance =
                    Math.sqrt(deltaX * deltaX + deltaY * deltaY) || 1;
                const band = Math.abs(distance - ripple.radius);
                if (band < RIPPLE_WIDTH) {
                    const amount = (1 - band / RIPPLE_WIDTH) * ripple.strength;
                    velocityX +=
                        (deltaX / distance) * amount * RIPPLE_FORCE * dt;
                    velocityY +=
                        (deltaY / distance) * amount * RIPPLE_FORCE * dt;
                    glow = Math.max(glow, amount * 0.85);
                }
            }

            velocityX = (velocityX - offsetX * SPRING * dt) * damping;
            velocityY = (velocityY - offsetY * SPRING * dt) * damping;
            offsetX += velocityX * dt;
            offsetY += velocityY * dt;

            const speed = Math.abs(velocityX) + Math.abs(velocityY);
            if (speed > maxSpeed) {
                maxSpeed = speed;
            }

            glow += Math.min(
                Math.sqrt(offsetX * offsetX + offsetY * offsetY) / 30,
                0.5,
            );

            this.offsetX[i] = offsetX;
            this.offsetY[i] = offsetY;
            this.velocityX[i] = velocityX;
            this.velocityY[i] = velocityY;
            this.bucket[i] = Math.min(BUCKETS - 1, Math.floor(glow * BUCKETS));
        }

        return maxSpeed;
    }

    private draw(now: number): void {
        const ctx = this.ctx;
        this.lastDrawAt = now;
        ctx.clearRect(0, 0, this.width, this.height);

        if (this.pointerStrength > 0.01) {
            const halo = ctx.createRadialGradient(
                this.pointerX,
                this.pointerY,
                0,
                this.pointerX,
                this.pointerY,
                this.pointerRadius * 1.3,
            );
            halo.addColorStop(
                0,
                `rgba(${this.palette.halo}, ${(0.14 * this.pointerStrength).toFixed(3)})`,
            );
            halo.addColorStop(1, `rgba(${this.palette.halo}, 0)`);
            ctx.fillStyle = halo;
            ctx.fillRect(0, 0, this.width, this.height);
        }

        const isBreathing = this.quality.ambient && !this.isReducedMotion;
        const time = now * 0.0011;

        for (let level = 0; level < BUCKETS; level++) {
            const radius = this.bucketRadii[level];
            let hasDots = false;
            ctx.beginPath();

            for (let i = 0; i < this.count; i++) {
                const originX = this.originX[i];
                const originY = this.originY[i];
                let dotLevel = this.bucket[i];

                if (isBreathing && dotLevel < 2) {
                    const wave = Math.sin(
                        time + originX * 0.011 + originY * 0.007,
                    );
                    if (wave > 0.82) {
                        dotLevel = Math.max(dotLevel, wave > 0.95 ? 2 : 1);
                    }
                }

                if (dotLevel !== level) {
                    continue;
                }

                const x = originX + this.offsetX[i];
                const y = originY + this.offsetY[i];
                ctx.moveTo(x + radius, y);
                ctx.arc(x, y, radius, 0, TAU);
                hasDots = true;
            }

            if (hasDots) {
                ctx.fillStyle = this.bucketStyles[level];
                ctx.fill();
            }
        }
    }
}
