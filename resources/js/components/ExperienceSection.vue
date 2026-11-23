<script setup lang="ts">
import type { ExperienceItem } from '@/types/resume';
import { useTranslations } from '@/composables/useTranslations';
import SectionShell from '@/components/ui/SectionShell.vue';
import TechBadge from '@/components/ui/TechBadge.vue';
import { ArrowUpRight, MapPin } from 'lucide-vue-next';

defineProps<{
    experience: ExperienceItem[];
    index?: number;
}>();

const { t } = useTranslations();
</script>

<template>
    <SectionShell
        id="experience"
        :index="index"
        :eyebrow="t('section.work_experience')"
        :title="t('section.experience_title')"
    >
        <ol class="relative">
            <span
                class="absolute top-2 bottom-2 left-[7px] w-px bg-zinc-300 sm:left-[11px] dark:bg-white/10"
                aria-hidden="true"
            />

            <li
                v-for="(job, position) in experience"
                :key="job.id"
                v-reveal="position"
                class="group relative pb-10 pl-8 last:pb-0 sm:pl-12"
            >
                <span
                    class="absolute top-6 left-0 flex h-[15px] w-[15px] items-center justify-center sm:h-6 sm:w-6"
                    aria-hidden="true"
                >
                    <span
                        class="h-full w-full rounded-full border-2 bg-zinc-50 transition-colors dark:bg-zinc-950"
                        :class="
                            job.isCurrent
                                ? 'border-emerald-500'
                                : 'border-zinc-300 group-hover:border-lavender-500 dark:border-zinc-700'
                        "
                    />
                </span>

                <div
                    class="card-glow rounded-3xl border border-zinc-300 bg-white p-6 shadow-card transition-shadow hover:shadow-card-hover sm:p-7 dark:border-white/10 dark:bg-zinc-900"
                >
                    <div
                        class="flex flex-col justify-between gap-2 sm:flex-row sm:items-start"
                    >
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3
                                    class="text-lg font-bold tracking-tight text-zinc-950 sm:text-xl dark:text-white"
                                >
                                    {{ job.role }}
                                </h3>
                                <span
                                    v-if="job.isCurrent"
                                    class="rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700 ring-1 ring-emerald-500/30 dark:text-emerald-300"
                                >
                                    {{ t('current') }}
                                </span>
                            </div>
                            <div
                                class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm"
                            >
                                <a
                                    v-if="job.companyUrl"
                                    :href="job.companyUrl"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-0.5 font-semibold text-zinc-800 transition-colors hover:text-lavender-700 dark:text-zinc-200 dark:hover:text-lavender-300"
                                >
                                    {{ job.company }}
                                    <ArrowUpRight class="h-3.5 w-3.5" />
                                </a>
                                <span
                                    v-else
                                    class="font-semibold text-zinc-800 dark:text-zinc-200"
                                >
                                    {{ job.company }}
                                </span>
                                <span
                                    v-if="job.location"
                                    class="inline-flex items-center gap-1 text-zinc-600 dark:text-zinc-400"
                                >
                                    <MapPin class="h-3.5 w-3.5" />
                                    {{ job.location }}
                                </span>
                            </div>
                        </div>
                        <time
                            v-if="job.period"
                            class="shrink-0 rounded-full border border-zinc-300 px-3 py-1 text-xs font-semibold text-zinc-700 tabular-nums dark:border-white/10 dark:text-zinc-400"
                        >
                            {{ job.period }}
                        </time>
                    </div>

                    <p
                        v-if="job.description"
                        class="mt-4 text-sm leading-relaxed text-zinc-700 dark:text-zinc-400"
                    >
                        {{ job.description }}
                    </p>

                    <ul v-if="job.highlights?.length" class="mt-4 space-y-2">
                        <li
                            v-for="(highlight, i) in job.highlights"
                            :key="i"
                            class="flex items-start gap-2.5 text-sm leading-relaxed text-zinc-700 dark:text-zinc-300"
                        >
                            <span
                                class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-brand"
                            />
                            <span>{{ highlight }}</span>
                        </li>
                    </ul>

                    <div
                        v-if="job.technologies?.length"
                        class="mt-5 flex flex-wrap gap-1.5"
                    >
                        <TechBadge v-for="tech in job.technologies" :key="tech">
                            {{ tech }}
                        </TechBadge>
                    </div>
                </div>
            </li>
        </ol>
    </SectionShell>
</template>
