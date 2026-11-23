<script setup lang="ts">
import { computed } from 'vue';
import type { ResumeData } from '@/types/resume';
import { useTranslations } from '@/composables/useTranslations';
import { visibleSections } from '@/lib/sections';
import StatCard from '@/components/ui/StatCard.vue';
import LinkedinIcon from '@/components/icons/LinkedinIcon.vue';
import GithubIcon from '@/components/icons/GithubIcon.vue';
import { ArrowRight, ChevronDown, Download, MapPin } from 'lucide-vue-next';

const props = defineProps<{
    data: ResumeData;
}>();

const { t } = useTranslations();

const sections = computed(() => visibleSections(props.data));
const nextSectionId = computed(() => sections.value[0] ?? null);
const isContactVisible = computed(() => sections.value.includes('contact'));
const canMessage = computed(
    () => isContactVisible.value || Boolean(props.data.contact?.email),
);

const statsGridClass = computed(() => {
    const count = props.data.quickStats?.length ?? 0;
    if (count <= 2) {
        return 'sm:grid-cols-2 lg:max-w-2xl';
    }
    return count === 3 ? 'sm:grid-cols-3' : 'lg:grid-cols-4';
});

const scrollTo = (id: string) => {
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
};

const handleMessageMe = () => {
    if (isContactVisible.value) {
        scrollTo('contact');
    } else if (props.data.contact?.email) {
        window.location.href = `mailto:${props.data.contact.email}`;
    }
};
</script>

<template>
    <section
        id="hero"
        class="hero-wash relative flex min-h-svh flex-col justify-center border-b border-zinc-300 pt-24 pb-24 sm:pt-28 dark:border-white/5"
    >
        <div
            class="mx-auto w-full max-w-6xl px-4 text-center sm:px-6 lg:px-8 lg:text-left"
        >
            <div
                v-if="data.contact?.location"
                v-reveal
                class="inline-flex items-center gap-2 rounded-full border border-zinc-300 bg-white px-3.5 py-1.5 text-xs font-medium text-zinc-700 shadow-sm dark:border-white/10 dark:bg-zinc-900 dark:text-zinc-300"
            >
                <span class="relative flex h-2 w-2">
                    <span
                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75 motion-reduce:animate-none"
                    />
                    <span
                        class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"
                    />
                </span>
                <MapPin class="h-3.5 w-3.5" />
                <span>{{ data.contact.location }}</span>
            </div>

            <h1
                v-reveal="1"
                class="mt-6 text-5xl leading-[0.95] font-bold tracking-tighter text-balance text-zinc-950 sm:text-7xl lg:text-8xl dark:text-white"
            >
                <span class="text-gradient">{{ data.name }}</span>
            </h1>

            <p
                v-if="data.title"
                v-reveal="2"
                class="mt-5 text-xl font-semibold tracking-tight text-zinc-800 sm:text-3xl dark:text-zinc-200"
            >
                {{ data.title }}
            </p>

            <p
                v-if="data.intro"
                v-reveal="3"
                class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-pretty text-zinc-700 sm:text-lg lg:mx-0 dark:text-zinc-400"
            >
                {{ data.intro }}
            </p>

            <div
                v-reveal="4"
                class="mt-9 flex flex-wrap items-center justify-center gap-3 lg:justify-start"
            >
                <a
                    v-if="data.cvPdfUrl"
                    :href="data.cvPdfUrl"
                    target="_blank"
                    download
                    class="btn btn-primary px-6 py-3 text-sm"
                >
                    <Download class="h-4 w-4" />
                    <span>{{ t('download_cv') }}</span>
                </a>

                <button
                    v-if="canMessage"
                    type="button"
                    @click="handleMessageMe"
                    class="group btn btn-secondary px-6 py-3 text-sm"
                >
                    <span>{{ t('message_me') }}</span>
                    <ArrowRight
                        class="h-4 w-4 transition-transform group-hover:translate-x-0.5"
                    />
                </button>

                <div
                    v-if="data.contact?.linkedin || data.contact?.github"
                    class="flex items-center gap-2"
                >
                    <a
                        v-if="data.contact.linkedin"
                        :href="data.contact.linkedin"
                        target="_blank"
                        rel="noopener noreferrer"
                        :aria-label="t('linkedin')"
                        :title="t('linkedin')"
                        class="btn btn-secondary h-11 w-11"
                    >
                        <LinkedinIcon class="h-4.5 w-4.5" />
                    </a>
                    <a
                        v-if="data.contact.github"
                        :href="data.contact.github"
                        target="_blank"
                        rel="noopener noreferrer"
                        :aria-label="t('github')"
                        :title="t('github')"
                        class="btn btn-secondary h-11 w-11"
                    >
                        <GithubIcon class="h-4.5 w-4.5" />
                    </a>
                </div>
            </div>

            <div
                v-if="data.quickStats?.length"
                class="mt-14 grid grid-cols-2 gap-3 text-left sm:gap-4 max-sm:[&>*:last-child:nth-child(odd)]:col-span-2"
                :class="statsGridClass"
            >
                <StatCard
                    v-for="(stat, index) in data.quickStats"
                    :key="index"
                    v-reveal="index + 4"
                    :stat="stat"
                />
            </div>
        </div>

        <div
            v-if="nextSectionId"
            class="absolute inset-x-0 bottom-6 flex justify-center"
        >
            <button
                type="button"
                @click="scrollTo(nextSectionId)"
                class="group flex cursor-pointer flex-col items-center gap-1 text-zinc-500 transition-colors hover:text-zinc-800 dark:text-zinc-500 dark:hover:text-zinc-200"
                :aria-label="t('scroll_next')"
            >
                <span
                    class="text-[10px] font-semibold tracking-[0.25em] uppercase"
                >
                    {{ t('scroll') }}
                </span>
                <ChevronDown
                    class="h-4 w-4 animate-bounce motion-reduce:animate-none"
                />
            </button>
        </div>
    </section>
</template>
