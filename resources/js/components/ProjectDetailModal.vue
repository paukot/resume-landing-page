<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted, computed } from 'vue';
import type { ProjectItem } from '@/types/resume';
import { useTranslations } from '@/composables/useTranslations';
import GithubIcon from '@/components/icons/GithubIcon.vue';
import {
    X,
    ExternalLink,
    ChevronLeft,
    ChevronRight,
    Sparkles,
    ZoomIn,
    Maximize2,
} from 'lucide-vue-next';

const props = defineProps<{
    project: ProjectItem | null;
    isOpen: boolean;
    projects?: ProjectItem[];
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'navigate', project: ProjectItem): void;
}>();

const { t } = useTranslations();

// Gallery active preview index
const activeGalleryIndex = ref<number>(0);

// Full-screen image lightbox state
const isLightboxOpen = ref<boolean>(false);
const activeLightboxIndex = ref<number>(0);

const openLightbox = (index?: number) => {
    activeLightboxIndex.value =
        typeof index === 'number' ? index : activeGalleryIndex.value;
    isLightboxOpen.value = true;
};

const closeLightbox = () => {
    isLightboxOpen.value = false;
};

const lightboxHasPrev = computed(() => activeLightboxIndex.value > 0);
const lightboxHasNext = computed(
    () => activeLightboxIndex.value < allImages.value.length - 1,
);

const lightboxPrev = () => {
    if (lightboxHasPrev.value) {
        activeLightboxIndex.value--;
    } else if (allImages.value.length > 1) {
        activeLightboxIndex.value = allImages.value.length - 1;
    }
    activeGalleryIndex.value = activeLightboxIndex.value;
};

const lightboxNext = () => {
    if (lightboxHasNext.value) {
        activeLightboxIndex.value++;
    } else if (allImages.value.length > 1) {
        activeLightboxIndex.value = 0;
    }
    activeGalleryIndex.value = activeLightboxIndex.value;
};

// Reset gallery & lightbox when project changes or closes
watch(
    () => props.project?.id,
    () => {
        activeGalleryIndex.value = 0;
        activeLightboxIndex.value = 0;
        isLightboxOpen.value = false;
    },
);

watch(
    () => props.isOpen,
    (isOpen) => {
        if (!isOpen) {
            isLightboxOpen.value = false;
        }
    },
);

const currentIndex = computed(() => {
    if (!props.project || !props.projects) return -1;
    return props.projects.findIndex((p) => p.id === props.project?.id);
});

const hasPrev = computed(() => currentIndex.value > 0);
const hasNext = computed(() => {
    if (!props.projects) return false;
    return (
        currentIndex.value >= 0 &&
        currentIndex.value < props.projects.length - 1
    );
});

const goToPrev = () => {
    if (hasPrev.value && props.projects) {
        emit('navigate', props.projects[currentIndex.value - 1]);
    }
};

const goToNext = () => {
    if (hasNext.value && props.projects) {
        emit('navigate', props.projects[currentIndex.value + 1]);
    }
};

// Keyboard listener for ESC, ArrowLeft, ArrowRight
const handleKeyDown = (e: KeyboardEvent) => {
    if (!props.isOpen) return;

    if (e.key === 'Escape') {
        if (isLightboxOpen.value) {
            closeLightbox();
        } else {
            emit('close');
        }
    } else if (e.key === 'ArrowLeft') {
        if (isLightboxOpen.value) {
            lightboxPrev();
        } else {
            goToPrev();
        }
    } else if (e.key === 'ArrowRight') {
        if (isLightboxOpen.value) {
            lightboxNext();
        } else {
            goToNext();
        }
    }
};

onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
});

// All displayable images (Cover + Gallery)
const allImages = computed(() => {
    const list: string[] = [];
    if (props.project?.coverImageUrl) {
        list.push(props.project.coverImageUrl);
    }
    if (props.project?.galleryUrls && props.project.galleryUrls.length > 0) {
        for (const url of props.project.galleryUrls) {
            if (!list.includes(url)) {
                list.push(url);
            }
        }
    }
    return list;
});
</script>

