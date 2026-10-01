<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import Icon from './Icon.vue';

defineProps<{
    name: string;
    isDark: boolean;
    cvPdfUrl: string;
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

    const sections = [
        'about',
        'experience',
        'skills',
        'education',
        'projects',
        'contact',
    ];
    const scrollPosition = window.scrollY + 180;

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
    if (window.scrollY < 120) {
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
        class="no-print sticky inset-x-0 top-0 z-40 transition-colors duration-200"
        :class="[
            isScrolled
                ? 'border-b-2 border-neutral-200 bg-white/90 backdrop-blur-md dark:border-neutral-800 dark:bg-neutral-950/90'
                : 'border-b border-transparent bg-transparent',
        ]"
    >
        <div
            class="mx-auto flex h-16 max-w-5xl items-center justify-between px-4 sm:px-6 lg:px-8"
        >
            <!-- Brand -->
            <a
                href="#hero"
                @click.prevent="scrollTo('#hero')"
                class="flex items-center gap-2 font-medium text-neutral-900 transition-opacity hover:opacity-80 dark:text-white"
            >
                <span
                    class="flex h-7 w-7 items-center justify-center rounded-md bg-neutral-900 text-xs font-semibold tracking-wider text-white dark:bg-white dark:text-neutral-900"
                >
                    PK
                </span>
                <span class="text-sm font-semibold tracking-tight">
                    {{ name }}
                </span>
            </a>

            <!-- Desktop Nav -->
            <nav class="hidden items-center gap-6 md:flex">
                <a
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    @click.prevent="scrollTo(item.href)"
                    class="text-xs font-medium transition-colors"
                    :class="[
                        activeSection === item.href.slice(1)
                            ? 'font-semibold text-neutral-900 dark:text-white'
                            : 'text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white',
                    ]"
                >
                    {{ item.label }}
                </a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <!-- Theme Toggle -->
                <button
                    type="button"
                    @click="emit('toggle-theme')"
                    class="cursor-pointer rounded-lg p-2 text-neutral-600 transition-colors hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800"
                    :title="
                        isDark ? 'Switch to light mode' : 'Switch to dark mode'
                    "
                    aria-label="Toggle theme"
                >
                    <Icon
                        v-if="isDark"
                        name="sun"
                        className="w-4 h-4 text-neutral-200"
                    />
                    <Icon
                        v-else
                        name="moon"
                        className="w-4 h-4 text-neutral-700"
                    />
                </button>

                <!-- CV PDF Button -->
                <a
                    :href="cvPdfUrl"
                    download="paulina_kot_php_developer_en.pdf"
                    class="hidden items-center gap-1.5 rounded-lg bg-neutral-900 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-neutral-800 sm:inline-flex dark:bg-white dark:text-neutral-950 dark:hover:bg-neutral-100"
                >
                    <Icon name="download" className="w-3.5 h-3.5" />
                    <span>Download CV</span>
                </a>

                <!-- Mobile Menu Button -->
                <button
                    type="button"
                    @click="isMobileMenuOpen = !isMobileMenuOpen"
                    class="rounded-lg p-2 text-neutral-700 hover:bg-neutral-100 md:hidden dark:text-neutral-200 dark:hover:bg-neutral-800"
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

        <!-- Mobile Menu -->
        <div
            v-if="isMobileMenuOpen"
            class="border-b border-neutral-200 bg-white px-4 py-4 md:hidden dark:border-neutral-800 dark:bg-neutral-950"
        >
            <div class="flex flex-col gap-2">
                <a
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    @click.prevent="scrollTo(item.href)"
                    class="rounded-lg px-3 py-2 text-sm font-medium"
                    :class="[
                        activeSection === item.href.slice(1)
                            ? 'bg-neutral-100 text-neutral-900 dark:bg-neutral-800 dark:text-white'
                            : 'text-neutral-600 hover:bg-neutral-50 dark:text-neutral-400 dark:hover:bg-neutral-900',
                    ]"
                >
                    {{ item.label }}
                </a>
                <a
                    :href="cvPdfUrl"
                    download="paulina_kot_php_developer_en.pdf"
                    class="mt-2 flex items-center justify-center gap-2 rounded-lg bg-neutral-900 py-2 text-xs font-semibold text-white dark:bg-white dark:text-neutral-950"
                >
                    <Icon name="download" className="w-4 h-4" />
                    <span>Download CV (PDF)</span>
                </a>
            </div>
        </div>
    </header>
</template>
