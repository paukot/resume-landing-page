import { onMounted, onUnmounted, ref, watch, type Ref } from 'vue';

/**
 * Tracks which of the given section ids is currently in the reading area of the viewport.
 * Returns 'hero' while none of them is.
 */
export function useActiveSection(sectionIds: Ref<string[]>) {
    const activeSection = ref('hero');
    const intersecting = new Set<string>();
    let observer: IntersectionObserver | null = null;

    const pickActive = () => {
        activeSection.value =
            sectionIds.value.find((id) => intersecting.has(id)) ?? 'hero';
    };

    const observe = () => {
        observer?.disconnect();
        intersecting.clear();

        observer = new IntersectionObserver(
            (entries) => {
                for (const entry of entries) {
                    if (entry.isIntersecting) {
                        intersecting.add(entry.target.id);
                    } else {
                        intersecting.delete(entry.target.id);
                    }
                }
                pickActive();
            },
            { rootMargin: '-35% 0px -55% 0px' },
        );

        for (const id of sectionIds.value) {
            const element = document.getElementById(id);
            if (element) {
                observer.observe(element);
            }
        }
    };

    onMounted(observe);
    watch(sectionIds, observe);
    onUnmounted(() => observer?.disconnect());

    return { activeSection };
}
