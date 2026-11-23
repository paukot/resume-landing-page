<script setup lang="ts">
withDefaults(
    defineProps<{
        size?: 'sm' | 'md';
    }>(),
    { size: 'sm' },
);
</script>

<template>
    <span
        class="inline-flex items-center rounded-full border border-zinc-300 bg-zinc-100 font-medium text-zinc-800 dark:border-white/10 dark:bg-white/5 dark:text-zinc-300"
        :class="size === 'sm' ? 'px-2.5 py-0.5 text-[11px]' : 'px-3 py-1 text-xs'"
    >
        <slot />
    </span>
</template>
