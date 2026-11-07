<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import type { ResumeData } from '@/types/resume';
import { useTranslations } from '@/composables/useTranslations';
import LinkedinIcon from '@/components/icons/LinkedinIcon.vue';
import { ChevronDown, Download, Mail } from 'lucide-vue-next';

const props = defineProps<{
    data: ResumeData;
    isDark?: boolean;
}>();

const { t } = useTranslations();

// Canvas & Particle animation state
const canvasRef = ref<HTMLCanvasElement | null>(null);
const heroSectionRef = ref<HTMLElement | null>(null);

interface Particle {
    x: number;
    y: number;
    vx: number;
    vy: number;
    radius: number;
    baseAlpha: number;
    color: string;
}

let animationFrameId: number | null = null;
let isVisible = true;
let width = 0;
let height = 0;
let lastWidth = 0; // Tracks width to prevent mobile URL bar resize glitch

// Mouse tracking with smooth lerp
const mouse = {
    x: -1000,
    y: -1000,
    targetX: -1000,
    targetY: -1000,
    active: false,
    radius: 160,
};

let particles: Particle[] = [];

const initParticles = () => {
    if (!width || !height) return;
    const isMobile = width < 640;
    const count = isMobile ? 28 : 55;

    const colors = props.isDark
        ? [
              'rgba(52, 211, 153, ', // emerald-400
              'rgba(56, 189, 248, ', // sky-400
              'rgba(167, 139, 250, ', // violet-400
              'rgba(244, 244, 245, ', // neutral-100
          ]
        : [
              'rgba(16, 185, 129, ', // emerald-500
              'rgba(14, 165, 233, ', // sky-500
              'rgba(124, 58, 237, ', // violet-600
              'rgba(15, 23, 42, ', // slate-900
          ];

    particles = [];
    for (let i = 0; i < count; i++) {
        particles.push({
            x: Math.random() * width,
            y: Math.random() * height,
            vx: (Math.random() - 0.5) * 0.45,
            vy: (Math.random() - 0.5) * 0.45,
            radius: Math.random() * 2 + 1.2,
            baseAlpha: Math.random() * 0.45 + 0.25,
            color: colors[Math.floor(Math.random() * colors.length)],
        });
    }
};

const handleResize = () => {
    if (!canvasRef.value || !heroSectionRef.value) return;
    const rect = heroSectionRef.value.getBoundingClientRect();

    // OPTIMIZATION: On mobile, scrolling hides the address bar, triggering a resize event.
    // If the width hasn't changed, and the height change is small, IGNORE the resize
    // to prevent the canvas from clearing and causing lag.
    if (lastWidth === rect.width && Math.abs(height - rect.height) < 150) {
        return;
    }

    lastWidth = rect.width;
    width = rect.width;
    height = rect.height;

    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    canvasRef.value.width = width * dpr;
    canvasRef.value.height = height * dpr;
    canvasRef.value.style.width = `${width}px`;
    canvasRef.value.style.height = `${height}px`;

    const ctx = canvasRef.value.getContext('2d', { alpha: true });
    if (ctx) {
        ctx.scale(dpr, dpr);
    }
    initParticles();
};

const handleMouseMove = (e: MouseEvent) => {
    if (!heroSectionRef.value) return;
    const rect = heroSectionRef.value.getBoundingClientRect();
    mouse.targetX = e.clientX - rect.left;
    mouse.targetY = e.clientY - rect.top;
    mouse.active = true;
};

const handleMouseLeave = () => {
    mouse.active = false;
    mouse.targetX = -1000;
    mouse.targetY = -1000;
};

