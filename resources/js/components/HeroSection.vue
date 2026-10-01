<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import type { ResumeData } from '../types/resume';
import Icon from './Icon.vue';

const props = defineProps<{
    data: ResumeData;
}>();

const emit = defineEmits<{
    (e: 'open-resume'): void;
}>();

// Dynamic role typewriter/rotator
const currentRoleIndex = ref(0);
const displayedRole = ref('');
const isDeleting = ref(false);
let roleTimer: ReturnType<typeof setTimeout> | null = null;

const typeSpeed = 80;
const deleteSpeed = 40;
const pauseTime = 2200;

const tickRole = () => {
    const fullRole =
        props.data.roles[currentRoleIndex.value] || props.data.title;

    if (!isDeleting.value) {
        displayedRole.value = fullRole.slice(0, displayedRole.value.length + 1);
        if (displayedRole.value === fullRole) {
            isDeleting.value = true;
            roleTimer = setTimeout(tickRole, pauseTime);
            return;
        }
        roleTimer = setTimeout(tickRole, typeSpeed);
    } else {
        displayedRole.value = fullRole.slice(0, displayedRole.value.length - 1);
        if (displayedRole.value === '') {
            isDeleting.value = false;
            currentRoleIndex.value =
                (currentRoleIndex.value + 1) % props.data.roles.length;
            roleTimer = setTimeout(tickRole, 400);
            return;
        }
        roleTimer = setTimeout(tickRole, deleteSpeed);
    }
};

// Subtle interactive mouse spotlight effect
const heroRef = ref<HTMLElement | null>(null);
const mouseX = ref(50);
const mouseY = ref(50);

const handleMouseMove = (e: MouseEvent) => {
    if (!heroRef.value) return;
    const rect = heroRef.value.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;
    mouseX.value = Math.max(0, Math.min(100, x));
    mouseY.value = Math.max(0, Math.min(100, y));
};

const scrollTo = (selector: string) => {
    const el = document.querySelector(selector);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth' });
    }
};

onMounted(() => {
    tickRole();
    if (heroRef.value) {
        heroRef.value.addEventListener('mousemove', handleMouseMove, {
            passive: true,
        });
    }
});

onUnmounted(() => {
    if (roleTimer) clearTimeout(roleTimer);
    if (heroRef.value) {
        heroRef.value.removeEventListener('mousemove', handleMouseMove);
    }
});
</script>

