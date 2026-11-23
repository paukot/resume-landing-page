<script setup lang="ts">
import { computed, type Component } from 'vue';
import type { ResumeData } from '@/types/resume';
import { useTranslations } from '@/composables/useTranslations';
import { useClipboard } from '@/composables/useClipboard';
import { displayUrl } from '@/lib/utils';
import SectionShell from '@/components/ui/SectionShell.vue';
import LinkedinIcon from '@/components/icons/LinkedinIcon.vue';
import GithubIcon from '@/components/icons/GithubIcon.vue';
import {
    Check,
    Copy,
    Languages,
    Mail,
    MapPin,
    Smartphone,
} from 'lucide-vue-next';

const props = defineProps<{
    data: ResumeData;
    index?: number;
}>();

const emit = defineEmits<{
    (e: 'copy-email', email: string): void;
}>();

const { t } = useTranslations();
const { copied, copy } = useClipboard();

interface ContactRow {
    key: string;
    label: string;
    value: string;
    href?: string;
    external?: boolean;
    icon: Component;
}

const contactRows = computed<ContactRow[]>(() => {
    const contact = props.data.contact ?? {};
    const rows: ContactRow[] = [];

    if (contact.email) {
        rows.push({
            key: 'email',
            icon: Mail,
            label: t('email'),
            value: contact.email,
            href: `mailto:${contact.email}`,
        });
    }
    if (contact.phone) {
        rows.push({
            key: 'phone',
            icon: Smartphone,
            label: t('phone'),
            value: contact.phone,
            href: `tel:${contact.phone.replace(/\s+/g, '')}`,
        });
    }
    if (contact.location) {
        rows.push({
            key: 'location',
            icon: MapPin,
            label: t('location'),
            value: contact.location,
        });
    }
    if (contact.linkedin) {
        rows.push({
            key: 'linkedin',
            icon: LinkedinIcon,
            label: t('linkedin'),
            value: displayUrl(contact.linkedin),
            href: contact.linkedin,
            external: true,
        });
    }
    if (contact.github) {
        rows.push({
            key: 'github',
            icon: GithubIcon,
            label: t('github'),
            value: displayUrl(contact.github),
            href: contact.github,
            external: true,
        });
    }

    return rows;
});

const hasSideColumn = computed(
    () =>
        contactRows.value.length > 0 || (props.data.languages?.length ?? 0) > 0,
);

const copyEmail = async () => {
    const email = props.data.contact?.email;
    if (email && (await copy(email))) {
        emit('copy-email', email);
    }
};
</script>

<template>
    <SectionShell
        id="about"
        :index="index"
        :eyebrow="t('nav.about')"
        :title="t('section.about_title')"
    >
        <div
            class="grid grid-cols-1 items-start gap-10"
            :class="{ 'lg:grid-cols-12': hasSideColumn }"
        >
            <div v-if="data.summary" v-reveal class="lg:col-span-7">
                <p
                    class="text-lg leading-relaxed whitespace-pre-line text-zinc-700 sm:text-xl sm:leading-relaxed dark:text-zinc-300"
                >
                    {{ data.summary }}
                </p>
            </div>

            <div
                v-if="hasSideColumn"
                v-reveal="1"
                class="flex flex-col gap-6"
                :class="data.summary ? 'lg:col-span-5' : 'lg:col-span-12'"
            >
                <div
                    v-if="contactRows.length"
                    class="card-glow rounded-3xl border border-zinc-300 bg-white p-2 shadow-card dark:border-white/10 dark:bg-zinc-900"
                >
                    <ul class="divide-y divide-zinc-300 dark:divide-white/5">
                        <li
                            v-for="row in contactRows"
                            :key="row.key"
                            class="flex items-center gap-3 px-3 py-3"
                        >
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-zinc-100 text-zinc-700 dark:bg-white/5 dark:text-zinc-300"
                            >
                                <component :is="row.icon" class="h-4 w-4" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <div
                                    class="text-[11px] font-semibold tracking-wider text-zinc-600 uppercase"
                                >
                                    {{ row.label }}
                                </div>
                                <a
                                    v-if="row.href"
                                    :href="row.href"
                                    :target="
                                        row.external ? '_blank' : undefined
                                    "
                                    :rel="
                                        row.external
                                            ? 'noopener noreferrer'
                                            : undefined
                                    "
                                    class="block truncate text-sm font-medium text-zinc-900 transition-colors hover:text-cyan-600 dark:text-zinc-100 dark:hover:text-cyan-400"
                                >
                                    {{ row.value }}
                                </a>
                                <span
                                    v-else
                                    class="block truncate text-sm font-medium text-zinc-900 dark:text-zinc-100"
                                >
                                    {{ row.value }}
                                </span>
                            </div>
                            <button
                                v-if="row.key === 'email'"
                                type="button"
                                @click="copyEmail"
                                :title="t('copy_email')"
                                :aria-label="t('copy_email')"
                                class="cursor-pointer rounded-lg p-2 text-zinc-500 transition-colors hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white"
                            >
                                <component
                                    :is="copied ? Check : Copy"
                                    class="h-4 w-4"
                                    :class="{ 'text-emerald-500': copied }"
                                />
                            </button>
                        </li>
                    </ul>
                </div>

                <div v-if="data.languages?.length">
                    <div
                        class="mb-3 flex items-center gap-2 text-xs font-semibold tracking-wider text-zinc-600 uppercase"
                    >
                        <Languages class="h-3.5 w-3.5" />
                        {{ t('languages') }}
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="lang in data.languages"
                            :key="lang.name"
                            class="inline-flex items-center gap-2 rounded-full border border-zinc-300 bg-white px-3.5 py-1.5 text-sm dark:border-white/10 dark:bg-zinc-900"
                        >
                            <span
                                class="font-semibold text-zinc-900 dark:text-white"
                            >
                                {{ lang.name }}
                            </span>
                            <span
                                v-if="lang.level"
                                class="text-zinc-600 dark:text-zinc-400"
                            >
                                {{ lang.level }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </SectionShell>
</template>