const render = () => {
    if (!isVisible || !canvasRef.value) {
        animationFrameId = requestAnimationFrame(render);
        return;
    }

    const ctx = canvasRef.value.getContext('2d');
    if (!ctx) return;

    // Smooth mouse coordinates
    mouse.x += (mouse.targetX - mouse.x) * 0.12;
    mouse.y += (mouse.targetY - mouse.y) * 0.12;

    ctx.clearRect(0, 0, width, height);

    // Draw ambient cursor glow spotlight if mouse is active
    if (mouse.active) {
        const glowRadius = width < 640 ? 180 : 260;
        const glowGradient = ctx.createRadialGradient(
            mouse.x,
            mouse.y,
            0,
            mouse.x,
            mouse.y,
            glowRadius,
        );
        if (props.isDark) {
            glowGradient.addColorStop(0, 'rgba(52, 211, 153, 0.18)');
            glowGradient.addColorStop(0.45, 'rgba(56, 189, 248, 0.08)');
            glowGradient.addColorStop(1, 'rgba(0, 0, 0, 0)');
        } else {
            glowGradient.addColorStop(0, 'rgba(16, 185, 129, 0.14)');
            glowGradient.addColorStop(0.45, 'rgba(14, 165, 233, 0.07)');
            glowGradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
        }
        ctx.fillStyle = glowGradient;
        ctx.beginPath();
        ctx.arc(mouse.x, mouse.y, glowRadius, 0, Math.PI * 2);
        ctx.fill();
    }

    // Update & draw particles and connector lines
    const lineDistance = width < 640 ? 85 : 125;

    for (let i = 0; i < particles.length; i++) {
        const p = particles[i];

        p.x += p.vx;
        p.y += p.vy;

        if (p.x < 0) p.x = width;
        if (p.x > width) p.x = 0;
        if (p.y < 0) p.y = height;
        if (p.y > height) p.y = 0;

        if (mouse.active) {
            const dx = mouse.x - p.x;
            const dy = mouse.y - p.y;
            const dist = Math.sqrt(dx * dx + dy * dy);

            if (dist < mouse.radius && dist > 0) {
                const force = (mouse.radius - dist) / mouse.radius;
                p.x -= (dx / dist) * force * 1.4;
                p.y -= (dy / dist) * force * 1.4;
            }
        }

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
        ctx.fillStyle = `${p.color}${p.baseAlpha})`;
        ctx.fill();

        for (let j = i + 1; j < particles.length; j++) {
            const p2 = particles[j];
            const dx = p.x - p2.x;
            const dy = p.y - p2.y;
            const dist = Math.sqrt(dx * dx + dy * dy);

            if (dist < lineDistance) {
                const alpha =
                    (1 - dist / lineDistance) * (props.isDark ? 0.22 : 0.16);
                ctx.beginPath();
                ctx.moveTo(p.x, p.y);
                ctx.lineTo(p2.x, p2.y);
                ctx.strokeStyle = props.isDark
                    ? `rgba(148, 163, 184, ${alpha})`
                    : `rgba(100, 116, 139, ${alpha})`;
                ctx.lineWidth = 0.8;
                ctx.stroke();
            }
        }
    }

    animationFrameId = requestAnimationFrame(render);
};

let observer: IntersectionObserver | null = null;

onMounted(() => {
    handleResize();
    window.addEventListener('resize', handleResize, { passive: true });

    if (heroSectionRef.value) {
        // Desktop mouse tracking only. Removed touch events for mobile scrolling performance.
        heroSectionRef.value.addEventListener('mousemove', handleMouseMove, {
            passive: true,
        });
        heroSectionRef.value.addEventListener('mouseleave', handleMouseLeave, {
            passive: true,
        });

        observer = new IntersectionObserver(
            ([entry]) => {
                isVisible = entry.isIntersecting;
            },
            { threshold: 0.05 },
        );
        observer.observe(heroSectionRef.value);
    }

    animationFrameId = requestAnimationFrame(render);
});

onUnmounted(() => {
    if (animationFrameId) cancelAnimationFrame(animationFrameId);
    window.removeEventListener('resize', handleResize);
    if (observer && heroSectionRef.value)
        observer.unobserve(heroSectionRef.value);
    if (heroSectionRef.value) {
        heroSectionRef.value.removeEventListener('mousemove', handleMouseMove);
        heroSectionRef.value.removeEventListener(
            'mouseleave',
            handleMouseLeave,
        );
    }
});

watch(
    () => props.isDark,
    () => {
        initParticles();
    },
);

const scrollTo = (selector: string) => {
    const el = document.querySelector(selector);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth' });
    }
};

const firstActiveSectionSelector = computed(() => {
    const s = props.data.settings;
    if (s?.showAbout ?? true) return '#about';
    if (s?.showExperience ?? true) return '#experience';
    if (s?.showSkills ?? true) return '#skills';
    if (s?.showEducation ?? true) return '#education';
    if (s?.showProjects ?? true) return '#projects';
    if (s?.showContact ?? true) return '#contact';
    return null;
});

const handleMessageMe = () => {
    if (props.data.settings?.showContact ?? true) {
        scrollTo('#contact');
    } else if (props.data.contact?.email) {
        window.location.href = `mailto:${props.data.contact.email}`;
    }
};
</script>

