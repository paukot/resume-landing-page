<script setup lang="ts">
import { computed } from 'vue';
import type { SkillCategory } from '@/types/resume';

const props = defineProps<{
    categories: SkillCategory[];
}>();

const skills = computed(() => [
    ...new Set(props.categories.flatMap((category) => category.skills ?? [])),
]);
</script>

<template>
    <div
        v-if="skills.length"
        class="marquee relative overflow-hidden border-y border-zinc-300 bg-white py-4 dark:border-white/5 dark:bg-zinc-900/40"
        aria-hidden="true"
    >
        <div class="marquee-track flex w-max gap-10">
            <template v-for="copy in 2" :key="copy">
                <span
                    v-for="skill in skills"
                    :key="`${copy}-${skill}`"
                    class="flex items-center gap-10 text-sm font-semibold tracking-wide whitespace-nowrap text-zinc-600 dark:text-zinc-400"
                >
                    {{ skill }}
                    <span class="h-1.5 w-1.5 rounded-full bg-brand" />
                </span>
            </template>
        </div>
    </div>
</template>