<template>
    <section
        id="hero"
        ref="heroRef"
        class="relative flex min-h-[92vh] flex-col items-center justify-center overflow-hidden pt-24 pb-16"
    >
        <!-- Dynamic Spotlight Gradient Follower -->
        <div
            class="pointer-events-none absolute inset-0 opacity-40 transition-opacity duration-1000 dark:opacity-30"
            :style="{
                background: `radial-gradient(650px circle at ${mouseX}% ${mouseY}%, rgba(139, 92, 246, 0.18), transparent 70%)`,
            }"
        />

        <!-- Ambient Animated Glow Orbs -->
        <div
            class="animate-pulse-glow pointer-events-none absolute -top-24 -left-24 h-96 w-96 rounded-full bg-violet-500/20 blur-3xl dark:bg-violet-600/15"
        />
        <div
            class="animate-float-reverse pointer-events-none absolute top-1/2 -right-24 h-96 w-96 rounded-full bg-indigo-500/20 blur-3xl dark:bg-indigo-600/15"
        />
        <div
            class="animate-float-slow pointer-events-none absolute -bottom-24 left-1/3 h-80 w-80 rounded-full bg-cyan-500/15 blur-3xl dark:bg-cyan-600/10"
        />

        <div
            class="relative z-10 mx-auto max-w-5xl px-4 text-center sm:px-6 lg:px-8"
        >
            <!-- Availability Badge -->
            <div class="mb-6 inline-flex items-center gap-2">
                <div
                    class="group inline-flex items-center gap-2 rounded-full border border-neutral-200/80 bg-neutral-100/90 px-3.5 py-1.5 text-xs font-medium shadow-xs backdrop-blur-md transition-all hover:border-violet-300 dark:border-neutral-800/80 dark:bg-neutral-900/90 dark:hover:border-violet-700"
                >
                    <span class="relative flex h-2 w-2">
                        <span
                            class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"
                        />
                        <span
                            class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"
                        />
                    </span>
                    <span class="text-neutral-700 dark:text-neutral-300">
                        {{ data.status.text }}
                    </span>
                </div>
            </div>

            <!-- Greeting & Dynamic Role Headline -->
            <h1
                class="mb-6 text-4xl leading-[1.1] font-extrabold tracking-tight text-neutral-900 sm:text-6xl lg:text-7xl dark:text-white"
            >
                Crafting digital experiences with
                <span
                    class="bg-gradient-to-r from-violet-600 via-indigo-600 to-cyan-500 bg-clip-text text-transparent dark:from-violet-400 dark:via-indigo-300 dark:to-cyan-300"
                >
                    precision & scale.
                </span>
            </h1>

            <!-- Animated Typing Role Subheadline -->
            <div
                class="mb-6 flex min-h-[2.5rem] items-center justify-center gap-1.5 text-lg font-medium text-neutral-700 sm:text-2xl dark:text-neutral-300"
            >
                <span>Hi, I'm</span>
                <span
                    class="font-bold text-violet-600 underline decoration-violet-400/40 decoration-wavy underline-offset-4 dark:text-violet-400"
                >
                    {{ data.name }}
                </span>
                <span class="text-neutral-400 dark:text-neutral-600">—</span>
                <span
                    class="inline-flex items-center font-semibold text-neutral-900 dark:text-neutral-100"
                >
                    {{ displayedRole }}
                    <span
                        class="ml-1 inline-block h-5 w-0.5 animate-pulse bg-violet-600 dark:bg-violet-400"
                    />
                </span>
            </div>

            <!-- Value Proposition Tagline -->
            <p
                class="mx-auto mb-9 max-w-2xl text-base leading-relaxed text-neutral-600 sm:text-lg dark:text-neutral-400"
            >
                {{ data.tagline }}
            </p>

            <!-- Hero Action Buttons -->
            <div
                class="mb-14 flex flex-wrap items-center justify-center gap-3.5"
            >
                <button
                    type="button"
                    @click="scrollTo('#projects')"
                    class="group relative inline-flex transform cursor-pointer items-center gap-2 rounded-full bg-neutral-900 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-neutral-900/10 transition-all hover:-translate-y-0.5 hover:bg-neutral-800 hover:shadow-lg dark:bg-white dark:text-neutral-950 dark:hover:bg-neutral-100"
                >
                    <span>View Projects</span>
                    <Icon
                        name="arrow-right"
                        className="w-4 h-4 transition-transform group-hover:translate-x-1"
                    />
                </button>

                <button
                    type="button"
                    @click="emit('open-resume')"
                    class="inline-flex transform cursor-pointer items-center gap-2 rounded-full border border-neutral-200 bg-white/80 px-6 py-3 text-sm font-semibold text-neutral-900 shadow-xs backdrop-blur-md transition-all hover:-translate-y-0.5 hover:bg-neutral-100 dark:border-neutral-800 dark:bg-neutral-900/80 dark:text-white dark:hover:bg-neutral-800"
                >
                    <Icon
                        name="download"
                        className="w-4 h-4 text-violet-600 dark:text-violet-400"
                    />
                    <span>Download CV</span>
                </button>

                <button
                    type="button"
                    @click="scrollTo('#contact')"
                    class="inline-flex cursor-pointer items-center gap-2 rounded-full px-5 py-3 text-sm font-medium text-neutral-600 transition-colors hover:bg-neutral-100/60 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-900/60 dark:hover:text-white"
                >
                    <Icon name="mail" className="w-4 h-4" />
                    <span>Get in Touch</span>
                </button>
            </div>

            <!-- Floating / Animated Stats Banner -->
            <div
                class="mx-auto grid max-w-4xl grid-cols-2 gap-3 rounded-2xl border border-neutral-200/70 bg-white/70 p-4 shadow-sm backdrop-blur-md sm:gap-4 sm:p-5 md:grid-cols-4 dark:border-neutral-800/70 dark:bg-neutral-900/70"
            >
                <div
                    v-for="(stat, index) in data.quickStats"
                    :key="index"
                    class="flex flex-col items-center justify-center rounded-xl p-2.5 transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-800/50"
                >
                    <div
                        class="bg-gradient-to-r from-violet-600 to-indigo-600 bg-clip-text text-2xl font-extrabold tracking-tight text-transparent sm:text-3xl dark:from-violet-400 dark:to-indigo-300"
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

        <!-- Scroll Down Cue -->
        <div class="absolute inset-x-0 bottom-4 z-10 flex justify-center">
            <button
                type="button"
                @click="scrollTo('#about')"
                class="group flex cursor-pointer flex-col items-center gap-1 text-neutral-400 transition-colors hover:text-neutral-700 dark:text-neutral-500 dark:hover:text-neutral-300"
                aria-label="Scroll to about section"
            >
                <span class="text-[11px] font-medium tracking-wider uppercase"
                    >Explore</span
                >
                <Icon
                    name="chevron-down"
                    className="w-4 h-4 animate-bounce group-hover:text-violet-600 dark:group-hover:text-violet-400"
                />
            </button>
        </div>
    </section>
</template>
