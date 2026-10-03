<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import type { ResumeData } from '@/types/resume';
import Navbar from '../components/Navbar.vue';
import HeroSection from '../components/HeroSection.vue';
import AboutSection from '../components/AboutSection.vue';
import ExperienceSection from '../components/ExperienceSection.vue';
import SkillsSection from '../components/SkillsSection.vue';
import EducationSection from '../components/EducationSection.vue';
import ProjectsSection from '../components/ProjectsSection.vue';
import ContactSection from '../components/ContactSection.vue';
import ResumeModal from '../components/ResumeModal.vue';
import {Check} from "lucide-vue-next";

const props = withDefaults(
    defineProps<{
        resume: ResumeData;
    }>(),
    {},
);

const data = computed(() => props.resume);

// Theme State
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
    }, 2500);
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
        class="min-h-screen bg-[#fafafa] text-neutral-900 transition-colors duration-200 selection:bg-neutral-900 selection:text-white dark:bg-[#0c0d0e] dark:text-neutral-100 dark:selection:bg-white dark:selection:text-neutral-950"
    >
        <Head :title="`${data.name} — ${data.title}`">
            <meta name="description" :content="data.summary" />
        </Head>

        <!-- Toast Notification -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform translate-y-2 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform translate-y-2 opacity-0"
        >
            <div
                v-if="toastMessage"
                class="fixed right-5 bottom-5 z-50 flex items-center gap-2 rounded-lg bg-neutral-900 px-3.5 py-2.5 text-xs font-medium text-white shadow-lg dark:bg-white dark:text-neutral-950"
            >
                <Check
                    className="w-3.5 h-3.5 text-emerald-400 dark:text-emerald-600"
                />
                <span>{{ toastMessage }}</span>
            </div>
        </transition>

        <!-- Navbar -->
        <Navbar
            :name="data.name"
            :isDark="isDark"
            :cvPdfUrl="data.cvPdfUrl"
            @toggle-theme="toggleTheme"
            @open-resume="isResumeModalOpen = true"
        />

        <!-- Main Content -->
        <main>
            <!-- Hero -->
            <HeroSection
                :data="data"
                :isDark="isDark"
                @open-resume="isResumeModalOpen = true"
                @copy-email="showToast(`Copied: ${$event}`)"
            />

            <!-- About & Direct Channels -->
            <AboutSection
                :data="data"
                @copy-email="showToast(`Copied: ${$event}`)"
            />

            <!-- Work Experience -->
            <ExperienceSection :experience="data.experience" />

            <!-- Skills (Simplified) -->
            <SkillsSection :categories="data.skillCategories" />

            <!-- Education -->
            <EducationSection :education="data.education" />

            <!-- Projects & Architecture -->
            <ProjectsSection :projects="data.projects" />

            <!-- Contact & Footer -->
            <ContactSection
                :data="data"
                @copy-email="showToast(`Copied: ${$event}`)"
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
