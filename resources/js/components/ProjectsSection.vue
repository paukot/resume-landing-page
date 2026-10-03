<script setup lang="ts">
import type { ProjectItem } from '@/types/resume';
import {useTranslations} from "@/composables/useTranslations";
import GithubIcon from "@/components/icons/GithubIcon.vue";

defineProps<{
    projects: ProjectItem[];
}>();

const { t } = useTranslations();
</script>

<template>
    <section
        id="projects"
        class="border-b-2 border-neutral-200/80 py-16 dark:border-neutral-800/80"
    >
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div
                class="mb-8 flex items-center gap-2 text-xs font-semibold tracking-wider text-neutral-500 uppercase dark:text-neutral-400"
            >
                <span>05</span>
                <span>/</span>
                <span>{{ t('section.featured_projects') }}</span>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div
                    v-for="project in projects"
                    :key="project.id"
                    class="flex flex-col justify-between rounded-xl border border-neutral-200/80 bg-neutral-50 p-6 transition-colors hover:border-neutral-400 dark:border-neutral-800/80 dark:bg-neutral-900 dark:hover:border-neutral-600"
                >
                    <div>
                        <!-- Category & Title -->
                        <div
                            class="mb-2 flex items-center justify-between gap-2"
                        >
                            <span
                                class="font-mono text-[11px] text-neutral-500 dark:text-neutral-400"
                            >
                                {{ project.category }}
                            </span>
                            <span
                                v-if="project.featured"
                                class="rounded-sm bg-neutral-200 px-2 py-0.5 text-[10px] font-semibold tracking-wider text-neutral-700 uppercase dark:bg-neutral-800 dark:text-neutral-300"
                            >
                                {{ t('featured') }}
                            </span>
                        </div>

                        <h3
                            class="text-base font-bold text-neutral-950 dark:text-white"
                        >
                            {{ project.title }}
                        </h3>
                        <p
                            class="mt-1 text-xs font-medium text-neutral-600 dark:text-neutral-400"
                        >
                            {{ project.tagline }}
                        </p>
                        <p
                            class="mt-3 text-xs leading-relaxed text-neutral-600 dark:text-neutral-400"
                        >
                            {{ project.description }}
                        </p>

                        <!-- Highlights -->
                        <ul class="mt-3 space-y-1">
                            <li
                                v-for="(hl, i) in project.highlights"
                                :key="i"
                                class="flex items-start gap-1.5 text-[11px] text-neutral-600 dark:text-neutral-400"
                            >
                                <span class="shrink-0 text-neutral-400">•</span>
                                <span>{{ hl }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Tech stack & links -->
                    <div
                        class="mt-5 flex items-center justify-between gap-2 border-t border-neutral-200/60 pt-4 dark:border-neutral-800/60"
                    >
                        <div class="flex flex-wrap gap-1">
                            <span
                                v-for="tech in project.technologies.slice(0, 4)"
                                :key="tech"
                                class="rounded border border-neutral-200 bg-white px-1.5 py-0.5 font-mono text-[10px] text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300"
                            >
                                {{ tech }}
                            </span>
                        </div>

                        <a
                            v-if="project.githubUrl"
                            :href="project.githubUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1 text-xs font-medium text-neutral-700 transition-colors hover:text-neutral-950 dark:text-neutral-300 dark:hover:text-white"
                        >
                            <GithubIcon className="w-3.5 h-3.5" />
                            <span>{{ t('code') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
