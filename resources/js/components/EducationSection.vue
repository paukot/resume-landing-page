<script setup lang="ts">
import type { EducationItem } from '@/types/resume';

defineProps<{
    education: EducationItem[];
}>();
</script>

<template>
    <section
        id="education"
        class="border-b-2 border-neutral-200/80 py-16 dark:border-neutral-800/80"
    >
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div
                class="mb-8 flex items-center gap-2 text-xs font-semibold tracking-wider text-neutral-500 uppercase dark:text-neutral-400"
            >
                <span>04</span>
                <span>/</span>
                <span>Education</span>
            </div>

            <div class="space-y-4">
                <div
                    v-for="edu in education"
                    :key="edu.id"
                    class="rounded-xl border border-neutral-200/80 bg-neutral-50 p-6 dark:border-neutral-800/80 dark:bg-neutral-900"
                >
                    <div
                        class="flex flex-col justify-between gap-1 sm:flex-row sm:items-baseline"
                    >
                        <div>
                            <h3
                                class="text-base font-bold text-neutral-950 sm:text-lg dark:text-white"
                            >
                                {{ edu.institution }}
                            </h3>
                            <div
                                class="mt-0.5 text-sm font-medium text-neutral-700 dark:text-neutral-300"
                            >
                                {{ edu.degree }}
                            </div>
                            <div
                                v-if="edu.field"
                                class="mt-0.5 text-xs text-neutral-500 dark:text-neutral-400"
                            >
                                {{ edu.field }} • {{ edu.location }}
                            </div>
                        </div>
                        <div
                            class="mt-1 font-mono text-xs text-neutral-500 sm:mt-0 dark:text-neutral-400"
                        >
                            {{ edu.period }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
