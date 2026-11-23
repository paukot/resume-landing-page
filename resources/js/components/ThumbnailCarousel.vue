<script setup lang="ts">
import { nextTick, onMounted, onUnmounted, ref, watch } from 'vue';
import { useTranslations } from '@/composables/useTranslations';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

const props = withDefaults(
    defineProps<{
        images: string[];
        activeIndex: number;
        tone?: 'light' | 'dark';
        size?: 'md' | 'sm';
    }>(),
    { tone: 'light', size: 'md' },
);

const emit = defineEmits<{
    (e: 'select', index: number): void;
}>();

const { t } = useTranslations();

const trackRef = ref<HTMLElement | null>(null);
const thumbRefs = ref<HTMLElement[]>([]);
const canScrollPrev = ref(false);
const canScrollNext = ref(false);

const updateScrollState = () => {
    const track = trackRef.value;
    if (!track) {
        return;
    }
    canScrollPrev.value = track.scrollLeft > 4;
    canScrollNext.value =
        track.scrollLeft + track.clientWidth < track.scrollWidth - 4;
};

const scrollByPage = (direction: -1 | 1) => {
    const track = trackRef.value;
    if (!track) {
        return;
    }
    track.scrollBy({
        left: direction * track.clientWidth * 0.8,
        behavior: 'smooth',
    });
};

/** Keeps the selected thumbnail centred without scrolling the page itself. */
const centreThumbnail = (index: number) => {
    const track = trackRef.value;
    const thumb = thumbRefs.value[index];
    if (!track || !thumb || typeof track.scrollTo !== 'function') {
        return;
    }
    track.scrollTo({
        left: thumb.offsetLeft - (track.clientWidth - thumb.clientWidth) / 2,
        behavior: 'smooth',
    });
};

// Mouse drag-to-scroll; touch devices keep native swiping.
let dragStartX = 0;
let dragStartScroll = 0;
let isPointerDown = false;
const hasDragged = ref(false);

const onPointerDown = (event: PointerEvent) => {
    if (event.pointerType !== 'mouse' || !trackRef.value) {
        return;
    }
    isPointerDown = true;
    hasDragged.value = false;
    dragStartX = event.clientX;
    dragStartScroll = trackRef.value.scrollLeft;
};

const onPointerMove = (event: PointerEvent) => {
    if (!isPointerDown || !trackRef.value) {
        return;
    }
    const distance = event.clientX - dragStartX;
    if (Math.abs(distance) > 5) {
        hasDragged.value = true;
    }
    trackRef.value.scrollLeft = dragStartScroll - distance;
};

const endDrag = () => {
    isPointerDown = false;
};

const selectThumbnail = (index: number) => {
    if (hasDragged.value) {
        hasDragged.value = false;
        return;
    }
    emit('select', index);
};

watch(
    () => props.activeIndex,
    (index) => centreThumbnail(index),
);

watch(
    () => props.images,
    async () => {
        thumbRefs.value = [];
        await nextTick();
        updateScrollState();
    },
);

onMounted(() => {
    updateScrollState();
    window.addEventListener('resize', updateScrollState, { passive: true });
    window.addEventListener('pointerup', endDrag);
});

onUnmounted(() => {
    window.removeEventListener('resize', updateScrollState);
    window.removeEventListener('pointerup', endDrag);
});
</script>

<template>
    <div class="relative">
        <div
            ref="trackRef"
            class="no-scrollbar flex snap-x snap-mandatory gap-3 overflow-x-auto py-1.5 select-none"
            :class="{ 'cursor-grab active:cursor-grabbing': images.length > 1 }"
            @scroll.passive="updateScrollState"
            @pointerdown="onPointerDown"
            @pointermove="onPointerMove"
            @pointerleave="endDrag"
            @dragstart.prevent
        >
            <button
                v-for="(imageUrl, index) in images"
                :key="imageUrl"
                :ref="
                    (el) => {
                        if (el) thumbRefs[index] = el as HTMLElement;
                    }
                "
                type="button"
                :aria-label="t('thumbnail', { number: index + 1 })"
                :aria-current="activeIndex === index ? 'true' : undefined"
                @click="selectThumbnail(index)"
                class="group/thumb relative aspect-video shrink-0 snap-start overflow-hidden rounded-xl border-2 transition-all duration-200"
                :class="[
                    size === 'md'
                        ? 'w-36 sm:w-44 lg:w-56'
                        : 'w-24 sm:w-28 lg:w-32',
                    activeIndex === index
                        ? tone === 'dark'
                            ? 'border-white opacity-100'
                            : 'border-lavender-500 shadow-md dark:border-lavender-300'
                        : 'border-transparent opacity-55 hover:opacity-100',
                ]"
            >
                <img
                    :src="imageUrl"
                    :alt="t('thumbnail', { number: index + 1 })"
                    draggable="false"
                    loading="lazy"
                    class="pointer-events-none h-full w-full object-cover transition-transform duration-300 group-hover/thumb:scale-105"
                />
            </button>
        </div>

        <!-- Edge fades + arrows, only when there is more to see -->
        <template v-for="side in ['prev', 'next'] as const" :key="side">
            <div
                v-if="side === 'prev' ? canScrollPrev : canScrollNext"
                class="pointer-events-none absolute inset-y-0 flex w-20 items-center"
                :class="[
                    side === 'prev'
                        ? 'left-0 justify-start bg-linear-to-r'
                        : 'right-0 justify-end bg-linear-to-l',
                    tone === 'dark'
                        ? 'from-black/90 to-transparent'
                        : 'from-white to-transparent dark:from-zinc-900',
                ]"
            >
                <button
                    type="button"
                    :aria-label="
                        side === 'prev' ? t('previous_image') : t('next_image')
                    "
                    @click="scrollByPage(side === 'prev' ? -1 : 1)"
                    class="pointer-events-auto inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-full shadow-md transition-transform hover:scale-105 active:scale-95"
                    :class="
                        tone === 'dark'
                            ? 'bg-white/15 text-white backdrop-blur-sm hover:bg-white/25'
                            : 'btn btn-secondary'
                    "
                >
                    <ChevronLeft v-if="side === 'prev'" class="h-4 w-4" />
                    <ChevronRight v-else class="h-4 w-4" />
                </button>
            </div>
        </template>
    </div>
</template>
