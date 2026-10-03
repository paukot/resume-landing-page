<script setup lang="ts">
import { ref } from 'vue';
import type { ResumeData } from '@/types/resume';
import Icon from './icons/Icon.vue';
import {useTranslations} from "@/composables/useTranslations";
import LinkedinIcon from "@/components/icons/LinkedinIcon.vue";
import {Check, Copy, ExternalLink, Mail} from "lucide-vue-next";

const props = defineProps<{
    data: ResumeData;
}>();

const { t } = useTranslations();

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
    }, 2000);
};

const handleSend = () => {
    if (!form.value.email || !form.value.message) return;
    messageSent.value = true;
    setTimeout(() => {
        form.value = { name: '', email: '', message: '' };
        messageSent.value = false;
    }, 4000);
};
</script>

<template>
    <section id="contact" class="relative py-16 sm:py-20">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div
                class="mb-4 flex items-center gap-2 text-xs font-semibold tracking-wider text-neutral-500 uppercase dark:text-neutral-400"
            >
                <span>06</span>
                <span>/</span>
                <span>{{ t('section.connect') }}</span>
            </div>

            <div class="mb-10">
                <h2
                    class="text-2xl font-extrabold tracking-tight text-neutral-950 sm:text-4xl dark:text-white"
                >
                    {{ t('contact.message_me_email_or_linkedin') }}
                </h2>
                <p
                    class="mt-2 max-w-2xl text-sm leading-relaxed text-neutral-600 sm:text-base dark:text-neutral-400"
                >
                    {{ t('contact.reach_out_description') }}
                </p>
            </div>

            <!-- Primary Direct Connect Cards (LinkedIn & Email) -->
            <div class="mb-10 grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- LinkedIn Card -->
                <div
                    class="group flex flex-col justify-between rounded-2xl border border-sky-200/80 bg-linear-to-br from-sky-500/10 via-sky-500/5 to-transparent p-6 shadow-xs transition-all hover:border-sky-400 dark:border-sky-900/60 dark:from-sky-950/30 dark:via-neutral-900 dark:to-neutral-900 dark:hover:border-sky-700"
                >
                    <div>
                        <div
                            class="mb-4 flex items-center justify-between gap-2"
                        >
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-600 shadow-xs dark:bg-sky-900/50 dark:text-sky-300"
                            >
                                <LinkedinIcon name="linkedin" className="w-5 h-5" />
                            </div>
                            <span
                                class="rounded-full bg-sky-100 px-2 py-0.5 text-[10px] font-semibold tracking-wider text-sky-800 uppercase dark:bg-sky-900/60 dark:text-sky-200"
                            >
                                {{ t('fast_response') }}
                            </span>
                        </div>

                        <h3
                            class="text-lg font-bold text-neutral-950 transition-colors group-hover:text-sky-600 dark:text-white dark:group-hover:text-sky-400"
                        >
                            {{ t('linkedin') }}
                        </h3>
                        <p
                            class="mt-1 text-xs text-neutral-600 dark:text-neutral-400"
                        >
                            {{ t('contact.linkedin_details') }}
                        </p>
                        <div
                            class="mt-3 font-mono text-xs text-neutral-800 dark:text-neutral-200"
                        >
                            {{ data.contact.linkedin }}
                        </div>
                    </div>

                    <div
                        class="mt-6 border-t border-sky-200/50 pt-4 dark:border-sky-900/40"
                    >
                        <a
                            :href="data.contact.linkedin"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition-colors hover:bg-sky-700 sm:text-sm"
                        >
                            <span>{{ t('message_on_linkedin') }}</span>
                            <ExternalLink
                                className="w-3.5 h-3.5"
                            />
                        </a>
                    </div>
                </div>

                <!-- Email Card -->
                <div
                    class="group flex flex-col justify-between rounded-2xl border border-emerald-200/80 bg-linear-to-br from-emerald-500/10 via-emerald-500/5 to-transparent p-6 shadow-xs transition-all hover:border-emerald-400 dark:border-emerald-900/60 dark:from-emerald-950/30 dark:via-neutral-900 dark:to-neutral-900 dark:hover:border-emerald-700"
                >
                    <div>
                        <div
                            class="mb-4 flex items-center justify-between gap-2"
                        >
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 shadow-xs dark:bg-emerald-900/50 dark:text-emerald-300"
                            >
                                <Mail className="w-5 h-5" />
                            </div>
                            <span
                                class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold tracking-wider text-emerald-800 uppercase dark:bg-emerald-900/60 dark:text-emerald-200"
                            >
                                {{ t('direct_inbox') }}
                            </span>
                        </div>

                        <h3
                            class="text-lg font-bold text-neutral-950 transition-colors group-hover:text-emerald-600 dark:text-white dark:group-hover:text-emerald-400"
                        >
                            {{ t('email') }}
                        </h3>
                        <p
                            class="mt-1 text-xs text-neutral-600 dark:text-neutral-400"
                        >
                            {{ t('contact.email_details') }}
                        </p>
                        <div
                            class="mt-3 font-mono text-xs text-neutral-800 select-all dark:text-neutral-200"
                        >
                            {{ data.contact.email }}
                        </div>
                    </div>

                    <div
                        class="mt-6 flex items-center gap-2 border-t border-emerald-200/50 pt-4 dark:border-emerald-900/40"
                    >
                        <a
                            :href="`mailto:${data.contact.email}?subject=Hello%20Paulina%20-%20Backend%20Role%20Inquiry`"
                            class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-emerald-600 px-3 py-2.5 text-xs font-semibold text-white shadow-xs transition-colors hover:bg-emerald-700 sm:text-sm"
                        >
                            <Mail className="w-3.5 h-3.5" />
                            <span>{{ t('send_email') }}</span>
                        </a>

                        <button
                            type="button"
                            @click="copyEmail"
                            class="dark:hover:bg-neutral-750 inline-flex shrink-0 cursor-pointer items-center justify-center gap-1 rounded-xl border border-neutral-300 bg-white px-3 py-2.5 text-xs font-medium text-neutral-800 transition-colors hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200"
                            title="Copy email address"
                        >
                            <component :is="copied ? Check : Copy" class="h-3.5 w-3.5" />
                            <span>{{ copied ? t('copied') : t('copy') }}</span>
                        </button>
                    </div>
                </div>
            </div>



            <!-- Clean minimalist footer -->
            <footer
                class="no-print mt-20 flex flex-col items-center justify-between gap-3 border-t border-neutral-200/80 pt-6 text-xs text-neutral-500 sm:flex-row dark:border-neutral-800/80 dark:text-neutral-400"
            >
                <div>{{ data.name }} • {{ data.title }}</div>
                <div>{{ new Date().getFullYear() }} • Poland</div>
            </footer>
        </div>
    </section>
</template>
