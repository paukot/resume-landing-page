<script setup lang="ts">
import type { ResumeData } from '@/types/resume';
import Icon from './icons/Icon.vue';
import {useTranslations} from "@/composables/useTranslations";
import {Download, Printer, X} from "lucide-vue-next";

defineProps<{
    data: ResumeData;
    isOpen: boolean;
}>();

const { t } = useTranslations();

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
        class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/60 p-3 backdrop-blur-xs sm:p-6"
    >
        <div class="fixed inset-0" @click="emit('close')" />

        <div
            class="relative z-10 flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-xl dark:border-neutral-800 dark:bg-neutral-900"
        >
            <!-- Modal Header Toolbar -->
            <div
                class="no-print flex items-center justify-between border-b-2 border-neutral-200 bg-neutral-50 px-6 py-3.5 dark:border-neutral-800 dark:bg-neutral-950"
            >
                <span
                    class="text-xs font-semibold tracking-wider text-neutral-900 uppercase dark:text-white"
                >
                    Curriculum Vitae Preview
                </span>
                <div class="flex items-center gap-2">
                    <a
                        :href="data.cvPdfUrl"
                        target="_blank"
                        download
                        class="inline-flex items-center gap-1.5 rounded-md bg-neutral-900 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-neutral-800 dark:bg-white dark:text-neutral-950 dark:hover:bg-neutral-100"
                    >
                        <Download name="download" className="w-3.5 h-3.5" />
                        <span>{{ t('download_pdf') }}</span>
                    </a>
                    <button
                        type="button"
                        @click="handlePrint"
                        class="inline-flex cursor-pointer items-center gap-1.5 rounded-md bg-neutral-200 px-3 py-1.5 text-xs font-medium text-neutral-800 transition-colors hover:bg-neutral-300 dark:bg-neutral-800 dark:text-neutral-200 dark:hover:bg-neutral-700"
                    >
                        <Printer className="w-3.5 h-3.5" />
                        <span>Print</span>
                    </button>
                    <button
                        type="button"
                        @click="emit('close')"
                        class="cursor-pointer rounded-md p-1.5 text-neutral-400 transition-colors hover:bg-neutral-200/60 hover:text-neutral-700 dark:hover:bg-neutral-800 dark:hover:text-neutral-200"
                        aria-label="Close modal"
                    >
                        <X className="w-4 h-4" />
                    </button>
                </div>
            </div>

            <!-- Resume Document Body (Printable) -->
            <div
                class="space-y-6 overflow-y-auto bg-white p-6 font-sans text-neutral-900 sm:p-10 print:p-0"
            >
                <!-- Header -->
                <div class="border-b-2 border-neutral-300 pb-4">
                    <h1
                        class="text-2xl font-bold tracking-tight text-neutral-950"
                    >
                        {{ data.name }}
                    </h1>
                    <div class="mt-0.5 text-sm font-semibold text-neutral-700">
                        {{ data.title }}
                    </div>
                    <div
                        class="mt-1 flex flex-wrap gap-x-3 gap-y-1 text-xs text-neutral-600"
                    >
                        <span>{{ data.contact.location }}</span>
                        <span>•</span>
                        <span>{{ data.contact.phone }}</span>
                        <span>•</span>
                        <a
                            :href="`mailto:${data.contact.email}`"
                            class="text-neutral-800 underline"
                        >
                            {{ data.contact.email }}
                        </a>
                        <span>•</span>
                        <a
                            :href="data.contact.linkedin"
                            target="_blank"
                            class="text-neutral-800 underline"
                        >
                            {{ data.contact.github }}
                        </a>
                    </div>
                </div>

                <!-- Summary -->
                <div>
                    <h2
                        class="mb-2 border-b-2 border-neutral-200 pb-1 text-xs font-bold tracking-wider text-neutral-500 uppercase"
                    >
                        Summary
                    </h2>
                    <p class="text-xs leading-relaxed text-neutral-700">
                        {{ data.summary }}
                    </p>
                </div>

                <!-- Experience -->
                <div>
                    <h2
                        class="mb-3 border-b-2 border-neutral-200 pb-1 text-xs font-bold tracking-wider text-neutral-500 uppercase"
                    >
                        Experience
                    </h2>
                    <div class="space-y-4">
                        <div v-for="job in data.experience" :key="job.id">
                            <div
                                class="flex items-baseline justify-between text-xs"
                            >
                                <div>
                                    <span class="font-bold text-neutral-950">{{
                                        job.company
                                    }}</span>
                                    <span class="font-medium text-neutral-600">
                                        — {{ job.role }}</span
                                    >
                                </div>
                                <span
                                    class="font-mono text-[11px] text-neutral-600"
                                    >{{ job.period }} ({{ job.location }})</span
                                >
                            </div>
                            <ul class="mt-2 space-y-1">
                                <li
                                    v-for="(hl, i) in job.highlights"
                                    :key="i"
                                    class="flex items-start gap-1.5 text-xs leading-relaxed text-neutral-700"
                                >
                                    <span class="text-neutral-400 select-none"
                                        >•</span
                                    >
                                    <span>{{ hl }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Education -->
                <div>
                    <h2
                        class="mb-2 border-b-2 border-neutral-200 pb-1 text-xs font-bold tracking-wider text-neutral-500 uppercase"
                    >
                        Education
                    </h2>
                    <div
                        v-for="edu in data.education"
                        :key="edu.id"
                        class="text-xs"
                    >
                        <div class="flex items-baseline justify-between">
                            <div>
                                <span class="font-bold text-neutral-950">{{
                                    edu.institution
                                }}</span>
                                <div class="text-neutral-700">
                                    {{ edu.degree }}
                                </div>
                            </div>
                            <span
                                class="font-mono text-[11px] text-neutral-600"
                                >{{ edu.period }}</span
                            >
                        </div>
                    </div>
                </div>

                <!-- Technical Skills -->
                <div>
                    <h2
                        class="mb-2 border-b-2 border-neutral-200 pb-1 text-xs font-bold tracking-wider text-neutral-500 uppercase"
                    >
                        Skills & Languages
                    </h2>
                    <div class="space-y-1 text-xs text-neutral-700">
                        <div>
                            <span class="font-semibold text-neutral-900"
                                >Technical Skills:</span
                            >
                            PHP, JavaScript (ES6+), Laravel (Livewire,
                            FilamentPHP), RESTful APIs, MySQL, Redis, MongoDB,
                            Asynchronous Laravel Queues, PHPUnit/PEST,
                            Elasticsearch, Git, Nginx, Docker.
                        </div>
                        <div>
                            <span class="font-semibold text-neutral-900"
                                >Languages:</span
                            >
                            Polish (Native), English (Advanced / C1).
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
