<script setup lang="ts">
import type { ExperienceItem } from '@/types/resume';
import {useTranslations} from "@/composables/useTranslations";

defineProps<{
    experience: ExperienceItem[];
}>();

const { t } = useTranslations();
</script>

<template>
    <section
        id="experience"
        class="border-b border-neutral-200/80 py-16 dark:border-neutral-800/80"
    >
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div
                class="mb-8 flex items-center gap-2 text-xs font-semibold tracking-wider text-neutral-500 uppercase dark:text-neutral-400"
            >
                <span>02</span>
                <span>/</span>
                <span>{{ t('section.work_experience') }}</span>
            </div>

            <div class="space-y-12">
                <div
                    v-for="job in experience"
                    :key="job.id"
                    class="group relative border-l-2 border-neutral-200 pl-6 dark:border-neutral-800"
                >
                    <!-- Timeline Marker -->
                    <div
                        class="absolute top-1.5 -left-2.25 h-4 w-4 rounded-full border-2 border-neutral-400 bg-white transition-colors group-hover:border-neutral-900 dark:border-neutral-600 dark:bg-neutral-950 dark:group-hover:border-white"
                    />

                    <!-- Job Header -->
                    <div
                        class="flex flex-col justify-between gap-1 sm:flex-row sm:items-baseline"
                    >
                        <div>
                            <h3
                                class="text-base font-bold text-neutral-950 sm:text-lg dark:text-white"
                            >
                                {{ job.role }}
                            </h3>
                            <div
                                class="mt-0.5 text-sm font-medium text-neutral-700 dark:text-neutral-300"
                            >
                                {{ job.company }}
                                <span
                                    class="text-neutral-400 dark:text-neutral-600"
                                    >•</span
                                >
                                <span
                                    class="text-xs text-neutral-500 dark:text-neutral-400"
                                    >{{ job.location }}</span
                                >
                            </div>
                        </div>
                        <div
                            class="mt-1 shrink-0 font-mono text-xs text-neutral-500 sm:mt-0 dark:text-neutral-400"
                        >
                            {{ job.period }}
                        </div>
                    </div>

                    <!-- Description -->
                    <p
                        v-if="job.description"
                        class="mt-3 text-xs leading-relaxed text-neutral-600 sm:text-sm dark:text-neutral-400"
                    >
                        {{ job.description }}
                    </p>

                    <!-- Key Contributions Bullet Points -->
                    <ul class="mt-4 space-y-2">
                        <li
                            v-for="(hl, i) in job.highlights"
                            :key="i"
                            class="flex items-start gap-2 text-xs leading-relaxed text-neutral-700 sm:text-sm dark:text-neutral-300"
                        >
                            <span
                                class="mt-0.5 text-neutral-400 select-none dark:text-neutral-600"
                                >•</span
                            >
                            <span>{{ hl }}</span>
                        </li>
                    </ul>

                    <!-- Tech Stack Tags -->
                    <div class="mt-4 flex flex-wrap items-center gap-1.5">
                        <span
                            v-for="tech in job.technologies"
                            :key="tech"
                            class="rounded-md border border-neutral-200/60 bg-neutral-100 px-2 py-0.5 text-[11px] font-medium text-neutral-700 dark:border-neutral-700/60 dark:bg-neutral-800/80 dark:text-neutral-300"
                        >
                            {{ tech }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
