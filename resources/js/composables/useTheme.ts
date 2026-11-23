import { onMounted, ref } from 'vue';

const THEME_STORAGE_KEY = 'theme';

function readStoredTheme(): string | null {
    try {
        return localStorage.getItem(THEME_STORAGE_KEY);
    } catch {
        return null;
    }
}

function storeTheme(theme: 'dark' | 'light'): void {
    try {
        localStorage.setItem(THEME_STORAGE_KEY, theme);
    } catch {
        // Storage can be unavailable (private mode); the theme still applies for this visit.
    }
}

/**
 * Light / dark theme state, synced with the `dark` class on <html> and persisted per visitor.
 */
export function useTheme() {
    const isDark = ref(false);

    const applyTheme = (dark: boolean) => {
        isDark.value = dark;
        document.documentElement.classList.toggle('dark', dark);
    };

    const toggleTheme = () => {
        applyTheme(!isDark.value);
        storeTheme(isDark.value ? 'dark' : 'light');
    };

    onMounted(() => {
        const savedTheme = readStoredTheme();
        const prefersDark = window.matchMedia(
            '(prefers-color-scheme: dark)',
        ).matches;

        applyTheme(savedTheme === 'dark' || (!savedTheme && prefersDark));
    });

    return { isDark, toggleTheme };
}
