<script setup lang="ts">
import { useTranslations } from '@/composables/useTranslations';
import ThumbnailCarousel from '@/components/ThumbnailCarousel.vue';
import { ChevronLeft, ChevronRight, X } from 'lucide-vue-next';

defineProps<{
    isOpen: boolean;
    images: string[];
    activeIndex: number;
    title: string;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'prev'): void;
    (e: 'next'): void;
    (e: 'select', index: number): void;
}>();

const { t } = useTranslations();
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="isOpen && images.length > 0"
                role="dialog"
                aria-modal="true"
                :aria-label="t('view_full_image')"
                class="fixed inset-0 z-[100] flex flex-col items-center justify-between bg-black/95 p-4 backdrop-blur-md select-none sm:p-6"
                @click="emit('close')"
            >
                <div
                    class="flex w-full max-w-7xl items-center justify-between text-white"
                    @click.stop
                >
                    <div class="flex items-center gap-3 overflow-hidden">
                        <span
                            class="truncate text-sm font-semibold tracking-wide text-white/90"
                        >
                            {{ title }}
                        </span>
                        <span
                            v-if="images.length > 1"
                            class="shrink-0 rounded-full bg-white/15 px-2.5 py-0.5 text-xs text-white/90 tabular-nums"
                        >
                            {{ activeIndex + 1 }} / {{ images.length }}
                        </span>
                    </div>
                    <button
                        type="button"
                        @click="emit('close')"
                        :title="`${t('close_full_view')} (Esc)`"
                        :aria-label="t('close_full_view')"
                        class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus:ring-2 focus:ring-white/50 focus:outline-hidden"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <div
                    class="relative flex w-full max-w-7xl flex-1 items-center justify-center py-4"
                    @click.stop
                >
                    <button
                        v-if="images.length > 1"
                        type="button"
                        @click="emit('prev')"
                        :title="`${t('previous_image')} (←)`"
                        :aria-label="t('previous_image')"
                        class="absolute left-2 z-10 inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-xs transition-all hover:scale-105 hover:bg-white/20 focus:ring-2 focus:ring-white/50 focus:outline-hidden active:scale-95 sm:left-4"
                    >
                        <ChevronLeft class="h-6 w-6" />
                    </button>

                    <img
                        :src="images[activeIndex]"
                        :alt="title"
                        :title="t('close_full_view')"
                        class="max-h-[80vh] max-w-full cursor-zoom-out rounded-xl object-contain shadow-2xl"
                        @click="emit('close')"
                    />

                    <button
                        v-if="images.length > 1"
                        type="button"
                        @click="emit('next')"
                        :title="`${t('next_image')} (→)`"
                        :aria-label="t('next_image')"
                        class="absolute right-2 z-10 inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-xs transition-all hover:scale-105 hover:bg-white/20 focus:ring-2 focus:ring-white/50 focus:outline-hidden active:scale-95 sm:right-4"
                    >
                        <ChevronRight class="h-6 w-6" />
                    </button>
                </div>

                <div
                    v-if="images.length > 1"
                    class="w-full max-w-4xl py-2"
                    @click.stop
                >
                    <ThumbnailCarousel
                        :images="images"
                        :active-index="activeIndex"
                        tone="dark"
                        size="sm"
                        @select="emit('select', $event)"
                    />
                </div>
                <div v-else class="h-6" />
            </div>
        </Transition>
    </Teleport>
</template>
