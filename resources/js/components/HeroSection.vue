<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue';
import type { ResumeData } from '@/types/resume';
import Icon from './icons/Icon.vue';
import {useTranslations} from "@/composables/useTranslations";
import LinkedinIcon from "@/components/icons/LinkedinIcon.vue";
import {ChevronDown, Download, Mail} from "lucide-vue-next";


const props = defineProps<{
    data: ResumeData;
    isDark?: boolean;
}>();

const { t } = useTranslations();

defineEmits<{
    (e: 'copy-email', email: string): void;
    (e: 'open-resume'): void;
}>();

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
    width = rect.width;
    height = rect.height;

    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    canvasRef.value.width = width * dpr;
    canvasRef.value.height = height * dpr;
    canvasRef.value.style.width = `${width}px`;
    canvasRef.value.style.height = `${height}px`;

    const ctx = canvasRef.value.getContext('2d');
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

const handleTouchMove = (e: TouchEvent) => {
    if (!heroSectionRef.value || e.touches.length === 0) return;
    const rect = heroSectionRef.value.getBoundingClientRect();
    mouse.targetX = e.touches[0].clientX - rect.left;
    mouse.targetY = e.touches[0].clientY - rect.top;
    mouse.active = true;
};

const handleTouchEnd = () => {
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

    // Smooth lerp mouse coordinates
    mouse.x += (mouse.targetX - mouse.x) * 0.12;
    mouse.y += (mouse.targetY - mouse.y) * 0.12;

    ctx.clearRect(0, 0, width, height);

    // Draw ambient cursor glow spotlight
    if (mouse.active || mouse.x > 0) {
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

        // Particle position update
        p.x += p.vx;
        p.y += p.vy;

        // Wrap around boundaries smoothly
        if (p.x < 0) p.x = width;
        if (p.x > width) p.x = 0;
        if (p.y < 0) p.y = height;
        if (p.y > height) p.y = 0;

        // Mouse proximity reaction (gentle magnetic push/pull)
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

        // Draw particle dot
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
        ctx.fillStyle = `${p.color}${p.baseAlpha})`;
        ctx.fill();

        // Connect nearby particles
        for (let j = i + 1; j < particles.length; j++) {
            const p2 = particles[j];
            const dx = p.x - p2.x;
            const dy = p.y - p2.y;
            const dist = Math.sqrt(dx * dx + dy * dy);

            if (dist < lineDistance) {
                const alpha = (1 - dist / lineDistance) * (props.isDark ? 0.22 : 0.16);
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

// Intersection Observer: Pause canvas when hero is out of view (saves 100% battery)
let observer: IntersectionObserver | null = null;

onMounted(() => {
    handleResize();
    window.addEventListener('resize', handleResize, { passive: true });

    if (heroSectionRef.value) {
        heroSectionRef.value.addEventListener('mousemove', handleMouseMove, { passive: true });
        heroSectionRef.value.addEventListener('mouseleave', handleMouseLeave, { passive: true });
        heroSectionRef.value.addEventListener('touchmove', handleTouchMove, { passive: true });
        heroSectionRef.value.addEventListener('touchend', handleTouchEnd, { passive: true });

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
    if (observer && heroSectionRef.value) observer.unobserve(heroSectionRef.value);
    if (heroSectionRef.value) {
        heroSectionRef.value.removeEventListener('mousemove', handleMouseMove);
        heroSectionRef.value.removeEventListener('mouseleave', handleMouseLeave);
        heroSectionRef.value.removeEventListener('touchmove', handleTouchMove);
        heroSectionRef.value.removeEventListener('touchend', handleTouchEnd);
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
</script>

<template>
    <section
        id="hero"
        ref="heroSectionRef"
        class="relative min-h-[calc(100vh-65px)] flex flex-col justify-center items-center text-center px-4 sm:px-6 lg:px-8 overflow-hidden border-b-2 border-neutral-200/80 dark:border-neutral-800/80 cursor-default select-none"
    >
        <!-- Interactive Reactive Canvas Background -->
        <canvas
            ref="canvasRef"
            class="absolute inset-0 pointer-events-none z-0"
        />

        <!-- Soft Radiant Ambient Aura Behind Text -->
        <div
            class="pointer-events-none absolute inset-0 z-0 bg-radial from-emerald-100/30 via-transparent to-transparent dark:from-emerald-950/20 dark:via-transparent dark:to-transparent"
        />

        <!-- Hero Content: Simple, Focused, Monumental -->
        <div class="relative z-10 max-w-3xl mx-auto py-12 sm:py-16">

            <!-- Monumental Name Headline -->
            <h1
                class="text-5xl sm:text-7xl md:text-8xl font-black tracking-tight text-neutral-950 dark:text-white leading-[1.05]"
            >
                {{ data.name }}
            </h1>

            <!-- Subtitle -->
            <p
                class="text-xl sm:text-2xl md:text-3xl font-semibold tracking-tight text-neutral-800 dark:text-neutral-200 mt-4"
            >
                <span class="bg-linear-to-r from-emerald-600 via-teal-600 to-sky-600 dark:from-emerald-400 dark:via-teal-300 dark:to-sky-400 bg-clip-text text-transparent">
                    {{ data.title }}
                </span>
            </p>

            <!-- Refined Concise Value Statement -->
            <p
                class="text-sm sm:text-base md:text-lg text-neutral-600 dark:text-neutral-400 mt-4 max-w-xl mx-auto leading-relaxed font-normal"
            >
                {{ data.intro }}
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-3 mt-8">
                <a
                    :href="data.cvPdfUrl"
                    target="_blank"
                    download
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-full text-xs sm:text-sm font-semibold bg-neutral-950 dark:bg-white text-white dark:text-neutral-950 hover:bg-neutral-800 dark:hover:bg-neutral-100 transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5 cursor-pointer"
                >
                    <Download class="w-4 h-4" />
                    <span>{{ t('download_cv') }}</span>
                </a>

                <button
                    type="button"
                    @click="scrollTo('#contact')"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-full text-xs sm:text-sm font-semibold bg-white/90 dark:bg-neutral-900/90 text-neutral-800 dark:text-neutral-200 border border-neutral-300 dark:border-neutral-700 hover:border-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all shadow-xs backdrop-blur-md transform hover:-translate-y-0.5 cursor-pointer"
                >
                    <Mail class="w-4 h-4" />
                    <span>{{ t('message_me') }}</span>
                </button>

                <a
                    :href="data.contact.linkedin"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-full text-xs sm:text-sm font-semibold text-neutral-600 dark:text-neutral-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors"
                >
                    <LinkedinIcon class="w-4 h-4 text-sky-600" />
                    <span>{{ t('linkedin') }}</span>
                </a>
            </div>

            <!-- Floating / Animated Stats Banner -->
            <div
                v-if="data.quickStats?.length"
                class="mt-8 grid max-w-4xl auto-cols-fr sm:grid-flow-col gap-3 rounded-2xl p-4 sm:gap-4 sm:p-5"
            >
                <div
                    v-for="(stat, index) in data.quickStats"
                    :key="index"
                    class="relative flex flex-col items-center justify-center rounded-xl p-2.5
                        after:absolute after:top-1/4 after:-right-1.5 after:h-1/2 after:w-0.5 after:rounded-full after:bg-neutral-300 sm:after:-right-2 dark:after:bg-neutral-700
                        max-md:even:after:hidden max-md:last:after:hidden md:last:after:hidden"
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

        <!-- Scroll down indicator -->
        <div class="absolute bottom-4 inset-x-0 flex justify-center z-10 pointer-events-auto">
            <button
                type="button"
                @click="scrollTo('#about')"
                class="flex flex-col items-center gap-1 text-neutral-400 dark:text-neutral-500 hover:text-neutral-800 dark:hover:text-neutral-200 transition-colors cursor-pointer group"
                aria-label="Scroll to about section"
            >
                <span class="text-[10px] font-mono tracking-wider uppercase">{{ t('scroll') }}</span>
                <ChevronDown
                    name="chevron-down"
                    class="w-3.5 h-3.5 animate-bounce group-hover:text-emerald-500 transition-colors"
                />
            </button>
        </div>
    </section>
</template>
