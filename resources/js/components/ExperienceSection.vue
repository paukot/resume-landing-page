<script setup lang="ts">
import type { ExperienceItem } from '../types/resume';
import Icon from './Icon.vue';

defineProps<{
    experience: ExperienceItem[];
}>();
</script>

<template>
    <section
        id="experience"
        class="relative border-t border-neutral-200/60 py-20 dark:border-neutral-800/60"
    >
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="mb-12 flex items-center gap-3">
                <span
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400"
                >
                    <Icon name="briefcase" className="w-4 h-4" />
                </span>
                <div>
                    <h2
                        class="text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl dark:text-white"
                    >
                        Work Experience
                    </h2>
                    <p
                        class="text-xs text-neutral-500 sm:text-sm dark:text-neutral-400"
                    >
                        Career track record, leadership roles, and technical
                        achievements
                    </p>
                </div>
            </div>

            <!-- Timeline Container -->
            <div
                class="relative space-y-10 border-l-2 border-neutral-200 pl-6 sm:space-y-12 sm:pl-8 dark:border-neutral-800"
            >
                <div
                    v-for="job in experience"
                    :key="job.id"
                    class="group relative"
                >
                    <!-- Timeline Node Pin -->
                    <div
                        class="absolute top-1.5 -left-[31px] h-4 w-4 rounded-full border-2 bg-white transition-all duration-300 sm:-left-[39px] dark:bg-neutral-950"
                        :class="[
                            job.isCurrent
                                ? 'border-violet-600 ring-4 ring-violet-500/20 dark:border-violet-400'
                                : 'border-neutral-400 group-hover:border-violet-500 dark:border-neutral-600',
                        ]"
                    >
                        <span
                            v-if="job.isCurrent"
                            class="absolute inset-0.5 animate-pulse rounded-full bg-violet-600 dark:bg-violet-400"
                        />
                    </div>

                    <!-- Job Card -->
                    <div
                        class="rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-xs transition-all hover:border-neutral-300 hover:shadow-md sm:p-7 dark:border-neutral-800/80 dark:bg-neutral-900 dark:hover:border-neutral-700"
                    >
                        <!-- Job Header -->
                        <div
                            class="flex flex-col gap-2 border-b border-neutral-100 pb-4 sm:flex-row sm:items-center sm:justify-between dark:border-neutral-800"
                        >
                            <div>
                                <div
                                    class="flex flex-wrap items-center gap-2.5"
                                >
                                    <h3
                                        class="text-lg font-bold text-neutral-900 sm:text-xl dark:text-white"
                                    >
                                        {{ job.role }}
                                    </h3>
                                    <span
                                        v-if="job.isCurrent"
                                        class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400"
                                    >
                                        <span
                                            class="h-1.5 w-1.5 rounded-full bg-emerald-500"
                                        />
                                        Present Role
                                    </span>
                                </div>
                                <div class="mt-1 flex items-center gap-2">
                                    <span
                                        class="text-sm font-semibold text-violet-600 dark:text-violet-400"
                                    >
                                        {{ job.company }}
                                    </span>
                                    <span
                                        class="text-xs text-neutral-400 dark:text-neutral-500"
                                        >•</span
                                    >
                                    <span
                                        class="text-xs text-neutral-500 dark:text-neutral-400"
                                    >
                                        {{ job.location }}
                                    </span>
                                </div>
                            </div>

                            <!-- Period / Duration -->
                            <div
                                class="inline-flex items-center gap-1.5 self-start rounded-full bg-neutral-100 px-3 py-1 text-xs font-medium text-neutral-500 sm:self-auto dark:bg-neutral-800 dark:text-neutral-400"
                            >
                                <Icon
                                    name="calendar"
                                    className="w-3.5 h-3.5 text-neutral-400"
                                />
                                <span>{{ job.period }}</span>
                            </div>
                        </div>

                        <!-- Role Description -->
                        <p
                            class="mt-4 text-xs leading-relaxed text-neutral-600 sm:text-sm dark:text-neutral-300"
                        >
                            {{ job.description }}
                        </p>

                        <!-- Highlights List -->
                        <div class="mt-4 space-y-2">
                            <h4
                                class="text-xs font-semibold tracking-wider text-neutral-400 uppercase dark:text-neutral-500"
                            >
                                Key Contributions & Outcomes:
                            </h4>
                            <ul class="space-y-1.5">
                                <li
                                    v-for="(highlight, hIdx) in job.highlights"
                                    :key="hIdx"
                                    class="flex items-start gap-2 text-xs text-neutral-600 sm:text-sm dark:text-neutral-300"
                                >
                                    <span
                                        class="mt-0.5 shrink-0 font-bold text-violet-600 dark:text-violet-400"
                                        >›</span
                                    >
                                    <span>{{ highlight }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Technologies Tags -->
                        <div
                            class="mt-5 flex flex-wrap items-center gap-1.5 border-t border-neutral-100 pt-4 dark:border-neutral-800"
                        >
                            <span
                                class="mr-1 text-[11px] font-medium text-neutral-400 dark:text-neutral-500"
                            >
                                Tech Stack:
                            </span>
                            <span
                                v-for="tech in job.technologies"
                                :key="tech"
                                class="rounded-md border border-neutral-200/50 bg-neutral-100 px-2.5 py-0.5 text-xs font-medium text-neutral-700 dark:border-neutral-700/50 dark:bg-neutral-800 dark:text-neutral-300"
                            >
                                {{ tech }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
