<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted, computed } from 'vue';
import type { ProjectItem } from '@/types/resume';
import { useTranslations } from '@/composables/useTranslations';
import GithubIcon from '@/components/icons/GithubIcon.vue';
import ImageLightbox from '@/components/ImageLightbox.vue';
import ThumbnailCarousel from '@/components/ThumbnailCarousel.vue';
import TechBadge from '@/components/ui/TechBadge.vue';
import {
    X,
    ExternalLink,
    ChevronLeft,
    ChevronRight,
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

const selectLightboxImage = (index: number) => {
    activeLightboxIndex.value = index;
    activeGalleryIndex.value = index;
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

// Top preview image slider navigation
const prevGalleryImage = () => {
    if (allImages.value.length <= 1) return;
    if (activeGalleryIndex.value > 0) {
        activeGalleryIndex.value--;
    } else {
        activeGalleryIndex.value = allImages.value.length - 1;
    }
};

const nextGalleryImage = () => {
    if (allImages.value.length <= 1) return;
    if (activeGalleryIndex.value < allImages.value.length - 1) {
        activeGalleryIndex.value++;
    } else {
        activeGalleryIndex.value = 0;
    }
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
        class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/60 p-3 backdrop-blur-md sm:p-6"
    >
        <!-- Backdrop click to close -->
        <div class="fixed inset-0" @click="emit('close')" />

        <!-- Modal Dialog Box -->
        <div
            class="relative z-10 flex max-h-[92vh] w-full max-w-6xl flex-col overflow-hidden rounded-3xl border border-zinc-300 bg-white shadow-2xl dark:border-white/10 dark:bg-zinc-900"
            @click.stop
        >
            <!-- Header Toolbar -->
            <div
                class="flex items-center justify-between border-b border-zinc-300 bg-zinc-100 px-6 py-4 backdrop-blur-md dark:border-white/10 dark:bg-zinc-800"
            >
                <div class="flex items-center gap-2 overflow-hidden">
                    <span
                        class="rounded-full bg-zinc-100 px-2.5 py-0.5 text-[10px] font-bold tracking-wider text-lavender-700 uppercase dark:bg-white/5 dark:text-lavender-300"
                    >
                        {{ project.category }}
                    </span>
                    <span
                        v-if="project.featured"
                        class="inline-flex items-center gap-1 rounded-full bg-lavender-600 px-2.5 py-0.5 text-[10px] font-bold text-white dark:bg-lavender-300 dark:text-lavender-950"
                    >
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
                            :title="`${t('previous_project')} (←)`"
                            :aria-label="t('previous_project')"
                            class="btn btn-secondary h-8 w-8 disabled:pointer-events-none disabled:opacity-30"
                        >
                            <ChevronLeft class="h-4 w-4" />
                        </button>
                        <button
                            type="button"
                            :disabled="!hasNext"
                            @click="goToNext"
                            :title="`${t('next_project')} (→)`"
                            :aria-label="t('next_project')"
                            class="btn btn-secondary h-8 w-8 disabled:pointer-events-none disabled:opacity-30"
                        >
                            <ChevronRight class="h-4 w-4" />
                        </button>
                    </div>

                    <button
                        type="button"
                        @click="emit('close')"
                        :title="`${t('close')} (Esc)`"
                        :aria-label="t('close')"
                        class="btn btn-ghost h-8 w-8"
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
                        class="text-3xl font-bold tracking-tight text-zinc-950 sm:text-4xl dark:text-white"
                    >
                        {{ project.title }}
                    </h2>
                    <p
                        v-if="project.tagline"
                        class="mt-2 text-base font-medium text-zinc-700 dark:text-zinc-400"
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
                        class="group relative aspect-video max-h-120 w-full cursor-zoom-in overflow-hidden rounded-xl border border-zinc-300 bg-zinc-100 transition-colors focus:ring-2 focus:ring-lavender-500 focus:outline-hidden dark:border-white/10 dark:bg-zinc-800 dark:focus:ring-lavender-300"
                    >
                        <img
                            :src="allImages[activeGalleryIndex]"
                            :alt="project.title"
                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.01]"
                        />

                        <!-- Previous Image Button (<) on Top Image -->
                        <button
                            v-if="allImages.length > 1"
                            type="button"
                            @click.stop="prevGalleryImage"
                            :title="`${t('previous_image')} (←)`"
                            class="absolute top-1/2 left-3 z-20 inline-flex h-10 w-10 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full bg-black/60 text-white backdrop-blur-xs transition-all hover:scale-110 hover:bg-black/85 focus:ring-2 focus:ring-white/50 focus:outline-hidden active:scale-95 sm:h-11 sm:w-11"
                        >
                            <ChevronLeft class="h-6 w-6" />
                        </button>

                        <!-- Next Image Button (>) on Top Image -->
                        <button
                            v-if="allImages.length > 1"
                            type="button"
                            @click.stop="nextGalleryImage"
                            :title="`${t('next_image')} (→)`"
                            class="absolute top-1/2 right-3 z-20 inline-flex h-10 w-10 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full bg-black/60 text-white backdrop-blur-xs transition-all hover:scale-110 hover:bg-black/85 focus:ring-2 focus:ring-white/50 focus:outline-hidden active:scale-95 sm:h-11 sm:w-11"
                        >
                            <ChevronRight class="h-6 w-6" />
                        </button>

                        <!-- Image Counter Badge -->
                        <div
                            v-if="allImages.length > 1"
                            class="pointer-events-none absolute bottom-3 left-3 z-10"
                        >
                            <span
                                class="inline-flex items-center rounded-full bg-black/65 px-3 py-1 font-mono text-xs font-medium text-white shadow-md backdrop-blur-xs"
                            >
                                {{ activeGalleryIndex + 1 }} /
                                {{ allImages.length }}
                            </span>
                        </div>

                        <!-- Hover Overlay Indicator -->
                        <div
                            class="pointer-events-none absolute inset-0 flex items-center justify-center bg-black/20 opacity-0 transition-opacity duration-200 group-hover:opacity-100"
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
                            class="pointer-events-none absolute right-3 bottom-3 z-10"
                        >
                            <span
                                class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-black/60 text-white backdrop-blur-xs transition-colors group-hover:bg-black/80"
                            >
                                <Maximize2 class="h-3.5 w-3.5" />
                            </span>
                        </div>
                    </div>

                    <ThumbnailCarousel
                        v-if="allImages.length > 1"
                        class="mt-4"
                        :images="allImages"
                        :active-index="activeGalleryIndex"
                        @select="activeGalleryIndex = $event"
                    />
                </div>

                <!-- Key Project Metadata & Action Links Bar -->
                <div
                    v-if="
                        project.technologies?.length ||
                        project.liveUrl ||
                        project.githubUrl
                    "
                    class="mb-8 flex flex-wrap items-center justify-between gap-4 rounded-xl border border-zinc-300 bg-zinc-100 p-4 dark:border-white/10 dark:bg-zinc-800"
                >
                    <!-- Tech Stack Badges -->
                    <div
                        v-if="project.technologies?.length"
                        class="flex flex-wrap items-center gap-1.5"
                    >
                        <span
                            class="mr-1 text-xs font-semibold text-zinc-600 uppercase dark:text-zinc-400"
                        >
                            {{ t('stack') }}
                        </span>
                        <TechBadge
                            v-for="tech in project.technologies"
                            :key="tech"
                        >
                            {{ tech }}
                        </TechBadge>
                    </div>

                    <!-- Action Link Buttons -->
                    <div class="flex items-center gap-2">
                        <a
                            v-if="project.liveUrl"
                            :href="project.liveUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-primary px-4 py-2 text-xs"
                        >
                            <ExternalLink class="h-3.5 w-3.5" />
                            <span>{{ t('live_demo') }}</span>
                        </a>

                        <a
                            v-if="project.githubUrl"
                            :href="project.githubUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-secondary px-4 py-2 text-xs"
                        >
                            <GithubIcon class="h-3.5 w-3.5" />
                            <span>{{ t('code') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Summary Overview -->
                <div v-if="project.description" class="mb-6">
                    <h3
                        class="mb-2 text-xs font-semibold tracking-wider text-zinc-600 uppercase dark:text-zinc-400"
                    >
                        {{ t('overview') }}
                    </h3>
                    <p
                        class="text-sm leading-relaxed text-zinc-700 dark:text-zinc-400"
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
                        class="mb-3 text-xs font-semibold tracking-wider text-zinc-600 uppercase dark:text-zinc-400"
                    >
                        {{ t('highlights') }}
                    </h3>
                    <ul class="space-y-2">
                        <li
                            v-for="(hl, i) in project.highlights"
                            :key="i"
                            class="flex items-start gap-2.5 text-sm text-zinc-700 dark:text-zinc-400"
                        >
                            <span
                                class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand"
                            />
                            <span class="leading-relaxed">{{ hl }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Detailed Rich Editor Content (Story / Case Study) -->
                <div
                    v-if="project.content"
                    class="mb-8 border-t border-zinc-300 pt-6 dark:border-white/10"
                >
                    <h3
                        class="mb-4 text-xs font-semibold tracking-wider text-zinc-600 uppercase dark:text-zinc-400"
                    >
                        {{ t('case_study') }}
                    </h3>
                    <div
                        class="project-prose text-sm leading-relaxed text-zinc-950 dark:text-white"
                        v-html="project.content"
                    />
                </div>
            </div>

            <ImageLightbox
                :is-open="isOpen && isLightboxOpen"
                :images="allImages"
                :active-index="activeLightboxIndex"
                :title="project.title"
                @close="closeLightbox"
                @prev="lightboxPrev"
                @next="lightboxNext"
                @select="selectLightboxImage"
            />

            <!-- Footer Toolbar -->
            <div
                class="flex items-center justify-end border-t border-zinc-300 bg-zinc-100 px-6 py-3.5 dark:border-white/10 dark:bg-zinc-800"
            >
                <button
                    type="button"
                    @click="emit('close')"
                    class="btn btn-secondary px-5 py-2 text-xs"
                >
                    {{ t('close') }}
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
    border-left: 3px solid var(--color-lavender-400);
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
    color: var(--color-lavender-700);
    text-decoration: underline;
}

.project-prose :deep(a:hover) {
    color: var(--color-lavender-900);
}

.dark .project-prose :deep(a) {
    color: var(--color-lavender-300);
}

.dark .project-prose :deep(a:hover) {
    color: var(--color-lavender-200);
}

.project-prose :deep(table) {
    width: 100%;
    margin-top: 1rem;
    margin-bottom: 1.5rem;
    border-collapse: collapse;
    font-size: 0.85rem;
    overflow-x: auto;
    display: block;
}

.project-prose :deep(th),
.project-prose :deep(td) {
    padding: 0.5rem 0.75rem;
    border: 1px solid rgba(120, 120, 120, 0.25);
    text-align: left;
}

.project-prose :deep(th) {
    background-color: rgba(120, 120, 120, 0.1);
    font-weight: 600;
}

.project-prose :deep(tr:nth-child(even)) {
    background-color: rgba(120, 120, 120, 0.04);
}
</style>