<template>
    <div
        v-if="isOpen && project"
        role="dialog"
        aria-modal="true"
        :aria-labelledby="`modal-title-${project.id}`"
        class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/70 p-3 backdrop-blur-sm sm:p-6"
    >
        <!-- Backdrop click to close -->
        <div class="fixed inset-0" @click="emit('close')" />

        <!-- Modal Dialog Box -->
        <div
            class="relative z-10 flex max-h-[92vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-2xl dark:border-neutral-800 dark:bg-neutral-900"
            @click.stop
        >
            <!-- Header Toolbar -->
            <div
                class="flex items-center justify-between border-b border-neutral-200/80 bg-neutral-50/90 px-6 py-4 backdrop-blur-md dark:border-neutral-800 dark:bg-neutral-950/90"
            >
                <div class="flex items-center gap-2 overflow-hidden">
                    <span
                        class="rounded-sm bg-neutral-200 px-2 py-0.5 font-mono text-[10px] font-semibold tracking-wider text-neutral-700 uppercase dark:bg-neutral-800 dark:text-neutral-300"
                    >
                        {{ project.category }}
                    </span>
                    <span
                        v-if="project.featured"
                        class="inline-flex items-center gap-1 rounded-sm bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-900 dark:bg-amber-950/60 dark:text-amber-300"
                    >
                        <Sparkles class="h-3 w-3" />
                        {{ t('featured') }}
                    </span>
                </div>

                <!-- Navigation & Close Buttons -->
                <div class="flex items-center gap-1.5">
                    <div
                        v-if="projects && projects.length > 1"
                        class="mr-2 flex items-center gap-1"
                    >
                        <button
                            type="button"
                            :disabled="!hasPrev"
                            @click="goToPrev"
                            title="Previous project (←)"
                            class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg border border-neutral-200 bg-white text-neutral-700 transition-colors hover:bg-neutral-100 disabled:pointer-events-none disabled:opacity-30 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
                        >
                            <ChevronLeft class="h-4 w-4" />
                        </button>
                        <button
                            type="button"
                            :disabled="!hasNext"
                            @click="goToNext"
                            title="Next project (→)"
                            class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg border border-neutral-200 bg-white text-neutral-700 transition-colors hover:bg-neutral-100 disabled:pointer-events-none disabled:opacity-30 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
                        >
                            <ChevronRight class="h-4 w-4" />
                        </button>
                    </div>

                    <button
                        type="button"
                        @click="emit('close')"
                        title="Close (Esc)"
                        class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-neutral-500 transition-colors hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-white"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>
            </div>

            <!-- Modal Content (Scrollable) -->
            <div class="overflow-y-auto px-6 py-6 sm:px-8">
                <!-- Project Title & Tagline -->
                <div class="mb-6">
                    <h2
                        :id="`modal-title-${project.id}`"
                        class="text-2xl font-bold tracking-tight text-neutral-950 sm:text-3xl dark:text-white"
                    >
                        {{ project.title }}
                    </h2>
                    <p
                        class="mt-1 text-sm font-medium text-neutral-600 dark:text-neutral-400"
                    >
                        {{ project.tagline }}
                    </p>
                </div>

                <!-- Visual Media Gallery Showcase -->
                <div v-if="allImages.length > 0" class="mb-8">
                    <!-- Main Image Preview (Click to open full view) -->
                    <div
                        role="button"
                        tabindex="0"
                        @click="openLightbox(activeGalleryIndex)"
                        @keydown.enter="openLightbox(activeGalleryIndex)"
                        @keydown.space.prevent="
                            openLightbox(activeGalleryIndex)
                        "
                        :title="t('view_full_image')"
                        class="group relative aspect-video w-full cursor-zoom-in overflow-hidden rounded-xl border border-neutral-200/80 bg-neutral-100 transition-colors focus:ring-2 focus:ring-neutral-400 focus:outline-hidden dark:border-neutral-800 dark:bg-neutral-950 dark:focus:ring-neutral-600"
                    >
                        <img
                            :src="allImages[activeGalleryIndex]"
                            :alt="project.title"
                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.02]"
                        />

                        <!-- Hover Overlay Indicator -->
                        <div
                            class="pointer-events-none absolute inset-0 flex items-center justify-center bg-black/25 opacity-0 transition-opacity duration-200 group-hover:opacity-100"
                        >
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full bg-black/80 px-3.5 py-1.5 text-xs font-medium text-white shadow-lg backdrop-blur-xs"
                            >
                                <ZoomIn class="h-3.5 w-3.5" />
                                <span>{{ t('view_full_image') }}</span>
                            </span>
                        </div>

                        <!-- Corner expand badge -->
                        <div
                            class="pointer-events-none absolute right-2.5 bottom-2.5"
                        >
                            <span
                                class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-black/60 text-white backdrop-blur-xs transition-colors group-hover:bg-black/80"
                            >
                                <Maximize2 class="h-3.5 w-3.5" />
                            </span>
                        </div>
                    </div>

                    <!-- Thumbnails Strip (if multiple images) -->
                    <div
                        v-if="allImages.length > 1"
                        class="mt-3 flex items-center gap-2 overflow-x-auto pb-1"
                    >
                        <button
                            v-for="(imgUrl, idx) in allImages"
                            :key="idx"
                            type="button"
                            @click="activeGalleryIndex = idx"
                            class="relative h-16 w-24 shrink-0 cursor-pointer overflow-hidden rounded-lg border-2 transition-all duration-150"
                            :class="[
                                activeGalleryIndex === idx
                                    ? 'border-neutral-900 ring-2 ring-neutral-900/20 dark:border-white dark:ring-white/20'
                                    : 'border-transparent opacity-60 hover:opacity-100',
                            ]"
                        >
                            <img
                                :src="imgUrl"
                                :alt="`Screenshot ${idx + 1}`"
                                class="h-full w-full object-cover"
                            />
                        </button>
                    </div>
                </div>

                <!-- Full-Screen Image Lightbox (Mounted to body) -->
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
                            v-if="
                                isOpen && isLightboxOpen && allImages.length > 0
                            "
                            role="dialog"
                            aria-modal="true"
                            :aria-label="t('view_full_image')"
                            class="fixed inset-0 z-[100] flex flex-col items-center justify-between bg-black/95 p-4 backdrop-blur-md select-none sm:p-6"
                            @click="closeLightbox"
                        >
                            <!-- Lightbox Header Toolbar -->
                            <div
                                class="flex w-full max-w-7xl items-center justify-between text-white"
                                @click.stop
                            >
                                <div
                                    class="flex items-center gap-3 overflow-hidden"
                                >
                                    <span
                                        class="truncate text-sm font-semibold tracking-wide text-white/90"
                                    >
                                        {{ project?.title }}
                                    </span>
                                    <span
                                        v-if="allImages.length > 1"
                                        class="shrink-0 rounded-full bg-white/15 px-2.5 py-0.5 font-mono text-xs text-white/90"
                                    >
                                        {{ activeLightboxIndex + 1 }} /
                                        {{ allImages.length }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        @click="closeLightbox"
                                        :title="`${t('close_full_view')} (Esc)`"
                                        class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus:ring-2 focus:ring-white/50 focus:outline-hidden"
                                    >
                                        <X class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>

                            <!-- Main Full Image Showcase (Natural aspect ratio, uncropped) -->
                            <div
                                class="relative flex w-full max-w-7xl flex-1 items-center justify-center py-4"
                                @click.stop
                            >
                                <!-- Previous Image Button -->
                                <button
                                    v-if="allImages.length > 1"
                                    type="button"
                                    @click="lightboxPrev"
                                    :title="`${t('previous_image')} (←)`"
                                    class="absolute left-2 z-10 inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-full bg-black/60 text-white backdrop-blur-xs transition-all hover:scale-105 hover:bg-black/90 focus:ring-2 focus:ring-white/50 focus:outline-hidden active:scale-95 sm:left-4"
                                >
                                    <ChevronLeft class="h-6 w-6" />
                                </button>

                                <!-- Full Uncropped Image -->
                                <div
                                    class="flex max-h-[80vh] max-w-[90vw] items-center justify-center"
                                >
                                    <img
                                        :src="allImages[activeLightboxIndex]"
                                        :alt="`${project?.title} full size preview`"
                                        class="max-h-[80vh] max-w-full cursor-zoom-out rounded-lg object-contain shadow-2xl transition-all duration-200"
                                        @click="closeLightbox"
                                        :title="t('close_full_view')"
                                    />
                                </div>

                                <!-- Next Image Button -->
                                <button
                                    v-if="allImages.length > 1"
                                    type="button"
                                    @click="lightboxNext"
                                    :title="`${t('next_image')} (→)`"
                                    class="absolute right-2 z-10 inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-full bg-black/60 text-white backdrop-blur-xs transition-all hover:scale-105 hover:bg-black/90 focus:ring-2 focus:ring-white/50 focus:outline-hidden active:scale-95 sm:right-4"
                                >
                                    <ChevronRight class="h-6 w-6" />
                                </button>
                            </div>

                            <!-- Bottom Thumbnails Strip (if multiple images) -->
                            <div
                                v-if="allImages.length > 1"
                                class="flex w-full max-w-2xl items-center justify-center gap-2 overflow-x-auto py-2"
                                @click.stop
                            >
                                <button
                                    v-for="(imgUrl, idx) in allImages"
                                    :key="idx"
                                    type="button"
                                    @click="
                                        activeLightboxIndex = idx;
                                        activeGalleryIndex = idx;
                                    "
                                    class="relative h-12 w-16 shrink-0 cursor-pointer overflow-hidden rounded-md border-2 transition-all"
                                    :class="[
                                        activeLightboxIndex === idx
                                            ? 'scale-105 border-white opacity-100 ring-2 ring-white/50'
                                            : 'border-transparent opacity-40 hover:opacity-90',
                                    ]"
                                >
                                    <img
                                        :src="imgUrl"
                                        :alt="`Thumbnail ${idx + 1}`"
                                        class="h-full w-full object-cover"
                                    />
                                </button>
                            </div>
                            <div v-else class="h-6" />
                        </div>
                    </Transition>
                </Teleport>

                <!-- Key Project Metadata & Action Links Bar -->
                <div
                    class="mb-8 flex flex-wrap items-center justify-between gap-4 rounded-xl border border-neutral-200/80 bg-neutral-50/80 p-4 dark:border-neutral-800 dark:bg-neutral-950/60"
                >
                    <!-- Tech Stack Badges -->
                    <div class="flex flex-wrap items-center gap-1.5">
                        <span
                            class="mr-1 text-xs font-semibold text-neutral-500 uppercase dark:text-neutral-400"
                        >
                            Stack:
                        </span>
                        <span
                            v-for="tech in project.technologies"
                            :key="tech"
                            class="rounded border border-neutral-200 bg-white px-2 py-0.5 font-mono text-[11px] text-neutral-700 shadow-2xs dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300"
                        >
                            {{ tech }}
                        </span>
                    </div>

                    <!-- Action Link Buttons -->
                    <div class="flex items-center gap-2">
                        <a
                            v-if="project.liveUrl"
                            :href="project.liveUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-neutral-900 px-3.5 py-2 text-xs font-medium text-white transition-colors hover:bg-neutral-800 dark:bg-white dark:text-neutral-950 dark:hover:bg-neutral-100"
                        >
                            <ExternalLink class="h-3.5 w-3.5" />
                            <span>Live Demo</span>
                        </a>

                        <a
                            v-if="project.githubUrl"
                            :href="project.githubUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-neutral-300 bg-white px-3.5 py-2 text-xs font-medium text-neutral-700 transition-colors hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700"
                        >
                            <GithubIcon class="h-3.5 w-3.5" />
                            <span>{{ t('code') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Summary Overview -->
                <div class="mb-6">
                    <h3
                        class="mb-2 text-xs font-semibold tracking-wider text-neutral-500 uppercase dark:text-neutral-400"
                    >
                        Overview
                    </h3>
                    <p
                        class="text-sm leading-relaxed text-neutral-700 dark:text-neutral-300"
                    >
                        {{ project.description }}
                    </p>
                </div>

                <!-- Highlights Bullet Points -->
                <div
                    v-if="project.highlights && project.highlights.length > 0"
                    class="mb-8"
                >
                    <h3
                        class="mb-3 text-xs font-semibold tracking-wider text-neutral-500 uppercase dark:text-neutral-400"
                    >
                        Key Architecture & Engineering Highlights
                    </h3>
                    <ul class="space-y-2">
                        <li
                            v-for="(hl, i) in project.highlights"
                            :key="i"
                            class="flex items-start gap-2 text-xs text-neutral-700 dark:text-neutral-300"
                        >
                            <span
                                class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-neutral-400 dark:bg-neutral-500"
                            />
                            <span class="leading-relaxed">{{ hl }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Detailed Rich Editor Content (Story / Case Study) -->
                <div
                    v-if="project.content"
                    class="mb-8 border-t border-neutral-200/80 pt-6 dark:border-neutral-800"
                >
                    <h3
                        class="mb-4 text-xs font-semibold tracking-wider text-neutral-500 uppercase dark:text-neutral-400"
                    >
                        Detailed Case Study
                    </h3>
                    <div
                        class="project-prose text-sm leading-relaxed text-neutral-800 dark:text-neutral-200"
                        v-html="project.content"
                    />
                </div>
            </div>

            <!-- Footer Toolbar -->
            <div
                class="flex items-center justify-end border-t border-neutral-200 bg-neutral-50 px-6 py-3.5 dark:border-neutral-800 dark:bg-neutral-950"
            >
                <button
                    type="button"
                    @click="emit('close')"
                    class="rounded-lg bg-neutral-200 px-4 py-2 text-xs font-medium text-neutral-800 transition-colors hover:bg-neutral-300 dark:bg-neutral-800 dark:text-neutral-200 dark:hover:bg-neutral-700"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Rich Text Typography for project detailed case study */
.project-prose :deep(h1),
.project-prose :deep(h2),
.project-prose :deep(h3),
.project-prose :deep(h4) {
    font-weight: 700;
    color: inherit;
    margin-top: 1.25rem;
    margin-bottom: 0.5rem;
}

.project-prose :deep(h2) {
    font-size: 1.25rem;
}

.project-prose :deep(h3) {
    font-size: 1.1rem;
}

.project-prose :deep(p) {
    margin-bottom: 0.85rem;
    line-height: 1.7;
}

.project-prose :deep(strong) {
    font-weight: 700;
}

.project-prose :deep(ul) {
    list-style-type: disc;
    margin-left: 1.25rem;
    margin-bottom: 1rem;
}

.project-prose :deep(ol) {
    list-style-type: decimal;
    margin-left: 1.25rem;
    margin-bottom: 1rem;
}

.project-prose :deep(li) {
    margin-bottom: 0.25rem;
}

.project-prose :deep(blockquote) {
    border-left: 3px solid #737373;
    padding-left: 1rem;
    font-style: italic;
    margin-top: 1rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.project-prose :deep(pre),
.project-prose :deep(code) {
    font-family:
        ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: 0.85em;
    background-color: rgba(120, 120, 120, 0.12);
    border-radius: 0.25rem;
    padding: 0.15rem 0.35rem;
}

.project-prose :deep(pre) {
    padding: 0.75rem 1rem;
    overflow-x: auto;
    margin-top: 0.75rem;
    margin-bottom: 0.75rem;
}

.project-prose :deep(a) {
    color: #3b82f6;
    text-decoration: underline;
}

.project-prose :deep(a:hover) {
    color: #1d4ed8;
}
</style>
