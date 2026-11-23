import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import HeroDots from '@/components/HeroDots.vue';

describe('HeroDots.vue', () => {
    it('renders a decorative canvas hidden from assistive tech', () => {
        // Arrange & Act
        const wrapper = mount(HeroDots);

        // Assert
        const canvas = wrapper.find('canvas');
        expect(canvas.exists()).toBe(true);
        expect(canvas.attributes('aria-hidden')).toBe('true');
        expect(canvas.classes()).toContain('pointer-events-none');
    });

    it('mounts and unmounts without errors when no 2D context is available', () => {
        // Arrange
        const getContext = vi
            .spyOn(HTMLCanvasElement.prototype, 'getContext')
            .mockReturnValue(null);

        // Act & Assert
        const wrapper = mount(HeroDots);
        expect(() => wrapper.unmount()).not.toThrow();

        getContext.mockRestore();
    });
});
