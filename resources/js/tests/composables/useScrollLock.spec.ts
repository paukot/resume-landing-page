import { describe, it, expect, beforeEach, afterEach } from 'vitest';
import { ref } from 'vue';
import { useScrollLock } from '@/composables/useScrollLock';

describe('useScrollLock', () => {
    beforeEach(() => {
        document.body.style.overflow = '';
    });

    afterEach(() => {
        document.body.style.overflow = '';
    });

    it('sets body overflow to hidden when locked is true', () => {
        // Arrange
        const isLocked = ref(true);

        // Act
        useScrollLock(isLocked);

        // Assert
        expect(document.body.style.overflow).toBe('hidden');
    });

    it('leaves body overflow empty when locked is false initially', () => {
        // Arrange
        const isLocked = ref(false);

        // Act
        useScrollLock(isLocked);

        // Assert
        expect(document.body.style.overflow).toBe('');
    });

    it('restores body overflow when locked transitions from true to false', async () => {
        // Arrange
        const isLocked = ref(true);
        useScrollLock(isLocked);
        expect(document.body.style.overflow).toBe('hidden');

        // Act
        isLocked.value = false;
        await new Promise((resolve) => setTimeout(resolve, 0));

        // Assert
        expect(document.body.style.overflow).toBe('');
    });
});
