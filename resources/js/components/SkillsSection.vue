<script setup lang="ts">
import type { SkillCategory } from '@/types/resume';
import Icon from './icons/Icon.vue';
import { useTranslations } from '@/composables/useTranslations';

defineProps<{
    categories: SkillCategory[];
}>();

const { t } = useTranslations();
</script>

<template>
    <section
        id="skills"
        class="border-b-2 border-neutral-200/80 py-16 dark:border-neutral-800/80"
    >
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div
                class="mb-8 text-xs font-semibold tracking-wider text-neutral-500 uppercase dark:text-neutral-400"
            >
                <span>{{ t('section.skills_and_tools') }}</span>
            </div>

            <!-- Simple, structured 2-column or 4-group grid without fake percentages -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div
                    v-for="cat in categories"
                    :key="cat.id"
                    class="rounded-xl border border-neutral-200/80 bg-neutral-50 p-5 shadow-xs shadow-neutral-300 dark:border-neutral-800/80 dark:bg-neutral-900 dark:shadow-neutral-700"
                >
                    <div class="mb-3 flex items-center gap-2">
                        <Icon
                            :name="cat.icon"
                            class="h-4 w-4 text-neutral-500 dark:text-neutral-400"
                        />
                        <h3
                            class="text-xs font-bold tracking-wider text-neutral-800 uppercase dark:text-neutral-200"
                        >
                            {{ cat.name }}
                        </h3>
                    </div>

                    <div class="flex flex-wrap gap-1.5">
                        <span
                            v-for="skill in cat.skills"
                            :key="skill"
                            class="rounded-md border border-neutral-200/80 bg-white px-2.5 py-1 text-xs font-medium text-neutral-800 shadow-xs shadow-neutral-300 transition-colors hover:border-neutral-400 dark:border-neutral-700/80 dark:bg-neutral-800 dark:text-neutral-200 dark:shadow-neutral-700 dark:hover:border-neutral-600"
                        >
                            {{ skill }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
