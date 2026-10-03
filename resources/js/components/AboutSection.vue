<script setup lang="ts">
import { ref } from 'vue';
import type { ResumeData } from '@/types/resume';
import Icon from './icons/Icon.vue';
import {useTranslations} from "@/composables/useTranslations";
import {Mail, Check, Copy, Smartphone, MapPin} from "lucide-vue-next";
import LinkedinIcon from "@/components/icons/LinkedinIcon.vue";
import GithubIcon from "@/components/icons/GithubIcon.vue";
import {Link} from "@inertiajs/vue3";

const props = defineProps<{
    data: ResumeData;
}>();

const { t } = useTranslations();

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
    }, 2000);
};
</script>

<template>
    <section
        id="about"
        class="border-b-2 border-neutral-200/80 py-16 dark:border-neutral-800/80"
    >
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div
                class="mb-6 flex items-center gap-2 text-xs font-semibold tracking-wider text-neutral-500 uppercase dark:text-neutral-400"
            >
                <span>01</span>
                <span>/</span>
                <span>{{ t('section.about_and_contact') }}</span>
            </div>

            <div class="grid grid-cols-1 items-start gap-8 md:grid-cols-12">
                <!-- Left: Bio & Philosophy -->
                <div
                    class="space-y-4 text-sm leading-relaxed text-neutral-700 sm:text-base md:col-span-7 dark:text-neutral-300"
                >
                    {{ data.summary }}

                    <!-- Spoken Languages -->
                    <div
                        class="border-t border-neutral-200/60 pt-4 dark:border-neutral-800/60"
                    >
                        <div
                            class="mb-2 text-xs font-semibold text-neutral-500 dark:text-neutral-400"
                        >
                            {{ t('languages') }}:
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="lang in data.languages"
                                :key="lang.name"
                                class="inline-flex items-center gap-1.5 rounded-md bg-neutral-100 px-3 py-1 text-xs font-medium text-neutral-800 dark:bg-neutral-800 dark:text-neutral-200"
                            >
                                <span class="font-semibold"
                                    >{{ lang.name }}:</span
                                >
                                <span
                                    class="text-neutral-500 dark:text-neutral-400"
                                    >{{ lang.level }}</span
                                >
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right: Contact & Quick Info -->
                <div class="space-y-3 md:col-span-5">
                    <div
                        class="space-y-3 rounded-xl border border-neutral-200/80 bg-neutral-50 p-5 text-xs dark:border-neutral-800/80 dark:bg-neutral-900"
                    >
                        <div
                            class="text-[11px] font-semibold tracking-wider text-neutral-900 uppercase dark:text-neutral-400 dark:text-white"
                        >
                            {{ t('about.direct_contact_details') }}
                        </div>

                        <!-- Email -->
                        <div
                            class="flex items-center justify-between gap-2 border-b-2 border-neutral-200/60 py-1.5 dark:border-neutral-800/60"
                        >
                            <div
                                class="flex items-center gap-2 text-neutral-600 dark:text-neutral-400"
                            >
                                <Mail className="w-3.5 h-3.5" />
                                <span>{{ t('email') }}</span>
                            </div>
                            <div
                                class="flex items-center gap-1.5 font-medium text-neutral-900 dark:text-neutral-100"
                            >
                                <a
                                    :href="`mailto:${data.contact.email}`"
                                    class="hover:underline"
                                >
                                    {{ data.contact.email }}
                                </a>
                                <button
                                    type="button"
                                    @click="copyEmail"
                                    class="cursor-pointer rounded p-1 text-neutral-400 transition-colors hover:bg-neutral-200 hover:text-neutral-900 dark:hover:bg-neutral-800 dark:hover:text-white"
                                    title="Copy email"
                                >
                                    <component :is="copied ? Check : Copy" class="h-3.5 w-3.5" />
                                </button>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div
                            class="flex items-center justify-between gap-2 border-b-2 border-neutral-200/60 py-1.5 dark:border-neutral-800/60"
                        >
                            <div
                                class="flex items-center gap-2 text-neutral-600 dark:text-neutral-400"
                            >
                                <Smartphone className="w-3.5 h-3.5" />
                                <span>{{ t('phone') }}</span>
                            </div>
                            <a
                                :href="`tel:${data.contact.phone}`"
                                class="font-medium text-neutral-900 hover:underline dark:text-neutral-100"
                            >
                                {{ data.contact.phone }}
                            </a>
                        </div>

                        <!-- Location -->
                        <div
                            class="flex items-center justify-between gap-2 border-b-2 border-neutral-200/60 py-1.5 dark:border-neutral-800/60"
                        >
                            <div
                                class="flex items-center gap-2 text-neutral-600 dark:text-neutral-400"
                            >
                                <MapPin className="w-3.5 h-3.5" />
                                <span>{{ t('location') }}</span>
                            </div>
                            <span
                                class="font-medium text-neutral-900 dark:text-neutral-100"
                            >
                                {{ data.contact.location }}
                            </span>
                        </div>

                        <!-- LinkedIn -->
                        <div
                            class="flex items-center justify-between gap-2 py-1.5"
                        >
                            <div
                                class="flex items-center gap-2 text-neutral-600 dark:text-neutral-400"
                            >
                                <LinkedinIcon className="w-3.5 h-3.5" />
                                <span>{{ t('linkedin') }}</span>
                            </div>
                            <a
                                :href="data.contact.linkedin"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1 font-medium text-neutral-900 hover:underline dark:text-neutral-100"
                            >
                                <span>{{ data.contact.github }}</span>
                                <GithubIcon
                                    name="external-link"
                                    className="w-3 h-3 text-neutral-400"
                                />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
