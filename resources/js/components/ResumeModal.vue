<script setup lang="ts">
import type { ResumeData } from '../types/resume';
import Icon from './Icon.vue';

defineProps<{
    data: ResumeData;
    isOpen: boolean;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const handlePrint = () => {
    window.print();
};
</script>

<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/70 p-3 backdrop-blur-sm sm:p-6"
    >
        <!-- Modal Backdrop -->
        <div class="fixed inset-0" @click="emit('close')" />

        <!-- Modal Dialog -->
        <div
            class="relative z-10 flex max-h-[92vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-2xl dark:border-neutral-800 dark:bg-neutral-900"
        >
            <!-- Modal Header (no-print) -->
            <div
                class="no-print flex items-center justify-between border-b border-neutral-200 bg-neutral-50 px-6 py-4 dark:border-neutral-800 dark:bg-neutral-950"
            >
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-violet-600" />
                    <span
                        class="text-sm font-bold text-neutral-900 dark:text-white"
                    >
                        Curriculum Vitae Preview
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        @click="handlePrint"
                        class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg bg-violet-600 px-3.5 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-violet-700"
                    >
                        <Icon name="printer" className="w-3.5 h-3.5" />
                        <span>Print / Save PDF</span>
                    </button>
                    <button
                        type="button"
                        @click="emit('close')"
                        class="rounded-lg p-1.5 text-neutral-400 transition-colors hover:bg-neutral-200/50 hover:text-neutral-700 dark:hover:bg-neutral-800 dark:hover:text-neutral-200"
                        aria-label="Close modal"
                    >
                        <Icon name="x-mark" className="w-5 h-5" />
                    </button>
                </div>
            </div>

            <!-- Scrollable Resume Document -->
            <div
                class="space-y-8 overflow-y-auto bg-white p-6 font-sans text-neutral-900 sm:p-10 print:p-0"
            >
                <!-- Resume Header -->
                <div class="border-b border-neutral-200 pb-6">
                    <div
                        class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start"
                    >
                        <div>
                            <h1
                                class="text-3xl font-extrabold tracking-tight text-neutral-950"
                            >
                                {{ data.name }}
                            </h1>
                            <p
                                class="mt-1 text-base font-semibold text-violet-700"
                            >
                                {{ data.title }}
                            </p>
                            <p class="mt-1 text-xs text-neutral-600">
                                {{ data.contact.location }} •
                                {{ data.contact.timezone }}
                            </p>
                        </div>
                        <div
                            class="space-y-1 text-xs text-neutral-700 sm:text-right"
                        >
                            <div>
                                <a
                                    :href="`mailto:${data.contact.email}`"
                                    class="font-medium text-violet-700 underline"
                                >
                                    {{ data.contact.email }}
                                </a>
                            </div>
                            <div
                                v-for="social in data.socials.slice(0, 3)"
                                :key="social.name"
                            >
                                <a
                                    :href="social.url"
                                    target="_blank"
                                    class="text-neutral-600 hover:underline"
                                >
                                    {{ social.label }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Executive Summary -->
                <div>
                    <h2
                        class="mb-2.5 border-b border-neutral-200 pb-1 text-xs font-bold tracking-wider text-neutral-500 uppercase"
                    >
                        Professional Summary
                    </h2>
                    <p class="text-xs leading-relaxed text-neutral-700">
                        {{ data.about.join(' ') }}
                    </p>
                </div>

                <!-- Work Experience -->
                <div>
                    <h2
                        class="mb-4 border-b border-neutral-200 pb-1 text-xs font-bold tracking-wider text-neutral-500 uppercase"
                    >
                        Experience
                    </h2>
                    <div class="space-y-5">
                        <div v-for="job in data.experience" :key="job.id">
                            <div
                                class="flex flex-col justify-between gap-1 sm:flex-row sm:items-baseline"
                            >
                                <div>
                                    <span
                                        class="text-sm font-bold text-neutral-900"
                                        >{{ job.role }}</span
                                    >
                                    <span
                                        class="text-xs font-medium text-neutral-500"
                                    >
                                        — {{ job.company }}</span
                                    >
                                </div>
                                <span
                                    class="font-mono text-xs text-neutral-600"
                                    >{{ job.period }}</span
                                >
                            </div>
                            <p class="mt-1 text-xs text-neutral-600 italic">
                                {{ job.description }}
                            </p>
                            <ul class="mt-2 space-y-1">
                                <li
                                    v-for="(hl, i) in job.highlights"
                                    :key="i"
                                    class="flex items-start gap-1.5 text-xs text-neutral-700"
                                >
                                    <span
                                        class="shrink-0 font-bold text-violet-600"
                                        >•</span
                                    >
                                    <span>{{ hl }}</span>
                                </li>
                            </ul>
                            <div
                                class="mt-2 flex flex-wrap gap-1 text-[11px] text-neutral-600"
                            >
                                <span class="font-semibold">Technologies:</span>
                                <span>{{ job.technologies.join(', ') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Education -->
                <div>
                    <h2
                        class="mb-3 border-b border-neutral-200 pb-1 text-xs font-bold tracking-wider text-neutral-500 uppercase"
                    >
                        Education
                    </h2>
                    <div
                        class="space-y-3"
                        v-for="edu in data.education"
                        :key="edu.id"
                    >
                        <div
                            class="flex flex-col justify-between gap-1 sm:flex-row sm:items-baseline"
                        >
                            <div>
                                <span
                                    class="text-xs font-bold text-neutral-900"
                                    >{{ edu.degree }}</span
                                >
                                <span class="text-xs text-neutral-600">
                                    — {{ edu.institution }}</span
                                >
                            </div>
                            <span class="font-mono text-xs text-neutral-600">{{
                                edu.period
                            }}</span>
                        </div>
                        <div class="text-xs text-neutral-600">
                            {{ edu.honors }} • GPA: {{ edu.gpa }}
                        </div>
                    </div>
                </div>

                <!-- Key Skills Matrix -->
                <div>
                    <h2
                        class="mb-3 border-b border-neutral-200 pb-1 text-xs font-bold tracking-wider text-neutral-500 uppercase"
                    >
                        Core Competencies
                    </h2>
                    <div class="grid grid-cols-1 gap-3 text-xs sm:grid-cols-2">
                        <div v-for="cat in data.skillCategories" :key="cat.id">
                            <span class="font-bold text-neutral-900"
                                >{{ cat.name }}:</span
                            >
                            <span class="ml-1 text-neutral-700">
                                {{ cat.skills.map((s) => s.name).join(', ') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Selected Projects -->
                <div>
                    <h2
                        class="mb-3 border-b border-neutral-200 pb-1 text-xs font-bold tracking-wider text-neutral-500 uppercase"
                    >
                        Featured Projects
                    </h2>
                    <div class="space-y-3">
                        <div
                            v-for="proj in data.projects.slice(0, 3)"
                            :key="proj.id"
                            class="text-xs"
                        >
                            <div class="font-bold text-neutral-900">
                                {{ proj.title }}
                                <span class="font-normal text-neutral-600"
                                    >— {{ proj.tagline }}</span
                                >
                            </div>
                            <div class="mt-0.5 text-neutral-600">
                                {{ proj.description }}
                            </div>
                            <div class="mt-0.5 text-neutral-500">
                                <span class="font-medium">Stack:</span>
                                {{ proj.technologies.join(', ') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
