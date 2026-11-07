import { describe, it, expect, vi } from 'vitest';
import { useTranslations } from '@/composables/useTranslations';

vi.mock('@inertiajs/vue3', () => ({
    usePage: () => ({
        props: {
            locale: 'en',
            translations: {
                nav: {
                    about: 'About',
                    projects: 'Projects',
                },
                welcome: 'Hello :name',
                poland: 'Poland',
            },
        },
    }),
}));

describe('useTranslations', () => {
    it('retrieves nested translation key', () => {
        // Arrange
        const { t } = useTranslations();

        // Act
        const result = t('nav.about');

        // Assert
        expect(result).toBe('About');
    });

    it('interpolates parameters in translation strings', () => {
        // Arrange
        const { t } = useTranslations();

        // Act
        const result = t('welcome', { name: 'Paulina' });

        // Assert
        expect(result).toBe('Hello Paulina');
    });

    it('returns the key itself as fallback when translation does not exist', () => {
        // Arrange
        const { t } = useTranslations();

        // Act
        const result = t('non.existent.key');

        // Assert
        expect(result).toBe('non.existent.key');
    });

    it('exposes current locale', () => {
        // Arrange & Act
        const { locale } = useTranslations();

        // Assert
        expect(locale.value).toBe('en');
    });
});
