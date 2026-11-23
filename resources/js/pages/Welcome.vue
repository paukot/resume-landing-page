<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref, type Component } from 'vue';
import { Head } from '@inertiajs/vue3';
import type { ProjectItem, ResumeData } from '@/types/resume';
import { useScrollLock } from '@/composables/useScrollLock';
import { useTheme } from '@/composables/useTheme';
import { useTranslations } from '@/composables/useTranslations';
import { visibleSections, type SectionId } from '@/lib/sections';
import Navbar from '@/components/Navbar.vue';
import HeroSection from '@/components/HeroSection.vue';
import SkillsMarquee from '@/components/SkillsMarquee.vue';
import AboutSection from '@/components/AboutSection.vue';
import ProjectsSection from '@/components/ProjectsSection.vue';
import ExperienceSection from '@/components/ExperienceSection.vue';
import SkillsSection from '@/components/SkillsSection.vue';
import EducationSection from '@/components/EducationSection.vue';
import ContactSection from '@/components/ContactSection.vue';
import ProjectDetailModal from '@/components/ProjectDetailModal.vue';
import { Check } from 'lucide-vue-next';

const props = defineProps<{
    resume: ResumeData;
}>();

const data = computed(() => props.resume);
const sections = computed(() => visibleSections(data.value));

const { t } = useTranslations();
const { isDark, toggleTheme } = useTheme();

// Toast notification
const toastMessage = ref<string | null>(null);
let toastTimer: ReturnType<typeof setTimeout> | null = null;

const showCopiedToast = (value: string) => {
    toastMessage.value = t('copied_with_value', { value });
    if (toastTimer) {
        clearTimeout(toastTimer);
    }
    toastTimer = setTimeout(() => {
        toastMessage.value = null;
    }, 2500);
};

onUnmounted(() => {
    if (toastTimer) {
        clearTimeout(toastTimer);
    }
});

// Project detail modal (deep-linkable through ?project=<id>)
const selectedProject = ref<ProjectItem | null>(null);
useScrollLock(computed(() => selectedProject.value !== null));

const setProjectQuery = (projectId: number | null) => {
    const url = new URL(window.location.href);
    if (projectId === null) {
        url.searchParams.delete('project');
    } else {
        url.searchParams.set('project', String(projectId));
    }
    window.history.replaceState(window.history.state, '', url);
};

const openProjectModal = (project: ProjectItem) => {
    selectedProject.value = project;
    setProjectQuery(project.id);
};

const closeProjectModal = () => {
    selectedProject.value = null;
    setProjectQuery(null);
};

onMounted(() => {
    const projectId = new URLSearchParams(window.location.search).get(
        'project',
    );
    if (projectId && sections.value.includes('projects')) {
        selectedProject.value =
            data.value.projects.find((p) => String(p.id) === projectId) ?? null;
    }
});

const sectionComponents: Record<SectionId, Component> = {
    about: AboutSection,
    projects: ProjectsSection,
    experience: ExperienceSection,
    skills: SkillsSection,
    education: EducationSection,
    contact: ContactSection,
};

const sectionProps = computed<Record<SectionId, Record<string, unknown>>>(
    () => ({
        about: { data: data.value, onCopyEmail: showCopiedToast },
        projects: {
            projects: data.value.projects,
            onSelectProject: openProjectModal,
        },
        experience: { experience: data.value.experience },
        skills: { categories: data.value.skillCategories },
        education: { education: data.value.education },
        contact: { data: data.value, onCopyEmail: showCopiedToast },
    }),
);

const pageTitle = computed(() =>
    [data.value.name, data.value.title].filter(Boolean).join(' — '),
);
</script>

<template>
    <div
        class="relative min-h-screen overflow-x-clip bg-canvas font-sans text-zinc-900 antialiased selection:bg-lavender-300/50 dark:selection:bg-lavender-400/40 selection:text-zinc-950 dark:bg-zinc-950 dark:text-zinc-100 dark:selection:text-white"
    >
        <Head :title="pageTitle">
            <meta
                v-if="data.summary"
                name="description"
                :content="data.summary"
            />
        </Head>

        <Navbar
            :name="data.name"
            :is-dark="isDark"
            :cv-pdf-url="data.cvPdfUrl"
            :sections="sections"
            @toggle-theme="toggleTheme"
        />

        <main class="relative">
            <HeroSection :data="data" />

            <SkillsMarquee
                v-if="sections.includes('skills')"
                :categories="data.skillCategories"
            />

            <component
                :is="sectionComponents[id]"
                v-for="(id, position) in sections"
                :key="id"
                v-bind="sectionProps[id]"
                :index="position + 1"
            />
        </main>

        <footer class="no-print border-t border-zinc-300 dark:border-white/5">
            <div
                class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-2 px-4 py-8 text-xs text-zinc-600 sm:flex-row sm:px-6 lg:px-8 dark:text-zinc-400"
            >
                <div class="flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand" />
                    <span
                        class="font-semibold text-zinc-700 dark:text-zinc-300"
                    >
                        {{ data.name }}
                    </span>
                    <span v-if="data.title">· {{ data.title }}</span>
                </div>
                <div class="tabular-nums">
                    © {{ new Date().getFullYear() }}
                    <template v-if="data.contact?.location">
                        · {{ data.contact.location }}
                    </template>
                </div>
            </div>
        </footer>

        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="translate-y-2 opacity-0"
            leave-active-class="transition duration-150 ease-in"
            leave-to-class="translate-y-2 opacity-0"
        >
            <div
                v-if="toastMessage"
                role="status"
                class="fixed right-5 bottom-5 z-50 flex items-center gap-2 rounded-full bg-zinc-900 px-4 py-2.5 text-xs font-medium text-white shadow-xl dark:bg-white dark:text-zinc-950"
            >
                <Check
                    class="h-3.5 w-3.5 text-emerald-400 dark:text-emerald-600"
                />
                <span>{{ toastMessage }}</span>
            </div>
        </transition>

        <ProjectDetailModal
            :project="selectedProject"
            :is-open="selectedProject !== null"
            :projects="data.projects"
            @close="closeProjectModal"
            @navigate="openProjectModal"
        />
    </div>
</template>
