<script setup lang="ts">
import type { EducationItem } from '@/types/resume';
import { useTranslations } from '@/composables/useTranslations';
import SectionShell from '@/components/ui/SectionShell.vue';
import { ArrowUpRight, GraduationCap } from 'lucide-vue-next';

defineProps<{
    education: EducationItem[];
    index?: number;
}>();

const { t } = useTranslations();

const details = (edu: EducationItem): string =>
    [edu.field, edu.location].filter(Boolean).join(' · ');
</script>

<template>
    <SectionShell
        id="education"
        :index="index"
        :eyebrow="t('section.education')"
        :title="t('section.education_title')"
    >
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div
                v-for="(edu, position) in education"
                :key="edu.id"
                v-reveal="position"
                class="card-glow flex gap-4 rounded-3xl border border-zinc-300 bg-white p-6 shadow-card dark:border-white/10 dark:bg-zinc-900"
            >
                <span
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-zinc-100 text-zinc-700 dark:bg-white/5 dark:text-zinc-200"
                >
                    <GraduationCap class="h-5 w-5" />
                </span>
                <div class="min-w-0 flex-1">
                    <time
                        v-if="edu.period"
                        class="text-xs font-semibold text-zinc-600 tabular-nums dark:text-zinc-400"
                    >
                        {{ edu.period }}
                    </time>
                    <h3
                        class="mt-1 text-base font-bold tracking-tight text-zinc-950 sm:text-lg dark:text-white"
                    >
                        <a
                            v-if="edu.institutionUrl"
                            :href="edu.institutionUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-0.5 transition-colors hover:text-lavender-700 dark:hover:text-lavender-300"
                        >
                            {{ edu.institution }}
                            <ArrowUpRight class="h-4 w-4" />
                        </a>
                        <template v-else>{{ edu.institution }}</template>
                    </h3>
                    <div
                        v-if="edu.degree"
                        class="mt-0.5 text-sm font-medium text-zinc-700 dark:text-zinc-300"
                    >
                        {{ edu.degree }}
                    </div>
                    <div
                        v-if="details(edu)"
                        class="mt-1 text-xs text-zinc-600 dark:text-zinc-400"
                    >
                        {{ details(edu) }}
                    </div>
                </div>
            </div>
        </div>
    </SectionShell>
</template>
