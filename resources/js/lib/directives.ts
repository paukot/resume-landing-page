import type { Directive } from 'vue';

const revealObservers = new WeakMap<HTMLElement, IntersectionObserver>();

/**
 * Fades an element in once it scrolls into view. Pass a number to stagger it (index × 80ms).
 */
export const reveal: Directive<HTMLElement, number | undefined> = {
    mounted(el, binding) {
        el.classList.add('reveal');
        if (typeof binding.value === 'number') {
            el.style.setProperty(
                '--reveal-delay',
                `${Math.min(binding.value, 6) * 80}ms`,
            );
        }

        if (typeof IntersectionObserver === 'undefined') {
            el.classList.add('is-revealed');
            return;
        }

        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry?.isIntersecting) {
                    el.classList.add('is-revealed');
                    observer.disconnect();
                    revealObservers.delete(el);
                }
            },
            { rootMargin: '0px 0px -8% 0px' },
        );
        observer.observe(el);
        revealObservers.set(el, observer);
    },
    unmounted(el) {
        revealObservers.get(el)?.disconnect();
        revealObservers.delete(el);
    },
};

const spotlightHandlers = new WeakMap<HTMLElement, (e: PointerEvent) => void>();

/**
 * Feeds the pointer position into `--x` / `--y` so the `.spotlight` glow follows the cursor.
 */
export const spotlight: Directive<HTMLElement> = {
    mounted(el) {
        el.classList.add('spotlight');
        const handler = (event: PointerEvent) => {
            const rect = el.getBoundingClientRect();
            el.style.setProperty('--x', `${event.clientX - rect.left}px`);
            el.style.setProperty('--y', `${event.clientY - rect.top}px`);
        };
        el.addEventListener('pointermove', handler, { passive: true });
        spotlightHandlers.set(el, handler);
    },
    unmounted(el) {
        const handler = spotlightHandlers.get(el);
        if (handler) {
            el.removeEventListener('pointermove', handler);
        }
        spotlightHandlers.delete(el);
    },
};
