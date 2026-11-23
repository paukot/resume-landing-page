<script setup lang="ts">
import { computed } from 'vue';
import type { ResumeData } from '@/types/resume';
import { useTranslations } from '@/composables/useTranslations';
import { useClipboard } from '@/composables/useClipboard';
import { displayUrl } from '@/lib/utils';
import LinkedinIcon from '@/components/icons/LinkedinIcon.vue';
import GithubIcon from '@/components/icons/GithubIcon.vue';
import { ArrowUpRight, Check, Copy, Mail } from 'lucide-vue-next';

const props = defineProps<{
    data: ResumeData;
    index?: number;
}>();

const emit = defineEmits<{
    (e: 'copy-email', email: string): void;
}>();

const { t } = useTranslations();
const { copied, copy } = useClipboard();

const email = computed(() => props.data.contact?.email ?? '');
const linkedin = computed(() => props.data.contact?.linkedin ?? '');
const github = computed(() => props.data.contact?.github ?? '');

const mailtoHref = computed(() => {
    const subject = t('contact.email_subject', { name: props.data.name });
    return `mailto:${email.value}?subject=${encodeURIComponent(subject)}`;
});

const hasBothChannels = computed(() => Boolean(email.value && linkedin.value));

const copyEmail = async () => {
    if (email.value && (await copy(email.value))) {
        emit('copy-email', email.value);
    }
};
</script>

<template>
    <section id="contact" class="relative py-20 sm:py-28">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div
                v-reveal
                class="relative overflow-hidden rounded-[2rem] border border-zinc-300 bg-white px-6 py-14 shadow-sm sm:px-12 sm:py-20 dark:border-white/10 dark:bg-zinc-900"
            >
                <div class="mx-auto max-w-2xl text-center">
                    <p
                        class="text-gradient text-xs font-semibold tracking-[0.2em] uppercase"
                    >
                        {{ t('section.connect') }}
                    </p>
                    <h2
                        class="mt-4 text-3xl font-bold tracking-tight text-balance text-zinc-950 sm:text-5xl dark:text-white"
                    >
                        {{ t('contact.message_me_email_or_linkedin') }}
                    </h2>
                    <p
                        class="mt-4 text-base leading-relaxed text-zinc-700 dark:text-zinc-400"
                    >
                        {{ t('contact.reach_out_description') }}
                    </p>
                </div>

                <div
                    class="mx-auto mt-12 grid max-w-3xl grid-cols-1 gap-5"
                    :class="{ 'md:grid-cols-2': hasBothChannels }"
                >
                    <div
                        v-if="email"
                        class="card-glow flex flex-col rounded-3xl border border-zinc-300 bg-zinc-100 p-6 dark:border-white/10 dark:bg-zinc-950/60"
                    >
                        <div class="flex items-center justify-between gap-2">
                            <span
                                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400"
                            >
                                <Mail class="h-5 w-5" />
                            </span>
                            <span
                                class="rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-[10px] font-bold tracking-wider text-emerald-700 uppercase dark:text-emerald-300"
                            >
                                {{ t('direct_inbox') }}
                            </span>
                        </div>
                        <h3
                            class="mt-4 text-lg font-bold text-zinc-950 dark:text-white"
                        >
                            {{ t('email') }}
                        </h3>
                        <p
                            class="mt-1 text-sm text-zinc-700 dark:text-zinc-400"
                        >
                            {{ t('contact.email_details') }}
                        </p>
                        <div
                            class="mt-3 truncate text-sm font-medium text-zinc-900 select-all dark:text-zinc-100"
                        >
                            {{ email }}
                        </div>
                        <div class="mt-6 flex items-center gap-2">
                            <a
                                :href="mailtoHref"
                                class="btn btn-primary flex-1 px-4 py-2.5 text-sm"
                            >
                                <Mail class="h-4 w-4" />
                                {{ t('send_email') }}
                            </a>
                            <button
                                type="button"
                                @click="copyEmail"
                                :title="t('copy_email')"
                                class="btn btn-secondary shrink-0 px-4 py-2.5 text-sm"
                            >
                                <component
                                    :is="copied ? Check : Copy"
                                    class="h-4 w-4"
                                    :class="{ 'text-emerald-500': copied }"
                                />
                                {{ copied ? t('copied') : t('copy') }}
                            </button>
                        </div>
                    </div>

                    <div
                        v-if="linkedin"
                        class="card-glow flex flex-col rounded-3xl border border-zinc-300 bg-zinc-100 p-6 dark:border-white/10 dark:bg-zinc-950/60"
                    >
                        <div class="flex items-center justify-between gap-2">
                            <span
                                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-500/10 text-sky-600 dark:text-sky-400"
                            >
                                <LinkedinIcon class="h-5 w-5" />
                            </span>
                            <span
                                class="rounded-full bg-sky-500/10 px-2.5 py-0.5 text-[10px] font-bold tracking-wider text-sky-700 uppercase dark:text-sky-300"
                            >
                                {{ t('fast_response') }}
                            </span>
                        </div>
                        <h3
                            class="mt-4 text-lg font-bold text-zinc-950 dark:text-white"
                        >
                            {{ t('linkedin') }}
                        </h3>
                        <p
                            class="mt-1 text-sm text-zinc-700 dark:text-zinc-400"
                        >
                            {{ t('contact.linkedin_details') }}
                        </p>
                        <div
                            class="mt-3 truncate text-sm font-medium text-zinc-900 dark:text-zinc-100"
                        >
                            {{ displayUrl(linkedin) }}
                        </div>
                        <div class="mt-6">
                            <a
                                :href="linkedin"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary w-full px-4 py-2.5 text-sm"
                            >
                                {{ t('message_on_linkedin') }}
                                <ArrowUpRight class="h-4 w-4" />
                            </a>
                        </div>
                    </div>
                </div>

                <div v-if="github" class="mt-8 flex justify-center">
                    <a
                        :href="github"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-zinc-700 transition-colors hover:text-zinc-950 dark:text-zinc-400 dark:hover:text-white"
                    >
                        <GithubIcon class="h-4 w-4" />
                        {{ displayUrl(github) }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>
