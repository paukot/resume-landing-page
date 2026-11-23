import { onUnmounted, ref } from 'vue';

/**
 * Copies text to the clipboard and exposes a short-lived `copied` flag for UI feedback.
 */
export function useClipboard(resetAfterMs = 2000) {
    const copied = ref(false);
    let resetTimer: ReturnType<typeof setTimeout> | null = null;

    const copy = async (text: string): Promise<boolean> => {
        try {
            await navigator.clipboard.writeText(text);
        } catch {
            return false;
        }

        copied.value = true;
        if (resetTimer) {
            clearTimeout(resetTimer);
        }
        resetTimer = setTimeout(() => {
            copied.value = false;
        }, resetAfterMs);

        return true;
    };

    onUnmounted(() => {
        if (resetTimer) {
            clearTimeout(resetTimer);
        }
    });

    return { copied, copy };
}
