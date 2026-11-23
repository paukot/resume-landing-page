<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref, toRef } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { home } from '@/routes';
import { useTranslations } from '@/composables/useTranslations';
import { useActiveSection } from '@/composables/useActiveSection';
import type { SectionId } from '@/lib/sections';
import { Download, Menu, Moon, Sun, X } from 'lucide-vue-next';

const props = defineProps<{
    name: string;
    isDark: boolean;
    cvPdfUrl: string;
    sections: SectionId[];
}>();

const emit = defineEmits<{
    (e: 'toggle-theme'): void;
}>();

const { t } = useTranslations();
const page = usePage();

const isScrolled = ref(false);
const scrollProgress = ref(0);
const isMobileMenuOpen = ref(false);
const { activeSection } = useActiveSection(toRef(props, 'sections'));

const switchToLanguage = computed(() =>
    page.props.locale === 'en' ? 'pl' : 'en',
);
const switchLangUrl = computed(() =>
    home.url({ query: { lang: switchToLanguage.value } }),
);
const switchLangLabel = computed(() =>
    switchToLanguage.value === 'en' ? t('english') : t('polish'),
);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
    const scrollable =
        document.documentElement.scrollHeight - window.innerHeight;
    scrollProgress.value =
        scrollable > 0 ? Math.min(window.scrollY / scrollable, 1) : 0;
};

const scrollTo = (id: string) => {
    isMobileMenuOpen.value = false;
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
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
        class="no-print fixed inset-x-0 top-0 z-40 transition-all duration-300"
        :class="
            isScrolled || isMobileMenuOpen
                ? 'border-b border-zinc-300 bg-white/85 backdrop-blur-xl dark:border-white/5 dark:bg-zinc-950/85'
                : 'border-b border-transparent'
        "
    >
        <div
            class="mx-auto flex h-(--nav-height) max-w-6xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8"
        >
            <a
                href="#hero"
                @click.prevent="scrollTo('hero')"
                class="flex items-center gap-2.5 transition-opacity hover:opacity-80"
            >
                <span
                    class="text-sm font-bold tracking-tight text-zinc-950 dark:text-white"
                >
                    {{ name }}
                </span>
            </a>

            <nav class="hidden items-center gap-1 md:flex">
                <a
                    v-for="id in sections"
                    :key="id"
                    :href="`#${id}`"
                    @click.prevent="scrollTo(id)"
                    :aria-current="
                        activeSection === id ? 'location' : undefined
                    "
                    class="relative rounded-full px-3 py-1.5 text-sm font-medium transition-colors"
                    :class="
                        activeSection === id
                            ? 'text-zinc-950 dark:text-white'
                            : 'text-zinc-600 hover:text-zinc-950 dark:text-zinc-400 dark:hover:text-white'
                    "
                >
                    {{ t(`nav.${id}`) }}
                    <span
                        class="absolute inset-x-3 -bottom-0.5 h-0.5 rounded-full bg-brand transition-opacity duration-300"
                        :class="
                            activeSection === id ? 'opacity-100' : 'opacity-0'
                        "
                        aria-hidden="true"
                    />
                </a>
            </nav>

            <div class="flex items-center gap-1.5">
                <Link
                    :href="switchLangUrl"
                    :title="switchLangLabel"
                    :aria-label="switchLangLabel"
                    preserve-scroll
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg transition-colors hover:bg-zinc-100 dark:hover:bg-white/5"
                >
                    <span
                        :class="`fi fi-${switchToLanguage === 'en' ? 'gb' : 'pl'} fis`"
                        class="block h-5! w-5! rounded-[3px] bg-cover! shadow-sm ring-1 ring-zinc-300 dark:ring-white/25"
                        aria-hidden="true"
                    />
                </Link>

                <button
                    type="button"
                    @click="emit('toggle-theme')"
                    class="btn btn-ghost h-9 w-9"
                    :title="isDark ? t('theme.light') : t('theme.dark')"
                    :aria-label="t('toggle_theme')"
                >
                    <Sun v-if="isDark" class="h-4 w-4" />
                    <Moon v-else class="h-4 w-4" />
                </button>

                <a
                    v-if="cvPdfUrl"
                    :href="cvPdfUrl"
                    target="_blank"
                    download
                    class="btn btn-primary ml-1 hidden px-4 py-2 text-xs sm:inline-flex"
                >
                    <Download class="h-3.5 w-3.5" />
                    <span>{{ t('download_cv') }}</span>
                </a>

                <button
                    v-if="sections.length || cvPdfUrl"
                    type="button"
                    @click="isMobileMenuOpen = !isMobileMenuOpen"
                    class="btn btn-ghost h-9 w-9 md:hidden"
                    :aria-label="t('toggle_menu')"
                    :aria-expanded="isMobileMenuOpen"
                    aria-controls="mobile-nav-menu"
                >
                    <X v-if="isMobileMenuOpen" class="h-5 w-5" />
                    <Menu v-else class="h-5 w-5" />
                </button>
            </div>
        </div>

        <div
            v-if="isMobileMenuOpen"
            id="mobile-nav-menu"
            class="border-t border-zinc-300 px-4 py-4 md:hidden dark:border-white/5"
        >
            <div class="flex flex-col gap-1">
                <a
                    v-for="id in sections"
                    :key="id"
                    :href="`#${id}`"
                    @click.prevent="scrollTo(id)"
                    :aria-current="
                        activeSection === id ? 'location' : undefined
                    "
                    class="rounded-xl px-3 py-2.5 text-sm font-medium"
                    :class="
                        activeSection === id
                            ? 'bg-zinc-100 text-zinc-950 dark:bg-white/5 dark:text-white'
                            : 'text-zinc-700 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:bg-white/5'
                    "
                >
                    {{ t(`nav.${id}`) }}
                </a>
                <a
                    v-if="cvPdfUrl"
                    :href="cvPdfUrl"
                    target="_blank"
                    download
                    class="btn btn-primary mt-2 w-full py-2.5 text-sm"
                >
                    <Download class="h-4 w-4" />
                    <span>{{ t('download_cv') }}</span>
                </a>
            </div>
        </div>

        <div
            class="absolute bottom-0 left-0 h-0.5 w-full origin-left bg-brand transition-opacity"
            :class="isScrolled ? 'opacity-100' : 'opacity-0'"
            :style="{ transform: `scaleX(${scrollProgress})` }"
            aria-hidden="true"
        />
    </header>
</template>
