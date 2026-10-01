<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import Icon from './Icon.vue';

defineProps<{
    name: string;
    isDark: boolean;
}>();

const emit = defineEmits<{
    (e: 'toggle-theme'): void;
    (e: 'open-resume'): void;
}>();

const isScrolled = ref(false);
const isMobileMenuOpen = ref(false);
const activeSection = ref('hero');

const navItems = [
    { label: 'About', href: '#about' },
    { label: 'Experience', href: '#experience' },
    { label: 'Skills', href: '#skills' },
    { label: 'Education', href: '#education' },
    { label: 'Projects', href: '#projects' },
    { label: 'Contact', href: '#contact' },
];

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;

    // Detect active section
    const sections = [
        'about',
        'experience',
        'skills',
        'education',
        'projects',
        'contact',
    ];
    const scrollPosition = window.scrollY + 200;

    for (const section of sections) {
        const el = document.getElementById(section);
        if (el) {
            const top = el.offsetTop;
            const height = el.offsetHeight;
            if (scrollPosition >= top && scrollPosition < top + height) {
                activeSection.value = section;
                break;
            }
        }
    }
    if (window.scrollY < 180) {
        activeSection.value = 'hero';
    }
};

const scrollTo = (href: string) => {
    isMobileMenuOpen.value = false;
    const target = document.querySelector(href);
    if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
    }
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <header
        class="no-print fixed inset-x-0 top-0 z-50 transition-all duration-300"
        :class="[
            isScrolled
                ? 'border-b border-neutral-200/80 bg-white/80 py-3 shadow-sm backdrop-blur-md dark:border-neutral-800/80 dark:bg-neutral-950/80'
                : 'bg-transparent py-5',
        ]"
    >
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <!-- Brand / Logo -->
                <a
                    href="#hero"
                    @click.prevent="scrollTo('#hero')"
                    class="group flex items-center gap-2.5 font-semibold text-neutral-900 transition-opacity hover:opacity-90 dark:text-neutral-100"
                >
                    <span
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-violet-600 to-indigo-600 text-sm font-bold text-white shadow-md shadow-violet-500/25 transition-transform group-hover:scale-105"
                    >
                        {{ name.charAt(0) }}
                    </span>
                    <span class="text-base font-medium tracking-tight">
                        {{ name }}
                        <span
                            class="font-bold text-violet-600 dark:text-violet-400"
                            >.</span
                        >
                    </span>
                </a>

                <!-- Desktop Navigation Links -->
                <nav
                    class="hidden items-center gap-1 rounded-full border border-neutral-200/60 bg-neutral-100/80 p-1.5 backdrop-blur-md md:flex dark:border-neutral-800/60 dark:bg-neutral-900/80"
                >
                    <a
                        v-for="item in navItems"
                        :key="item.href"
                        :href="item.href"
                        @click.prevent="scrollTo(item.href)"
                        class="rounded-full px-3.5 py-1.5 text-xs font-medium transition-all duration-200"
                        :class="[
                            activeSection === item.href.slice(1)
                                ? 'bg-white font-semibold text-neutral-900 shadow-xs dark:bg-neutral-800 dark:text-white'
                                : 'text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white',
                        ]"
                    >
                        {{ item.label }}
                    </a>
                </nav>

                <!-- Actions (Theme Toggle & CV Button) -->
                <div class="flex items-center gap-2.5">
                    <!-- Theme Toggle -->
                    <button
                        type="button"
                        @click="emit('toggle-theme')"
                        class="rounded-full p-2 text-neutral-600 transition-colors hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800"
                        :title="
                            isDark
                                ? 'Switch to light mode'
                                : 'Switch to dark mode'
                        "
                        aria-label="Toggle theme"
                    >
                        <Icon
                            v-if="isDark"
                            name="sun"
                            className="w-4 h-4 text-amber-400"
                        />
                        <Icon
                            v-else
                            name="moon"
                            className="w-4 h-4 text-neutral-700"
                        />
                    </button>

                    <!-- View CV Button -->
                    <button
                        type="button"
                        @click="emit('open-resume')"
                        class="hidden cursor-pointer items-center gap-1.5 rounded-full bg-neutral-900 px-3.5 py-1.5 text-xs font-semibold text-white shadow-xs transition-all hover:bg-neutral-800 sm:inline-flex dark:bg-white dark:text-neutral-950 dark:hover:bg-neutral-100"
                    >
                        <Icon name="download" className="w-3.5 h-3.5" />
                        <span>Resume</span>
                    </button>

                    <!-- Mobile Menu Hamburger -->
                    <button
                        type="button"
                        @click="isMobileMenuOpen = !isMobileMenuOpen"
                        class="rounded-lg p-2 text-neutral-700 transition-colors hover:bg-neutral-100 md:hidden dark:text-neutral-200 dark:hover:bg-neutral-800"
                        aria-label="Toggle mobile menu"
                    >
                        <Icon
                            v-if="isMobileMenuOpen"
                            name="x-mark"
                            className="w-5 h-5"
                        />
                        <svg
                            v-else
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Dropdown Menu -->
        <div
            v-if="isMobileMenuOpen"
            class="border-b border-neutral-200 bg-white px-4 pt-3 pb-5 shadow-xl md:hidden dark:border-neutral-800 dark:bg-neutral-950"
        >
            <div class="flex flex-col gap-1">
                <a
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    @click.prevent="scrollTo(item.href)"
                    class="rounded-lg px-3 py-2 text-sm font-medium transition-colors"
                    :class="[
                        activeSection === item.href.slice(1)
                            ? 'bg-violet-50 text-violet-700 dark:bg-violet-950/40 dark:text-violet-300'
                            : 'text-neutral-700 hover:bg-neutral-50 dark:text-neutral-300 dark:hover:bg-neutral-900',
                    ]"
                >
                    {{ item.label }}
                </a>
                <button
                    type="button"
                    @click="
                        emit('open-resume');
                        isMobileMenuOpen = false;
                    "
                    class="mt-2 flex w-full items-center justify-center gap-2 rounded-lg bg-neutral-900 py-2.5 text-xs font-semibold text-white dark:bg-white dark:text-neutral-950"
                >
                    <Icon name="download" className="w-4 h-4" />
                    <span>View & Download CV</span>
                </button>
            </div>
        </div>
    </header>
</template>
