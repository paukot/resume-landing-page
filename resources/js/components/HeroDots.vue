<script setup lang="ts">
import { onBeforeUnmount, onMounted, useTemplateRef } from 'vue';
import { DotField } from '@/lib/dotField';

const canvas = useTemplateRef<HTMLCanvasElement>('canvas');
let field: DotField | null = null;

onMounted(() => {
    if (!canvas.value) {
        return;
    }

    try {
        field = new DotField(canvas.value);
    } catch {
        // No 2D canvas (very old browser, test environment): the hero simply has no dots.
        field = null;
    }
});

onBeforeUnmount(() => {
    field?.destroy();
    field = null;
});
</script>

<template>
    <canvas
        ref="canvas"
        aria-hidden="true"
        class="hero-dots pointer-events-none absolute inset-0 h-full w-full"
    />
</template>

<style scoped>
.hero-dots {
    mask-image: radial-gradient(
        ellipse 85% 75% at 50% 45%,
        #000 35%,
        transparent 100%
    );
}
</style>
