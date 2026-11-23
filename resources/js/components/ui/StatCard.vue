<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';
import { useCountUp } from '@/composables/useCountUp';
import type { QuickStat } from '@/types/resume';

const props = defineProps<{
    stat: QuickStat;
}>();

const cardRef = ref<HTMLElement | null>(null);
const { display, start } = useCountUp(props.stat.value);
let observer: IntersectionObserver | null = null;

onMounted(() => {
    if (!cardRef.value) {
        return;
    }
    observer = new IntersectionObserver(([entry]) => {
        if (entry?.isIntersecting) {
            start();
            observer?.disconnect();
        }
    });
    observer.observe(cardRef.value);
});

onUnmounted(() => observer?.disconnect());
</script>

<template>
    <div
        ref="cardRef"
        class="card-glow rounded-2xl border border-zinc-300 bg-white p-5 shadow-card dark:border-white/10 dark:bg-zinc-900"
    >
        <div
            class="text-gradient text-3xl font-bold tracking-tight tabular-nums sm:text-4xl"
        >
            <span aria-hidden="true">{{ display }}</span>
            <span class="sr-only">{{ stat.value }}</span>
        </div>
        <div
            class="mt-1 text-sm font-semibold text-zinc-900 dark:text-zinc-100"
        >
            {{ stat.label }}
        </div>
        <div
            v-if="stat.description"
            class="mt-0.5 text-xs leading-snug text-zinc-600 dark:text-zinc-400"
        >
            {{ stat.description }}
        </div>
    </div>
</template>
