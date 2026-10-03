<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import Icon from './icons/Icon.vue';
import { useTranslations } from '@/composables/useTranslations';
import {Link, usePage} from "@inertiajs/vue3";
import {Download, Menu, Moon, Sun, X} from "lucide-vue-next";

defineProps<{
    name: string;
    isDark: boolean;
    cvPdfUrl: string;
}>();

const { t } = useTranslations();

const emit = defineEmits<{
    (e: 'toggle-theme'): void;
    (e: 'open-resume'): void;
}>();

const page = usePage();
const isScrolled = ref(false);
const isMobileMenuOpen = ref(false);
const activeSection = ref('hero');

const navItems = [
    { label: 'about', href: '#about' },
    { label: 'experience', href: '#experience' },
    { label: 'skills', href: '#skills' },
    { label: 'education', href: '#education' },
    { label: 'projects', href: '#projects' },
    { label: 'contact', href: '#contact' },
];

const switchToLanguage = page.props.locale === 'en' ? 'pl' : 'en';
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
                <span class="text-sm font-semibold trackitext-xs ng-tight">
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
                    class="text-xs lg:text-sm font-medium transition-colors"
                    :class="[
                        activeSection === item.href.slice(1)
                            ? 'font-semibold text-neutral-900 dark:text-white'
                            : 'text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white',
                    ]"
                >
                    {{ t(`nav.${item.label}`) }}
                </a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <!-- Lang switcher -->
                <Link
                    :href="`/?lang=${switchToLanguage}`"
                    :title="switchToLanguage === 'en' ? t('polish') : t('english')"
                    :aria-label="switchToLanguage === 'en' ? t('polish') : t('english')"
                    preserve-scroll
                    class="inline-flex items-center p-2"

                >
                    <span :class="`fi fi-${switchToLanguage === 'en' ? 'gb' : 'pl'} fis rounded-sm`" />
                </Link>

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
                    <Sun v-if="isDark" class="h-4 w-4 text-neutral-200" />
                    <Moon v-else class="h-4 w-4 text-neutral-700" />
                </button>

                <!-- CV PDF Button -->
                <a
                    :href="cvPdfUrl"
                    download="paulina_kot_php_developer_en.pdf"
                    class="hidden items-center gap-1.5 rounded-lg bg-neutral-900 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-neutral-800 sm:inline-flex dark:bg-white dark:text-neutral-950 dark:hover:bg-neutral-100"
                >
                    <Download class="w-3.5 h-3.5" />
                    <span> {{ t('download_cv') }}</span>
                </a>

                <!-- Mobile Menu Button -->
                <button
                    type="button"
                    @click="isMobileMenuOpen = !isMobileMenuOpen"
                    class="rounded-lg p-2 text-neutral-700 hover:bg-neutral-100 md:hidden dark:text-neutral-200 dark:hover:bg-neutral-800"
                    aria-label="Toggle mobile menu"
                >

                    <X
                        v-if="isMobileMenuOpen"
                        class="w-5 h-5"
                    />
                    <Menu
                        v-else
                        class="w-5 h-5"
                    />
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
                    {{ t(`nav.${item.label}`) }}
                </a>
                <a
                    :href="cvPdfUrl"
                    download="paulina_kot_php_developer_en.pdf"
                    class="mt-2 flex items-center justify-center gap-2 rounded-lg bg-neutral-900 py-2 text-xs font-semibold text-white dark:bg-white dark:text-neutral-950"
                >
                    <Download class="w-4 h-4" />
                    <span> {{ t('download_cv') }} </span>
                </a>
            </div>
        </div>
    </header>
</template>
