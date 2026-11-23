<script setup lang="ts">
import { computed } from 'vue';
import type { ProjectItem } from '@/types/resume';
import { useTranslations } from '@/composables/useTranslations';
import SectionShell from '@/components/ui/SectionShell.vue';
import TechBadge from '@/components/ui/TechBadge.vue';
import GithubIcon from '@/components/icons/GithubIcon.vue';
import { ArrowUpRight, ExternalLink, Layers } from 'lucide-vue-next';

const props = defineProps<{
    projects: ProjectItem[];
    index?: number;
}>();

const emit = defineEmits<{
    (e: 'select-project', project: ProjectItem): void;
}>();

const { t } = useTranslations();

/** The first featured project is shown as a wide spotlight card. */
const spotlightProject = computed(
    () => props.projects.find((project) => project.featured) ?? null,
);

const orderedProjects = computed(() =>
    spotlightProject.value
        ? [
              spotlightProject.value,
              ...props.projects.filter(
                  (project) => project !== spotlightProject.value,
              ),
          ]
        : props.projects,
);

/**
 * A regular project left alone on the last row of the two-column grid
 * stretches to full width instead of leaving an empty half.
 */
const lonelyLastProject = computed(() => {
    const regularProjects = orderedProjects.value.filter(
        (project) => project !== spotlightProject.value,
    );
    return regularProjects.length % 2 === 1
        ? regularProjects[regularProjects.length - 1]
        : null;
});

const isWide = (project: ProjectItem): boolean =>
    project === spotlightProject.value || project === lonelyLastProject.value;

const MAX_TECH = 5;
</script>

<template>
    <SectionShell
        id="projects"
        :index="index"
        :eyebrow="t('section.portfolio')"
        :title="t('section.portfolio_title')"
        :subtitle="t('section.portfolio_subtitle')"
    >
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <article
                v-for="(project, position) in orderedProjects"
                :key="project.id"
                v-reveal="position"
                v-spotlight
                role="button"
                tabindex="0"
                :aria-label="t('open_project', { title: project.title })"
                @click="emit('select-project', project)"
                @keydown.enter.self="emit('select-project', project)"
                @keydown.space.self.prevent="emit('select-project', project)"
                class="card-glow group relative flex cursor-pointer flex-col overflow-hidden rounded-3xl border border-zinc-300 bg-white shadow-card transition-all duration-300 hover:shadow-card-hover focus-visible:outline-none dark:border-white/10 dark:bg-zinc-900"
                :class="{ 'md:col-span-2 lg:flex-row': isWide(project) }"
            >
                <!-- Cover -->
                <div
                    class="relative z-[2] aspect-video w-full shrink-0 overflow-hidden border-zinc-300 bg-zinc-100 dark:border-white/10 dark:bg-zinc-800"
                    :class="
                        isWide(project)
                            ? 'border-b lg:aspect-auto lg:w-3/5 lg:border-r lg:border-b-0'
                            : 'border-b'
                    "
                >
                    <img
                        v-if="project.coverImageUrl"
                        :src="project.coverImageUrl"
                        :alt="project.title"
                        loading="lazy"
                        class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                    />
                    <div
                        v-else
                        class="flex h-full min-h-48 w-full flex-col items-center justify-center bg-linear-to-br from-zinc-100 to-zinc-200/70 dark:from-zinc-800 dark:to-zinc-900"
                    >
                        <Layers
                            class="h-10 w-10 stroke-[1.3] text-zinc-500 transition-transform duration-500 group-hover:scale-110 dark:text-zinc-500"
                        />
                        <span
                            v-if="project.category"
                            class="mt-3 text-xs font-semibold tracking-[0.2em] text-zinc-600 uppercase"
                        >
                            {{ project.category }}
                        </span>
                    </div>

                    <span
                        v-if="project.featured"
                        class="absolute top-4 left-4 rounded-full bg-lavender-600 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm dark:bg-lavender-300 dark:text-lavender-950"
                    >
                        {{ t('featured') }}
                    </span>
                </div>

                <!-- Body -->
                <div class="relative z-[2] flex flex-1 flex-col p-6 sm:p-7">
                    <span
                        v-if="project.category"
                        class="text-xs font-semibold tracking-wider text-lavender-700 uppercase dark:text-lavender-300"
                    >
                        {{ project.category }}
                    </span>

                    <h3
                        class="mt-2 text-xl font-bold tracking-tight text-zinc-950 sm:text-2xl dark:text-white"
                    >
                        {{ project.title }}
                    </h3>
                    <p
                        v-if="project.tagline"
                        class="mt-1 text-sm font-medium text-zinc-700 dark:text-zinc-400"
                    >
                        {{ project.tagline }}
                    </p>

                    <p
                        v-if="project.description"
                        class="mt-4 line-clamp-3 text-sm leading-relaxed text-zinc-700 dark:text-zinc-400"
                    >
                        {{ project.description }}
                    </p>

                    <ul
                        v-if="project.highlights?.length"
                        class="mt-4 space-y-1.5"
                    >
                        <li
                            v-for="(highlight, i) in project.highlights.slice(
                                0,
                                project === spotlightProject ? 3 : 2,
                            )"
                            :key="i"
                            class="flex items-start gap-2 text-sm text-zinc-700 dark:text-zinc-300"
                        >
                            <span
                                class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-brand"
                            />
                            <span class="line-clamp-1">{{ highlight }}</span>
                        </li>
                    </ul>

                    <div class="mt-auto pt-6">
                        <div
                            v-if="project.technologies?.length"
                            class="mb-5 flex flex-wrap gap-1.5"
                        >
                            <TechBadge
                                v-for="tech in project.technologies.slice(
                                    0,
                                    MAX_TECH,
                                )"
                                :key="tech"
                            >
                                {{ tech }}
                            </TechBadge>
                            <span
                                v-if="project.technologies.length > MAX_TECH"
                                class="px-1 py-0.5 text-[11px] font-medium text-zinc-500"
                            >
                                +{{ project.technologies.length - MAX_TECH }}
                            </span>
                        </div>

                        <div
                            class="flex items-center justify-between gap-2 border-t border-zinc-300 pt-4 dark:border-white/5"
                        >
                            <span
                                class="inline-flex items-center gap-1 text-sm font-semibold text-zinc-900 dark:text-white"
                            >
                                {{ t('show_details') }}
                                <ArrowUpRight
                                    class="h-4 w-4 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
                                />
                            </span>

                            <div class="flex items-center gap-1.5">
                                <a
                                    v-if="project.liveUrl"
                                    :href="project.liveUrl"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    @click.stop
                                    class="btn btn-primary px-3.5 py-1.5 text-xs"
                                >
                                    <ExternalLink class="h-3.5 w-3.5" />
                                    <span>{{ t('live_demo') }}</span>
                                </a>
                                <a
                                    v-if="project.githubUrl"
                                    :href="project.githubUrl"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    :title="t('source_code')"
                                    :aria-label="t('source_code')"
                                    @click.stop
                                    class="rounded-lg p-2 text-zinc-600 transition-colors hover:bg-zinc-100 hover:text-zinc-950 dark:text-zinc-400 dark:hover:bg-white/5 dark:hover:text-white"
                                >
                                    <GithubIcon class="h-4 w-4" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </SectionShell>
</template>
