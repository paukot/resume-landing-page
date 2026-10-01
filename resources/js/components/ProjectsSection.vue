<script setup lang="ts">
import { ref, computed } from 'vue';
import type { ProjectItem } from '../types/resume';
import Icon from './Icon.vue';

const props = defineProps<{
    projects: ProjectItem[];
}>();

const selectedCategory = ref<string>('All');

const categories = computed(() => {
    const set = new Set(props.projects.map((p) => p.category));
    return ['All', ...Array.from(set)];
});

const filteredProjects = computed(() => {
    if (selectedCategory.value === 'All') {
        return props.projects;
    }
    return props.projects.filter((p) => p.category === selectedCategory.value);
});
</script>

<template>
    <section
        id="projects"
        class="relative border-t border-neutral-200/60 py-20 dark:border-neutral-800/60"
    >
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div
                class="mb-10 flex flex-col justify-between gap-4 sm:flex-row sm:items-end"
            >
                <div class="flex items-center gap-3">
                    <span
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400"
                    >
                        <Icon name="layers" className="w-4 h-4" />
                    </span>
                    <div>
                        <h2
                            class="text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl dark:text-white"
                        >
                            Personal Projects
                        </h2>
                        <p
                            class="text-xs text-neutral-500 sm:text-sm dark:text-neutral-400"
                        >
                            Independent open-source tools, full-stack
                            applications, and technical experiments
                        </p>
                    </div>
                </div>

                <!-- Category Filter Buttons -->
                <div
                    class="flex flex-wrap gap-1.5 self-start rounded-xl bg-neutral-100 p-1 sm:self-auto dark:bg-neutral-800/70"
                >
                    <button
                        v-for="cat in categories"
                        :key="cat"
                        type="button"
                        @click="selectedCategory = cat"
                        class="cursor-pointer rounded-lg px-3 py-1.5 text-xs font-medium transition-all"
                        :class="[
                            selectedCategory === cat
                                ? 'bg-white font-semibold text-neutral-950 shadow-xs dark:bg-neutral-900 dark:text-white'
                                : 'text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white',
                        ]"
                    >
                        {{ cat }}
                    </button>
                </div>
            </div>

            <!-- Projects Grid -->
            <div class="grid grid-cols-1 gap-7 md:grid-cols-2">
                <div
                    v-for="project in filteredProjects"
                    :key="project.id"
                    class="group flex flex-col justify-between overflow-hidden rounded-2xl border border-neutral-200/80 bg-white shadow-xs transition-all hover:border-neutral-300 hover:shadow-lg dark:border-neutral-800/80 dark:bg-neutral-900 dark:hover:border-neutral-700"
                >
                    <div>
                        <!-- Mockup / Preview Header -->
                        <div
                            class="relative flex h-44 w-full items-center justify-center overflow-hidden bg-gradient-to-br p-5 sm:h-48"
                            :class="project.gradient"
                        >
                            <!-- Decorative Grid Texture -->
                            <div
                                class="bg-grid-pattern absolute inset-0 opacity-20"
                            />

                            <!-- Stylized Browser Window Preview -->
                            <div
                                class="relative w-full max-w-sm transform rounded-lg border border-white/10 bg-neutral-950/85 p-3 shadow-xl backdrop-blur-md transition-transform group-hover:scale-[1.02]"
                            >
                                <div class="mb-2.5 flex items-center gap-1.5">
                                    <div
                                        class="h-2.5 w-2.5 rounded-full bg-rose-500/80"
                                    />
                                    <div
                                        class="h-2.5 w-2.5 rounded-full bg-amber-500/80"
                                    />
                                    <div
                                        class="h-2.5 w-2.5 rounded-full bg-emerald-500/80"
                                    />
                                    <div
                                        class="ml-2 flex h-3 flex-1 items-center rounded bg-white/10 px-2 font-mono text-[9px] text-neutral-400"
                                    >
                                        {{
                                            project.title
                                                .toLowerCase()
                                                .replace(/\s+/g, '-')
                                        }}.internal
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <div
                                        class="h-2 w-3/4 rounded bg-white/20"
                                    />
                                    <div
                                        class="h-2 w-1/2 rounded bg-white/10"
                                    />
                                </div>
                            </div>

                            <!-- Featured & Category Badges -->
                            <div
                                class="absolute top-3 left-3 flex items-center gap-2"
                            >
                                <span
                                    v-if="project.featured"
                                    class="inline-flex items-center gap-1 rounded-full bg-white/90 px-2.5 py-0.5 text-[10px] font-bold tracking-wider text-neutral-950 uppercase shadow-xs backdrop-blur-sm"
                                >
                                    <Icon
                                        name="sparkles"
                                        className="w-3 h-3 text-amber-500"
                                    />
                                    Featured
                                </span>
                                <span
                                    class="rounded-full border border-white/10 bg-black/40 px-2.5 py-0.5 text-[10px] font-medium text-white backdrop-blur-sm"
                                >
                                    {{ project.category }}
                                </span>
                            </div>

                            <!-- Metric Badge (Stars / Downloads) -->
                            <div
                                v-if="project.stats"
                                class="absolute right-3 bottom-3 inline-flex items-center gap-1 rounded-md border border-white/10 bg-neutral-900/90 px-2.5 py-1 text-xs font-semibold text-white backdrop-blur-sm"
                            >
                                <Icon
                                    name="star"
                                    className="w-3 h-3 text-amber-400"
                                />
                                <span>{{ project.stats.value }}</span>
                            </div>
                        </div>

                        <!-- Project Body -->
                        <div class="p-6">
                            <h3
                                class="text-xl font-bold text-neutral-900 transition-colors group-hover:text-violet-600 dark:text-white dark:group-hover:text-violet-400"
                            >
                                {{ project.title }}
                            </h3>
                            <p
                                class="mt-1 text-xs font-medium text-violet-600 dark:text-violet-400"
                            >
                                {{ project.tagline }}
                            </p>
                            <p
                                class="mt-3 text-xs leading-relaxed text-neutral-600 sm:text-sm dark:text-neutral-300"
                            >
                                {{ project.description }}
                            </p>

                            <!-- Highlights -->
                            <div class="mt-4 space-y-1.5">
                                <div
                                    v-for="(hl, i) in project.highlights"
                                    :key="i"
                                    class="flex items-start gap-2 text-xs text-neutral-600 dark:text-neutral-400"
                                >
                                    <span
                                        class="mt-0.5 shrink-0 font-bold text-violet-500"
                                        >•</span
                                    >
                                    <span>{{ hl }}</span>
                                </div>
                            </div>

                            <!-- Technologies Stack -->
                            <div class="mt-5 flex flex-wrap gap-1.5">
                                <span
                                    v-for="tech in project.technologies"
                                    :key="tech"
                                    class="rounded-md bg-neutral-100 px-2 py-0.5 text-[11px] font-medium text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300"
                                >
                                    {{ tech }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Links -->
                    <div
                        class="mt-4 flex items-center justify-between gap-3 border-t border-neutral-100 p-6 pt-0 dark:border-neutral-800/80"
                    >
                        <a
                            v-if="project.liveUrl"
                            :href="project.liveUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-neutral-900 transition-colors hover:text-violet-600 dark:text-white dark:hover:text-violet-400"
                        >
                            <span>Live Preview</span>
                            <Icon
                                name="external-link"
                                className="w-3.5 h-3.5"
                            />
                        </a>

                        <a
                            v-if="project.githubUrl"
                            :href="project.githubUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-neutral-600 transition-colors hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white"
                        >
                            <Icon name="github" className="w-3.5 h-3.5" />
                            <span>Source Code</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
