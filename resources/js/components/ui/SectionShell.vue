<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    id: string;
    eyebrow: string;
    index?: number;
    title?: string;
    subtitle?: string;
}>();

/** Every other section sits on a slightly raised band so the page has rhythm without patterns. */
const isBanded = computed(() => (props.index ?? 1) % 2 === 0);
</script>

<template>
    <section
        :id="id"
        class="relative py-20 sm:py-28"
        :class="isBanded ? 'border-y border-zinc-300 bg-zinc-50 shadow-[inset_0_1px_0_white] dark:border-white/5 dark:bg-zinc-900/40' : ''"
    >
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <header
                v-if="eyebrow || title || subtitle"
                v-reveal
                class="mb-10 max-w-2xl sm:mb-14"
            >
                <p
                    class="flex items-center gap-3 text-xs font-semibold tracking-[0.2em] uppercase"
                >
                    <span class="bg-brand h-px w-8" aria-hidden="true" />
                    <span class="text-gradient">{{ eyebrow }}</span>
                </p>
                <h2
                    v-if="title"
                    class="mt-4 text-3xl font-bold tracking-tight text-zinc-950 sm:text-4xl dark:text-white"
                >
                    {{ title }}
                </h2>
                <p
                    v-if="subtitle"
                    class="mt-3 text-base leading-relaxed text-zinc-700 dark:text-zinc-400"
                >
                    {{ subtitle }}
                </p>
            </header>

            <slot />
        </div>
    </section>
</template>
