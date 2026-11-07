<script setup lang="ts">
import type { ProjectItem } from '@/types/resume';
import { useTranslations } from '@/composables/useTranslations';
import GithubIcon from '@/components/icons/GithubIcon.vue';
import { ExternalLink, ArrowUpRight, Layers } from 'lucide-vue-next';

defineProps<{
    projects: ProjectItem[];
}>();

const emit = defineEmits<{
    (e: 'select-project', project: ProjectItem): void;
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
                class="mb-8 text-xs font-semibold tracking-wider text-neutral-500 uppercase dark:text-neutral-400"
            >
                <span>{{ t('section.featured_projects') }}</span>
            </div>

            <!-- 2 Projects Per Column Responsive Grid -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <article
                    v-for="project in projects"
                    :key="project.id"
                    class="group flex flex-col justify-between overflow-hidden rounded-xl border border-neutral-200/80 bg-neutral-50 shadow-xs shadow-neutral-300 transition-all duration-200 hover:-translate-y-0.5 hover:border-neutral-400 dark:border-neutral-800/80 dark:bg-neutral-900 dark:shadow-neutral-700 dark:hover:border-neutral-600"
                >
                    <!-- Visual Cover / Thumbnail -->
                    <div
                        role="button"
                        tabindex="0"
                        @click="emit('select-project', project)"
                        @keydown.enter="emit('select-project', project)"
                        @keydown.space.prevent="emit('select-project', project)"
                        :aria-label="project.title"
                        class="relative aspect-video w-full cursor-pointer overflow-hidden bg-neutral-200 focus:ring-2 focus:ring-neutral-400 focus:outline-hidden dark:bg-neutral-800 dark:focus:ring-neutral-600"
                    >
                        <img
                            v-if="project.coverImageUrl"
                            :src="project.coverImageUrl"
                            :alt="project.title"
                            loading="lazy"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                        />
                        <!-- Subtle Pattern Fallback if no cover image -->
                        <div
                            v-else
                            class="flex h-full w-full flex-col items-center justify-center bg-gradient-to-br from-neutral-100 to-neutral-200/60 p-4 text-neutral-400 dark:from-neutral-900 dark:to-neutral-800 dark:text-neutral-500"
                        >
                            <Layers
                                class="h-8 w-8 stroke-[1.2] opacity-70 transition-transform duration-300 group-hover:scale-110"
                            />
                            <span
                                class="mt-2 font-mono text-[11px] tracking-wide uppercase opacity-75"
                            >
                                {{ project.category }}
                            </span>
                        </div>

                        <!-- Top-Right Badges Overlay -->
                        <div
                            class="absolute top-3 right-3 flex items-center gap-1.5"
                        >
                            <span
                                v-if="project.featured"
                                class="rounded-sm bg-neutral-900/80 px-2 py-0.5 text-[10px] font-semibold tracking-wider text-white uppercase backdrop-blur-xs dark:bg-white/90 dark:text-neutral-950"
                            >
                                {{ t('featured') }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="flex flex-1 flex-col p-5 sm:p-6">
                        <!-- Category Tag -->
                        <div class="mb-1.5 flex items-center justify-between">
                            <span
                                class="font-mono text-[11px] font-medium text-neutral-500 dark:text-neutral-400"
                            >
                                {{ project.category }}
                            </span>
                        </div>

                        <!-- Title & Tagline -->
                        <h3
                            role="button"
                            tabindex="0"
                            @click="emit('select-project', project)"
                            @keydown.enter="emit('select-project', project)"
                            @keydown.space.prevent="
                                emit('select-project', project)
                            "
                            class="cursor-pointer text-lg font-bold tracking-tight text-neutral-950 transition-colors hover:text-neutral-700 focus:underline focus:outline-hidden dark:text-white dark:hover:text-neutral-200"
                        >
                            {{ project.title }}
                        </h3>
                        <p
                            class="mt-1 text-xs font-medium text-neutral-600 dark:text-neutral-400"
                        >
                            {{ project.tagline }}
                        </p>

                        <!-- Short Description -->
                        <p
                            class="mt-3 line-clamp-3 text-xs leading-relaxed text-neutral-600 dark:text-neutral-400"
                        >
                            {{ project.description }}
                        </p>

                        <!-- Highlights Preview (first 2) -->
                        <ul
                            v-if="
                                project.highlights &&
                                project.highlights.length > 0
                            "
                            class="mt-3 space-y-1"
                        >
                            <li
                                v-for="(hl, i) in project.highlights.slice(
                                    0,
                                    2,
                                )"
                                :key="i"
                                class="flex items-start gap-1.5 text-[11px] text-neutral-600 dark:text-neutral-400"
                            >
                                <span class="shrink-0 text-neutral-400">•</span>
                                <span class="line-clamp-1">{{ hl }}</span>
                            </li>
                        </ul>

                        <!-- Push footer to bottom -->
                        <div class="mt-auto pt-5">
                            <!-- Technologies Badges -->
                            <div class="mb-4 flex flex-wrap gap-1">
                                <span
                                    v-for="tech in project.technologies.slice(
                                        0,
                                        4,
                                    )"
                                    :key="tech"
                                    class="rounded border border-neutral-200 bg-white px-1.5 py-0.5 font-mono text-[10px] text-neutral-700 shadow-2xs dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300"
                                >
                                    {{ tech }}
                                </span>
                                <span
                                    v-if="project.technologies.length > 4"
                                    class="rounded border border-transparent px-1 py-0.5 font-mono text-[10px] text-neutral-400 dark:text-neutral-500"
                                >
                                    +{{ project.technologies.length - 4 }}
                                </span>
                            </div>

                            <!-- Card Action Toolbar -->
                            <div
                                class="flex items-center justify-between gap-2 border-t border-neutral-200/80 pt-3 dark:border-neutral-800/80"
                            >
                                <button
                                    type="button"
                                    @click="emit('select-project', project)"
                                    class="inline-flex cursor-pointer items-center gap-1 text-xs font-semibold text-neutral-900 transition-colors hover:text-neutral-600 dark:text-white dark:hover:text-neutral-300"
                                >
                                    <span>{{ t('show_details') }}</span>
                                    <ArrowUpRight
                                        class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
                                    />
                                </button>

                                <div class="flex items-center gap-3">
                                    <a
                                        v-if="project.liveUrl"
                                        :href="project.liveUrl"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        title="Live Demo"
                                        class="inline-flex items-center gap-1 text-xs text-neutral-600 transition-colors hover:text-neutral-950 dark:text-neutral-400 dark:hover:text-white"
                                    >
                                        <ExternalLink class="h-3.5 w-3.5" />
                                    </a>

                                    <a
                                        v-if="project.githubUrl"
                                        :href="project.githubUrl"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        title="View Source Code"
                                        class="inline-flex items-center gap-1 text-xs text-neutral-600 transition-colors hover:text-neutral-950 dark:text-neutral-400 dark:hover:text-white"
                                    >
                                        <GithubIcon class="h-3.5 w-3.5" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>
