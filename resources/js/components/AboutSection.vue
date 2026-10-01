<script setup lang="ts">
import { ref } from 'vue';
import type { ResumeData } from '../types/resume';
import Icon from './Icon.vue';

const props = defineProps<{
    data: ResumeData;
}>();

const emit = defineEmits<{
    (e: 'copy-email', email: string): void;
}>();

const copied = ref(false);

const copyEmail = () => {
    navigator.clipboard.writeText(props.data.contact.email);
    copied.value = true;
    emit('copy-email', props.data.contact.email);
    setTimeout(() => {
        copied.value = false;
    }, 2500);
};
</script>

<template>
    <section
        id="about"
        class="relative border-t border-neutral-200/60 py-20 dark:border-neutral-800/60"
    >
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="mb-10 flex items-center gap-3">
                <span
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-100 text-violet-700 dark:bg-violet-950/60 dark:text-violet-400"
                >
                    <Icon name="user" className="w-4 h-4" />
                </span>
                <div>
                    <h2
                        class="text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl dark:text-white"
                    >
                        About Me
                    </h2>
                    <p
                        class="text-xs text-neutral-500 sm:text-sm dark:text-neutral-400"
                    >
                        Background, engineering philosophy, and personal profile
                    </p>
                </div>
            </div>

            <!-- Content Grid: Bio on Left, Quick Facts & Connect on Right -->
            <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-12">
                <!-- Left Column: Avatar & Biography -->
                <div class="space-y-6 lg:col-span-7">
                    <!-- Profile Card -->
                    <div
                        class="rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-xs sm:p-7 dark:border-neutral-800/80 dark:bg-neutral-900"
                    >
                        <div
                            class="flex flex-col items-start gap-5 border-b border-neutral-100 pb-6 sm:flex-row sm:items-center dark:border-neutral-800"
                        >
                            <!-- Avatar / Initials with Glow -->
                            <div class="relative shrink-0">
                                <div
                                    class="h-20 w-20 rounded-2xl bg-gradient-to-tr from-violet-600 via-indigo-600 to-cyan-500 p-0.5 shadow-lg shadow-violet-500/20"
                                >
                                    <div
                                        class="flex h-full w-full items-center justify-center rounded-[14px] bg-white text-2xl font-extrabold tracking-tighter text-neutral-900 dark:bg-neutral-900 dark:text-white"
                                    >
                                        {{
                                            data.name
                                                .split(' ')
                                                .map((n) => n[0])
                                                .join('')
                                        }}
                                    </div>
                                </div>
                                <span
                                    class="absolute -right-1 -bottom-1 flex h-5 w-5 items-center justify-center rounded-full border-2 border-white bg-emerald-500 dark:border-neutral-900"
                                    title="Active & Ready"
                                >
                                    <span
                                        class="h-1.5 w-1.5 animate-pulse rounded-full bg-white"
                                    />
                                </span>
                            </div>

                            <!-- Name, Title & Location -->
                            <div>
                                <h3
                                    class="text-xl font-bold text-neutral-900 sm:text-2xl dark:text-white"
                                >
                                    {{ data.name }}
                                </h3>
                                <p
                                    class="mt-0.5 text-sm font-medium text-violet-600 dark:text-violet-400"
                                >
                                    {{ data.title }}
                                </p>
                                <div
                                    class="mt-2 flex flex-wrap items-center gap-3 text-xs text-neutral-500 dark:text-neutral-400"
                                >
                                    <span
                                        class="inline-flex items-center gap-1"
                                    >
                                        <Icon
                                            name="map-pin"
                                            className="w-3.5 h-3.5 text-neutral-400"
                                        />
                                        {{ data.contact.location }}
                                    </span>
                                    <span
                                        class="inline-flex items-center gap-1"
                                    >
                                        <Icon
                                            name="globe"
                                            className="w-3.5 h-3.5 text-neutral-400"
                                        />
                                        {{ data.contact.timezone }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Bio Paragraphs -->
                        <div
                            class="space-y-4 pt-6 text-sm leading-relaxed font-normal text-neutral-600 sm:text-base dark:text-neutral-300"
                        >
                            <p
                                v-for="(paragraph, idx) in data.about"
                                :key="idx"
                            >
                                {{ paragraph }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Quick Facts & Direct Channels -->
                <div class="space-y-6 lg:col-span-5">
                    <!-- Quick Facts Card -->
                    <div
                        class="rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-xs dark:border-neutral-800/80 dark:bg-neutral-900"
                    >
                        <h4
                            class="mb-4 text-xs font-semibold tracking-wider text-neutral-400 uppercase dark:text-neutral-500"
                        >
                            Quick Snapshot
                        </h4>
                        <div class="space-y-3.5">
                            <div
                                v-for="(fact, i) in data.quickFacts"
                                :key="i"
                                class="flex items-start gap-3 rounded-xl border border-neutral-100 bg-neutral-50 p-3 dark:border-neutral-800/60 dark:bg-neutral-800/60"
                            >
                                <div
                                    class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-700 dark:bg-violet-950/50 dark:text-violet-300"
                                >
                                    <Icon
                                        :name="fact.icon"
                                        className="w-4 h-4"
                                    />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div
                                        class="text-xs font-medium text-neutral-500 dark:text-neutral-400"
                                    >
                                        {{ fact.label }}
                                    </div>
                                    <div
                                        class="truncate text-xs font-semibold text-neutral-900 sm:text-sm dark:text-neutral-100"
                                    >
                                        {{ fact.value }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Direct Connect & Copy Email -->
                    <div
                        class="rounded-2xl border border-violet-200/60 bg-gradient-to-br from-violet-500/10 via-indigo-500/5 to-transparent p-6 dark:border-violet-900/40 dark:from-violet-950/30 dark:via-neutral-900 dark:to-neutral-900"
                    >
                        <h4
                            class="mb-2 text-xs font-semibold tracking-wider text-violet-700 uppercase dark:text-violet-400"
                        >
                            Direct Contact
                        </h4>
                        <p
                            class="mb-4 text-xs text-neutral-600 dark:text-neutral-400"
                        >
                            Feel free to reach out directly for work inquiries,
                            consulting, or technical discussions.
                        </p>

                        <!-- Copy Email Button -->
                        <div
                            class="mb-4 flex items-center justify-between rounded-xl border border-neutral-200 bg-white p-2.5 shadow-xs dark:border-neutral-700 dark:bg-neutral-800"
                        >
                            <span
                                class="truncate pr-2 font-mono text-xs text-neutral-800 select-all sm:text-sm dark:text-neutral-200"
                            >
                                {{ data.contact.email }}
                            </span>
                            <button
                                type="button"
                                @click="copyEmail"
                                class="inline-flex shrink-0 cursor-pointer items-center gap-1.5 rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-violet-700"
                            >
                                <Icon
                                    :name="copied ? 'check' : 'copy'"
                                    className="w-3.5 h-3.5"
                                />
                                <span>{{ copied ? 'Copied!' : 'Copy' }}</span>
                            </button>
                        </div>

                        <!-- Social Link Pills -->
                        <div class="flex flex-wrap gap-2">
                            <a
                                v-for="social in data.socials"
                                :key="social.name"
                                :href="social.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-neutral-200/80 bg-white/80 px-3 py-1.5 text-xs font-medium text-neutral-700 transition-colors hover:border-violet-400 hover:text-neutral-950 dark:border-neutral-700/80 dark:bg-neutral-800/80 dark:text-neutral-300 dark:hover:border-violet-600 dark:hover:text-white"
                            >
                                <Icon
                                    :name="social.icon"
                                    className="w-3.5 h-3.5"
                                />
                                <span>{{ social.name }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
