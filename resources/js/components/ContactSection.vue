<script setup lang="ts">
import { ref } from 'vue';
import type { ResumeData } from '../types/resume';
import Icon from './Icon.vue';

const props = defineProps<{
    data: ResumeData;
}>();

const emit = defineEmits<{
    (e: 'copy-email', email: string): void;
    (e: 'open-resume'): void;
}>();

const copied = ref(false);
const messageSent = ref(false);
const form = ref({
    name: '',
    email: '',
    message: '',
});

const copyEmail = () => {
    navigator.clipboard.writeText(props.data.contact.email);
    copied.value = true;
    emit('copy-email', props.data.contact.email);
    setTimeout(() => {
        copied.value = false;
    }, 2500);
};

const handleSend = () => {
    if (!form.value.email || !form.value.message) return;
    messageSent.value = true;
    setTimeout(() => {
        form.value = { name: '', email: '', message: '' };
        messageSent.value = false;
    }, 4500);
};
</script>

<template>
    <section
        id="contact"
        class="relative border-t border-neutral-200/60 py-20 dark:border-neutral-800/60"
    >
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <!-- Callout Banner -->
            <div
                class="relative mb-16 overflow-hidden rounded-3xl bg-gradient-to-br from-violet-600 via-indigo-700 to-neutral-900 p-8 text-white shadow-xl sm:p-12"
            >
                <div
                    class="pointer-events-none absolute -right-10 -bottom-10 h-80 w-80 rounded-full bg-cyan-400/20 blur-3xl"
                />

                <div class="relative z-10 max-w-2xl">
                    <span
                        class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold backdrop-blur-md"
                    >
                        <span class="h-2 w-2 rounded-full bg-emerald-400" />
                        Let's collaborate
                    </span>
                    <h2
                        class="mb-4 text-3xl font-extrabold tracking-tight sm:text-4xl"
                    >
                        Have an ambitious project or exciting opportunity in
                        mind?
                    </h2>
                    <p
                        class="mb-8 text-sm leading-relaxed text-neutral-200 sm:text-base"
                    >
                        I am currently open to senior engineering roles,
                        technical advisory, and high-impact freelance
                        consulting. Let's discuss how we can work together.
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        <a
                            :href="`mailto:${data.contact.email}?subject=Hello%20Alex%20-%20Project%20Inquiry`"
                            class="inline-flex items-center gap-2 rounded-full bg-white px-6 py-3 text-xs font-semibold text-neutral-950 shadow-sm transition-colors hover:bg-neutral-100 sm:text-sm"
                        >
                            <Icon
                                name="mail"
                                className="w-4 h-4 text-violet-600"
                            />
                            <span>Send an Email</span>
                        </a>

                        <button
                            type="button"
                            @click="copyEmail"
                            class="inline-flex cursor-pointer items-center gap-2 rounded-full bg-white/10 px-5 py-3 text-xs font-semibold text-white backdrop-blur-md transition-colors hover:bg-white/20 sm:text-sm"
                        >
                            <Icon
                                :name="copied ? 'check' : 'copy'"
                                className="w-4 h-4"
                            />
                            <span>{{
                                copied
                                    ? 'Copied to Clipboard!'
                                    : 'Copy Email Address'
                            }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Two-column: Quick Message Form & Socials -->
            <div class="grid grid-cols-1 items-start gap-10 lg:grid-cols-12">
                <!-- Left: Quick Contact Form -->
                <div
                    class="rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-xs sm:p-8 lg:col-span-7 dark:border-neutral-800/80 dark:bg-neutral-900"
                >
                    <h3
                        class="mb-1 text-lg font-bold text-neutral-900 dark:text-white"
                    >
                        Send a Direct Message
                    </h3>
                    <p
                        class="mb-6 text-xs text-neutral-500 dark:text-neutral-400"
                    >
                        Leave a message here and I'll get back to you within 24
                        hours.
                    </p>

                    <div
                        v-if="messageSent"
                        class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs text-emerald-800 sm:text-sm dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300"
                    >
                        <Icon
                            name="check"
                            className="w-5 h-5 text-emerald-500 shrink-0"
                        />
                        <span
                            >Thank you! Your message has been received. I will
                            reply soon.</span
                        >
                    </div>

                    <form v-else @submit.prevent="handleSend" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label
                                    class="mb-1.5 block text-xs font-semibold text-neutral-700 dark:text-neutral-300"
                                >
                                    Your Name
                                </label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    required
                                    placeholder="Sarah Jenkins"
                                    class="w-full rounded-xl border border-neutral-200 bg-neutral-50 px-3.5 py-2 text-xs text-neutral-900 focus:ring-2 focus:ring-violet-500 focus:outline-hidden sm:text-sm dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                />
                            </div>
                            <div>
                                <label
                                    class="mb-1.5 block text-xs font-semibold text-neutral-700 dark:text-neutral-300"
                                >
                                    Your Email
                                </label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    required
                                    placeholder="sarah@example.com"
                                    class="w-full rounded-xl border border-neutral-200 bg-neutral-50 px-3.5 py-2 text-xs text-neutral-900 focus:ring-2 focus:ring-violet-500 focus:outline-hidden sm:text-sm dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                />
                            </div>
                        </div>

                        <div>
                            <label
                                class="mb-1.5 block text-xs font-semibold text-neutral-700 dark:text-neutral-300"
                            >
                                Message
                            </label>
                            <textarea
                                v-model="form.message"
                                rows="4"
                                required
                                placeholder="Hi Alex, I'd like to talk about an engineering role / upcoming web project..."
                                class="w-full resize-y rounded-xl border border-neutral-200 bg-neutral-50 px-3.5 py-2 text-xs text-neutral-900 focus:ring-2 focus:ring-violet-500 focus:outline-hidden sm:text-sm dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                            />
                        </div>

                        <button
                            type="submit"
                            class="inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-violet-600 px-6 py-2.5 text-xs font-semibold text-white transition-colors hover:bg-violet-700 sm:w-auto sm:text-sm"
                        >
                            <span>Send Message</span>
                            <Icon name="arrow-right" className="w-3.5 h-3.5" />
                        </button>
                    </form>
                </div>

                <!-- Right: Information & Social Directory -->
                <div class="space-y-6 lg:col-span-5">
                    <div
                        class="rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-xs dark:border-neutral-800/80 dark:bg-neutral-900"
                    >
                        <h4
                            class="mb-3 text-xs font-semibold tracking-wider text-neutral-400 uppercase dark:text-neutral-500"
                        >
                            Connect Across Platforms
                        </h4>
                        <div class="space-y-2">
                            <a
                                v-for="social in data.socials"
                                :key="social.name"
                                :href="social.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="group flex items-center justify-between rounded-xl p-3 transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-800"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-neutral-100 text-neutral-700 transition-colors group-hover:bg-violet-100 group-hover:text-violet-600 dark:bg-neutral-800 dark:text-neutral-300 dark:group-hover:bg-violet-950/60"
                                    >
                                        <Icon
                                            :name="social.icon"
                                            className="w-4 h-4"
                                        />
                                    </div>
                                    <div>
                                        <div
                                            class="text-xs font-semibold text-neutral-900 dark:text-white"
                                        >
                                            {{ social.name }}
                                        </div>
                                        <div
                                            class="text-[11px] text-neutral-500 dark:text-neutral-400"
                                        >
                                            {{ social.label }}
                                        </div>
                                    </div>
                                </div>
                                <Icon
                                    name="arrow-right"
                                    className="w-4 h-4 text-neutral-400 group-hover:translate-x-1 group-hover:text-violet-500 transition-all"
                                />
                            </a>
                        </div>
                    </div>

                    <!-- Resume CTA Card -->
                    <div
                        class="flex items-center justify-between gap-4 rounded-2xl border border-neutral-200/80 bg-neutral-50 p-6 dark:border-neutral-800/80 dark:bg-neutral-900/60"
                    >
                        <div>
                            <div
                                class="text-xs font-bold text-neutral-900 dark:text-white"
                            >
                                Need a printable CV?
                            </div>
                            <div
                                class="mt-0.5 text-[11px] text-neutral-500 dark:text-neutral-400"
                            >
                                Download or print the formatted PDF version.
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="emit('open-resume')"
                            class="shrink-0 cursor-pointer rounded-lg bg-neutral-900 px-3.5 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-neutral-800 dark:bg-white dark:text-neutral-950 dark:hover:bg-neutral-100"
                        >
                            Open CV
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer
                class="no-print mt-20 flex flex-col items-center justify-between gap-4 border-t border-neutral-200/60 pt-8 text-xs text-neutral-500 sm:flex-row dark:border-neutral-800/60 dark:text-neutral-400"
            >
                <div class="flex items-center gap-2">
                    <span
                        class="font-semibold text-neutral-800 dark:text-neutral-200"
                        >{{ data.name }}</span
                    >
                    <span>© {{ new Date().getFullYear() }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <span>Designed for CV & portfolio showcase</span>
                    <span>•</span>
                    <span>Built with Laravel & Vue 3</span>
                </div>
            </footer>
        </div>
    </section>
</template>
