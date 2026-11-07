<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useTranslations } from '@/composables/useTranslations';
import { Link, usePage } from '@inertiajs/vue3';
import { home } from '@/routes';
import { Download, Menu, Moon, Sun, X } from 'lucide-vue-next';
import type { SectionSettings } from '@/types/resume';

const props = defineProps<{
    name: string;
    isDark: boolean;
    cvPdfUrl: string;
    settings?: SectionSettings;
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

const navItems = computed(() => {
    const items = [
        {
            label: 'about',
            href: '#about',
            id: 'about',
            visible: props.settings?.showAbout ?? true,
        },
        {
            label: 'experience',
            href: '#experience',
            id: 'experience',
            visible: props.settings?.showExperience ?? true,
        },
        {
            label: 'skills',
            href: '#skills',
            id: 'skills',
            visible: props.settings?.showSkills ?? true,
        },
        {
            label: 'education',
            href: '#education',
            id: 'education',
            visible: props.settings?.showEducation ?? true,
        },
        {
            label: 'projects',
            href: '#projects',
            id: 'projects',
            visible: props.settings?.showProjects ?? true,
        },
        {
            label: 'contact',
            href: '#contact',
            id: 'contact',
            visible: props.settings?.showContact ?? true,
        },
    ];

    return items.filter((item) => item.visible);
});

const switchToLanguage = computed(() =>
    page.props.locale === 'en' ? 'pl' : 'en',
);
const switchLangUrl = computed(() =>
    home.url({ query: { lang: switchToLanguage.value } }),
);
const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;

    const sections = navItems.value.map((item) => item.id);
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
                    class="text-xs font-medium transition-colors lg:text-sm"
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
                    :href="switchLangUrl"
                    :title="
                        switchToLanguage === 'en' ? t('english') : t('polish')
                    "
                    :aria-label="
                        switchToLanguage === 'en' ? t('english') : t('polish')
                    "
                    preserve-scroll
                    class="inline-flex items-center p-2"
                >
                    <span
                        :class="`fi fi-${switchToLanguage === 'en' ? 'gb' : 'pl'} fis rounded-sm shadow-md shadow-neutral-300 dark:shadow-neutral-700`"
                    />
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
                    v-if="cvPdfUrl"
                    :href="cvPdfUrl"
                    target="_blank"
                    download
                    class="hidden items-center gap-1.5 rounded-lg bg-neutral-900 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-neutral-800 sm:inline-flex dark:bg-white dark:text-neutral-950 dark:hover:bg-neutral-100"
                >
                    <Download class="h-3.5 w-3.5" />
                    <span> {{ t('download_cv') }}</span>
                </a>

                <!-- Mobile Menu Button -->
                <button
                    type="button"
                    @click="isMobileMenuOpen = !isMobileMenuOpen"
                    class="rounded-lg p-2 text-neutral-700 hover:bg-neutral-100 md:hidden dark:text-neutral-200 dark:hover:bg-neutral-800"
                    aria-label="Toggle mobile menu"
                    :aria-expanded="isMobileMenuOpen"
                    aria-controls="mobile-nav-menu"
                >
                    <X v-if="isMobileMenuOpen" class="h-5 w-5" />
                    <Menu v-else class="h-5 w-5" />
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div
            v-if="isMobileMenuOpen"
            id="mobile-nav-menu"
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
                    target="_blank"
                    download
                    class="mt-2 flex items-center justify-center gap-2 rounded-lg bg-neutral-900 py-2 text-xs font-semibold text-white dark:bg-white dark:text-neutral-950"
                >
                    <Download class="h-4 w-4" />
                    <span> {{ t('download_cv') }} </span>
                </a>
            </div>
        </div>
    </header>
</template>
