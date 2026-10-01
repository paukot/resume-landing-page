<script setup lang="ts">
import { ref, computed } from 'vue';
import type { SkillCategory } from '../types/resume';
import Icon from './Icon.vue';

const props = defineProps<{
    categories: SkillCategory[];
}>();

const activeTab = ref<string>('all');

const tabs = computed(() => [
    { id: 'all', name: 'All Skills' },
    ...props.categories.map((c) => ({ id: c.id, name: c.name })),
]);

const displayedCategories = computed(() => {
    if (activeTab.value === 'all') {
        return props.categories;
    }
    return props.categories.filter((c) => c.id === activeTab.value);
});
</script>

<template>
    <section
        id="skills"
        class="relative border-t border-neutral-200/60 py-20 dark:border-neutral-800/60"
    >
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div
                class="mb-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-end"
            >
                <div class="flex items-center gap-3">
                    <span
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-cyan-100 text-cyan-700 dark:bg-cyan-950/60 dark:text-cyan-400"
                    >
                        <Icon name="code" className="w-4 h-4" />
                    </span>
                    <div>
                        <h2
                            class="text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl dark:text-white"
                        >
                            Skills & Expertise
                        </h2>
                        <p
                            class="text-xs text-neutral-500 sm:text-sm dark:text-neutral-400"
                        >
                            Technical competencies, frameworks, and engineering
                            methodologies
                        </p>
                    </div>
                </div>

                <!-- Category Filter Tabs -->
                <div
                    class="flex flex-wrap gap-1.5 self-start rounded-xl bg-neutral-100 p-1 sm:self-auto dark:bg-neutral-800/70"
                >
                    <button
                        v-for="tab in tabs"
                        :key="tab.id"
                        type="button"
                        @click="activeTab = tab.id"
                        class="cursor-pointer rounded-lg px-3 py-1.5 text-xs font-medium transition-all"
                        :class="[
                            activeTab === tab.id
                                ? 'bg-white font-semibold text-neutral-950 shadow-xs dark:bg-neutral-900 dark:text-white'
                                : 'text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white',
                        ]"
                    >
                        {{ tab.name }}
                    </button>
                </div>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div
                    v-for="cat in displayedCategories"
                    :key="cat.id"
                    class="flex flex-col justify-between rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-xs sm:p-7 dark:border-neutral-800/80 dark:bg-neutral-900"
                >
                    <div>
                        <!-- Category Header -->
                        <div class="mb-3 flex items-center gap-3">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-950/40 dark:text-violet-400"
                            >
                                <Icon :name="cat.icon" className="w-4 h-4" />
                            </div>
                            <div>
                                <h3
                                    class="text-base font-bold text-neutral-900 dark:text-white"
                                >
                                    {{ cat.name }}
                                </h3>
                                <p
                                    class="text-xs text-neutral-500 dark:text-neutral-400"
                                >
                                    {{ cat.description }}
                                </p>
                            </div>
                        </div>

                        <!-- Skills List with Progress Bars -->
                        <div class="mt-6 space-y-4">
                            <div
                                v-for="skill in cat.skills"
                                :key="skill.name"
                                class="space-y-1.5"
                            >
                                <div
                                    class="flex items-center justify-between text-xs"
                                >
                                    <span
                                        class="flex items-center gap-1.5 font-semibold text-neutral-800 dark:text-neutral-200"
                                    >
                                        {{ skill.name }}
                                        <span
                                            v-if="skill.highlight"
                                            class="inline-block h-1.5 w-1.5 rounded-full bg-violet-500"
                                            title="Core Specialization"
                                        />
                                    </span>
                                    <span
                                        class="rounded px-2 py-0.5 text-[10px] font-semibold"
                                        :class="[
                                            skill.level === 'Expert'
                                                ? 'bg-violet-100 text-violet-700 dark:bg-violet-950/60 dark:text-violet-300'
                                                : skill.level === 'Advanced'
                                                  ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300'
                                                  : 'bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300',
                                        ]"
                                    >
                                        {{ skill.level }}
                                    </span>
                                </div>
                                <!-- Progress Bar Track -->
                                <div
                                    class="h-1.5 w-full overflow-hidden rounded-full bg-neutral-100 dark:bg-neutral-800"
                                >
                                    <div
                                        class="h-full rounded-full transition-all duration-700"
                                        :class="[
                                            skill.highlight
                                                ? 'bg-gradient-to-r from-violet-600 to-indigo-500'
                                                : 'bg-neutral-400 dark:bg-neutral-600',
                                        ]"
                                        :style="{
                                            width: `${skill.percentage}%`,
                                        }"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