<template>
    <section
        id="hero"
        ref="heroSectionRef"
        class="relative flex min-h-[calc(100svh-65px)] cursor-default flex-col items-center justify-center overflow-hidden border-b-2 border-neutral-200/80 px-4 text-center select-none sm:px-6 lg:px-8 dark:border-neutral-800/80"
    >
        <canvas
            ref="canvasRef"
            class="pointer-events-none absolute inset-0 z-0"
        />

        <div
            class="pointer-events-none absolute inset-0 z-0 bg-radial from-emerald-100/30 via-transparent to-transparent dark:from-emerald-950/20 dark:via-transparent dark:to-transparent"
        />

        <div class="relative z-10 mx-auto max-w-3xl py-12 sm:py-16">
            <h1
                class="text-5xl leading-[1.05] font-black tracking-tight text-neutral-950 sm:text-7xl md:text-8xl dark:text-white"
            >
                {{ data.name }}
            </h1>

            <p
                class="mt-4 text-xl font-semibold tracking-tight text-neutral-800 sm:text-2xl md:text-3xl dark:text-neutral-200"
            >
                <span
                    class="bg-linear-to-r from-emerald-600 via-teal-600 to-sky-600 bg-clip-text text-transparent dark:from-emerald-400 dark:via-teal-300 dark:to-sky-400"
                >
                    {{ data.title }}
                </span>
            </p>

            <p
                v-if="data.intro"
                class="mx-auto mt-4 max-w-xl text-sm leading-relaxed font-normal text-neutral-600 sm:text-base md:text-lg dark:text-neutral-400"
            >
                {{ data.intro }}
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a
                    v-if="data.cvPdfUrl"
                    :href="data.cvPdfUrl"
                    target="_blank"
                    download
                    class="inline-flex transform cursor-pointer items-center gap-2 rounded-full bg-neutral-950 px-6 py-3 text-xs font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:bg-neutral-800 hover:shadow-md sm:text-sm dark:bg-white dark:text-neutral-950 dark:hover:bg-neutral-100"
                >
                    <Download class="h-4 w-4" />
                    <span>{{ t('download_cv') }}</span>
                </a>

                <button
                    type="button"
                    @click="handleMessageMe"
                    class="inline-flex transform cursor-pointer items-center gap-2 rounded-full border border-neutral-300 bg-white/90 px-6 py-3 text-xs font-semibold text-neutral-800 shadow-xs backdrop-blur-md transition-all hover:-translate-y-0.5 hover:border-emerald-500 hover:text-emerald-600 sm:text-sm dark:border-neutral-700 dark:bg-neutral-900/90 dark:text-neutral-200 dark:hover:text-emerald-400"
                >
                    <Mail class="h-4 w-4" />
                    <span>{{ t('message_me') }}</span>
                </button>

                <a
                    :href="data.contact.linkedin"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 rounded-full px-5 py-3 text-xs font-semibold text-neutral-600 transition-colors hover:text-sky-600 sm:text-sm dark:text-neutral-400 dark:hover:text-sky-400"
                >
                    <LinkedinIcon class="h-4 w-4 text-sky-600" />
                    <span>{{ t('linkedin') }}</span>
                </a>
            </div>

            <div
                v-if="data.quickStats?.length"
                class="mt-8 grid max-w-4xl auto-cols-fr gap-3 rounded-2xl p-4 sm:grid-flow-col sm:gap-4 sm:p-5"
            >
                <div
                    v-for="(stat, index) in data.quickStats"
                    :key="index"
                    class="relative flex flex-col items-center justify-center rounded-xl p-2.5 max-md:last:after:hidden max-md:even:after:hidden md:last:after:hidden"
                >
                    <div
                        class="bg-linear-to-r from-violet-600 to-indigo-600 bg-clip-text text-2xl font-extrabold tracking-tight text-transparent sm:text-3xl dark:from-violet-400 dark:to-indigo-300"
                    >
                        {{ stat.value }}
                    </div>
                    <div
                        class="mt-0.5 text-xs font-semibold text-neutral-800 dark:text-neutral-200"
                    >
                        {{ stat.label }}
                    </div>
                    <div
                        class="mt-0.5 hidden text-center text-[11px] text-neutral-500 sm:block dark:text-neutral-400"
                    >
                        {{ stat.description }}
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="firstActiveSectionSelector"
            class="pointer-events-auto absolute inset-x-0 bottom-4 z-10 flex justify-center"
        >
            <button
                type="button"
                @click="scrollTo(firstActiveSectionSelector)"
                class="group flex cursor-pointer flex-col items-center gap-1 text-neutral-400 transition-colors hover:text-neutral-800 dark:text-neutral-500 dark:hover:text-neutral-200"
                aria-label="Scroll to next section"
            >
                <span class="font-mono text-[10px] tracking-wider uppercase">{{
                    t('scroll')
                }}</span>
                <ChevronDown
                    class="h-3.5 w-3.5 animate-bounce transition-colors group-hover:text-emerald-500"
                />
            </button>
        </div>
    </section>
</template>
