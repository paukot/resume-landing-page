<script setup lang="ts">
import { computed } from 'vue';
import type { SkillCategory } from '@/types/resume';
import { useTranslations } from '@/composables/useTranslations';
import SectionShell from '@/components/ui/SectionShell.vue';
import TechBadge from '@/components/ui/TechBadge.vue';
import Icon from '@/components/icons/Icon.vue';

const props = defineProps<{
    categories: SkillCategory[];
    index?: number;
}>();

const { t } = useTranslations();

const nonEmptyCategories = computed(() =>
    props.categories.filter((category) => category.skills?.length > 0),
);
</script>

<template>
    <SectionShell
        id="skills"
        :index="index"
        :eyebrow="t('section.skills_and_tools')"
        :title="t('section.skills_title')"
    >
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="(category, position) in nonEmptyCategories"
                :key="category.id"
                v-reveal="position"
                v-spotlight
                class="card-glow relative overflow-hidden rounded-3xl border border-zinc-300 bg-white p-6 shadow-card dark:border-white/10 dark:bg-zinc-900"
            >
                <div class="relative z-[2]">
                    <div class="mb-4 flex items-center gap-3">
                        <span
                            class="flex h-10 w-10 items-center justify-center rounded-2xl bg-lavender-100 text-lavender-700 dark:bg-lavender-400/10 dark:text-lavender-300"
                        >
                            <Icon :name="category.icon" class="h-5 w-5" />
                        </span>
                        <h3
                            class="text-base font-bold tracking-tight text-zinc-950 dark:text-white"
                        >
                            {{ category.name }}
                        </h3>
                    </div>

                    <div class="flex flex-wrap gap-1.5">
                        <TechBadge
                            v-for="skill in category.skills"
                            :key="skill"
                            size="md"
                        >
                            {{ skill }}
                        </TechBadge>
                    </div>
                </div>
            </div>
        </div>
    </SectionShell>
</template>
