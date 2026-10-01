<script setup lang="ts">
import type { EducationItem, CertificationItem } from '../types/resume';
import Icon from './Icon.vue';

defineProps<{
    education: EducationItem[];
    certifications: CertificationItem[];
}>();
</script>

<template>
    <section
        id="education"
        class="relative border-t border-neutral-200/60 py-20 dark:border-neutral-800/60"
    >
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="mb-10 flex items-center gap-3">
                <span
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400"
                >
                    <Icon name="graduation-cap" className="w-4 h-4" />
                </span>
                <div>
                    <h2
                        class="text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl dark:text-white"
                    >
                        Education & Credentials
                    </h2>
                    <p
                        class="text-xs text-neutral-500 sm:text-sm dark:text-neutral-400"
                    >
                        Academic foundations, degrees, and industry
                        certifications
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                <!-- Formal Education (Left) -->
                <div class="space-y-6 lg:col-span-7">
                    <h3
                        class="text-xs font-semibold tracking-wider text-neutral-400 uppercase dark:text-neutral-500"
                    >
                        Academic Degrees
                    </h3>

                    <div
                        v-for="edu in education"
                        :key="edu.id"
                        class="rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-xs sm:p-7 dark:border-neutral-800/80 dark:bg-neutral-900"
                    >
                        <div
                            class="flex flex-col gap-2 border-b border-neutral-100 pb-4 sm:flex-row sm:items-center sm:justify-between dark:border-neutral-800"
                        >
                            <div>
                                <h4
                                    class="text-base font-bold text-neutral-900 sm:text-lg dark:text-white"
                                >
                                    {{ edu.degree }}
                                </h4>
                                <div
                                    class="mt-0.5 text-sm font-semibold text-emerald-600 dark:text-emerald-400"
                                >
                                    {{ edu.institution }}
                                </div>
                                <div
                                    class="mt-1 text-xs text-neutral-500 dark:text-neutral-400"
                                >
                                    {{ edu.field }} • {{ edu.location }}
                                </div>
                            </div>
                            <div
                                class="inline-flex items-center gap-1.5 self-start rounded-full bg-neutral-100 px-3 py-1 text-xs font-medium text-neutral-500 sm:self-auto dark:bg-neutral-800 dark:text-neutral-400"
                            >
                                <Icon name="calendar" className="w-3.5 h-3.5" />
                                <span>{{ edu.period }}</span>
                            </div>
                        </div>

                        <!-- Honors / GPA -->
                        <div
                            v-if="edu.honors || edu.gpa"
                            class="mt-4 flex flex-wrap items-center gap-2"
                        >
                            <span
                                v-if="edu.gpa"
                                class="inline-flex items-center gap-1 rounded-md border border-emerald-200/60 bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:border-emerald-800/40 dark:bg-emerald-950/40 dark:text-emerald-300"
                            >
                                <Icon name="award" className="w-3 h-3" />
                                GPA: {{ edu.gpa }}
                            </span>
                            <span
                                v-if="edu.honors"
                                class="inline-flex items-center gap-1 rounded-md border border-amber-200/60 bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 dark:border-amber-800/40 dark:bg-amber-950/40 dark:text-amber-300"
                            >
                                <Icon name="star" className="w-3 h-3" />
                                {{ edu.honors }}
                            </span>
                        </div>

                        <!-- Relevant Coursework -->
                        <div
                            v-if="edu.keyCourses && edu.keyCourses.length > 0"
                            class="mt-5"
                        >
                            <div
                                class="mb-2 text-xs font-semibold text-neutral-400 dark:text-neutral-500"
                            >
                                Relevant Coursework & Focus:
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                <span
                                    v-for="course in edu.keyCourses"
                                    :key="course"
                                    class="rounded-md bg-neutral-100 px-2.5 py-0.5 text-xs text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300"
                                >
                                    {{ course }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Industry Certifications (Right) -->
                <div class="space-y-6 lg:col-span-5">
                    <h3
                        class="text-xs font-semibold tracking-wider text-neutral-400 uppercase dark:text-neutral-500"
                    >
                        Professional Certifications
                    </h3>

                    <div class="space-y-4">
                        <div
                            v-for="cert in certifications"
                            :key="cert.id"
                            class="rounded-2xl border border-neutral-200/80 bg-white p-5 shadow-xs transition-colors hover:border-emerald-300 dark:border-neutral-800/80 dark:bg-neutral-900 dark:hover:border-emerald-700"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-xs font-bold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400"
                                    >
                                        {{ cert.badge || 'CERT' }}
                                    </div>
                                    <div>
                                        <h4
                                            class="text-sm leading-tight font-bold text-neutral-900 dark:text-white"
                                        >
                                            {{ cert.name }}
                                        </h4>
                                        <p
                                            class="mt-1 text-xs font-medium text-emerald-600 dark:text-emerald-400"
                                        >
                                            {{ cert.issuer }}
                                        </p>
                                        <span
                                            class="mt-0.5 inline-block text-[11px] text-neutral-400 dark:text-neutral-500"
                                        >
                                            Issued {{ cert.issueDate }}
                                        </span>
                                    </div>
                                </div>

                                <a
                                    v-if="cert.credentialUrl"
                                    :href="cert.credentialUrl"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="shrink-0 rounded-lg p-1.5 text-neutral-400 transition-colors hover:bg-neutral-100 hover:text-neutral-900 dark:hover:bg-neutral-800 dark:hover:text-white"
                                    title="Verify credential"
                                >
                                    <Icon
                                        name="external-link"
                                        className="w-4 h-4"
                                    />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
