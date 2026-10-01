<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import type { ResumeData } from '../types/resume';
import { resumeData } from '../data/resumeData';
import Navbar from '../components/Navbar.vue';
import HeroSection from '../components/HeroSection.vue';
import AboutSection from '../components/AboutSection.vue';
import ExperienceSection from '../components/ExperienceSection.vue';
import SkillsSection from '../components/SkillsSection.vue';
import EducationSection from '../components/EducationSection.vue';
import ProjectsSection from '../components/ProjectsSection.vue';
import ContactSection from '../components/ContactSection.vue';
import ResumeModal from '../components/ResumeModal.vue';
import Icon from '../components/Icon.vue';

const props = withDefaults(
    defineProps<{
        customData?: Partial<ResumeData>;
    }>(),
    {},
);

const data = computed<ResumeData>(() => ({
    ...resumeData,
    ...props.customData,
}));

// Dark Mode State
const isDark = ref(false);

const toggleTheme = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
};

// Toast notification
const toastMessage = ref<string | null>(null);
let toastTimer: ReturnType<typeof setTimeout> | null = null;

const showToast = (message: string) => {
    toastMessage.value = message;
    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
        toastMessage.value = null;
    }, 3000);
};

// Resume modal state
const isResumeModalOpen = ref(false);

onMounted(() => {
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia(
        '(prefers-color-scheme: dark)',
    ).matches;

    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
        isDark.value = true;
        document.documentElement.classList.add('dark');
    } else {
        isDark.value = false;
        document.documentElement.classList.remove('dark');
    }
});
</script>

<template>
    <div
        class="relative min-h-screen bg-neutral-50 text-neutral-900 transition-colors duration-300 selection:bg-violet-600 selection:text-white dark:bg-neutral-950 dark:text-neutral-100"
    >
        <Head :title="`${data.name} — ${data.title}`">
            <meta name="description" :content="data.tagline" />
            <link rel="preconnect" href="https://fonts.bunny.net" />
        </Head>

        <!-- Toast Alert Notification -->
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform translate-y-4 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform translate-y-4 opacity-0"
        >
            <div
                v-if="toastMessage"
                class="fixed right-6 bottom-6 z-50 flex items-center gap-2.5 rounded-xl border border-neutral-700 bg-neutral-900 px-4 py-3 text-xs font-medium text-white shadow-xl sm:text-sm dark:border-neutral-200 dark:bg-white dark:text-neutral-950"
            >
                <Icon
                    name="check"
                    className="w-4 h-4 text-emerald-400 dark:text-emerald-600"
                />
                <span>{{ toastMessage }}</span>
            </div>
        </transition>

        <!-- Navigation Bar -->
        <Navbar
            :name="data.name"
            :isDark="isDark"
            @toggle-theme="toggleTheme"
            @open-resume="isResumeModalOpen = true"
        />

        <!-- Main Landing Content -->
        <main class="relative">
            <!-- Hero with fancy animations -->
            <HeroSection :data="data" @open-resume="isResumeModalOpen = true" />

            <!-- About / Personal Information -->
            <AboutSection
                :data="data"
                @copy-email="showToast(`Email copied: ${$event}`)"
            />

            <!-- Experience -->
            <ExperienceSection :experience="data.experience" />

            <!-- Skills -->
            <SkillsSection :categories="data.skillCategories" />

            <!-- Education -->
            <EducationSection
                :education="data.education"
                :certifications="data.certifications"
            />

            <!-- Personal Projects -->
            <ProjectsSection :projects="data.projects" />

            <!-- Contact & Footer -->
            <ContactSection
                :data="data"
                @copy-email="showToast(`Email copied: ${$event}`)"
                @open-resume="isResumeModalOpen = true"
            />
        </main>

        <!-- Printable Resume Modal -->
        <ResumeModal
            :data="data"
            :isOpen="isResumeModalOpen"
            @close="isResumeModalOpen = false"
        />
    </div>
</template>
