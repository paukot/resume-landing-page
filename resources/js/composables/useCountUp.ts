import { onUnmounted, ref } from 'vue';

export interface ParsedStatValue {
    prefix: string;
    number: number;
    decimals: number;
    suffix: string;
}

/**
 * Splits a stat like "10+", "99.9%" or "~5 years" into its numeric part and surrounding text.
 * Returns null when the value has no number to animate.
 */
export function parseStatValue(value: string): ParsedStatValue | null {
    const match = value.match(/^(\D*?)(\d+(?:[.,]\d+)?)(.*)$/s);
    if (!match) {
        return null;
    }

    const [, prefix, numeric, suffix] = match;
    const normalised = numeric.replace(',', '.');
    const decimals = normalised.includes('.')
        ? normalised.split('.')[1].length
        : 0;

    return { prefix, number: Number(normalised), decimals, suffix };
}

export function formatStatValue(parsed: ParsedStatValue, current: number) {
    return `${parsed.prefix}${current.toFixed(parsed.decimals)}${parsed.suffix}`;
}

/**
 * Animates a stat value from zero up to its final number once `start()` is called.
 * Shows the final value until then; non-numeric values are shown as-is.
 */
export function useCountUp(value: string, durationMs = 1400) {
    const parsed = parseStatValue(value);
    // Start on the real value so it is never stuck at zero if the animation does not run.
    const display = ref(value);
    let frameId: number | null = null;
    let hasStarted = false;

    const start = () => {
        if (!parsed || hasStarted) {
            return;
        }
        hasStarted = true;

        const reducedMotion = window.matchMedia(
            '(prefers-reduced-motion: reduce)',
        ).matches;
        if (reducedMotion) {
            return;
        }

        display.value = formatStatValue(parsed, 0);

        const startedAt = performance.now();
        const tick = (now: number) => {
            const progress = Math.min((now - startedAt) / durationMs, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            display.value =
                progress < 1
                    ? formatStatValue(parsed, parsed.number * eased)
                    : value;
            if (progress < 1) {
                frameId = requestAnimationFrame(tick);
            }
        };
        frameId = requestAnimationFrame(tick);
    };

    onUnmounted(() => {
        if (frameId) {
            cancelAnimationFrame(frameId);
        }
    });

    return { display, start };
}
