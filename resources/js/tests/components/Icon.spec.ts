import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import Icon from '@/components/icons/Icon.vue';

describe('Icon.vue', () => {
    it('renders the corresponding icon by lowercase name', () => {
        // Arrange & Act
        const wrapper = mount(Icon, {
            props: { name: 'github' },
        });

        // Assert
        expect(wrapper.find('svg').exists()).toBe(true);
        expect(wrapper.attributes('aria-hidden')).toBe('true');
    });

    it('falls back to default icon for unrecognized names', () => {
        // Arrange & Act
        const wrapper = mount(Icon, {
            props: { name: 'unknown-icon-name-xyz' },
        });

        // Assert
        expect(wrapper.find('svg').exists()).toBe(true);
    });

    it('applies custom class through fallthrough attributes', () => {
        // Arrange & Act
        const wrapper = mount(Icon, {
            props: { name: 'mail' },
            attrs: { class: 'custom-icon-class text-emerald-500' },
        });

        // Assert
        expect(wrapper.classes()).toContain('custom-icon-class');
        expect(wrapper.classes()).toContain('text-emerald-500');
    });

    it('falls back to default w-4 h-4 class when no class or className is provided', () => {
        // Arrange & Act
        const wrapper = mount(Icon, {
            props: { name: 'sun' },
        });

        // Assert
        expect(wrapper.classes()).toContain('w-4');
        expect(wrapper.classes()).toContain('h-4');
    });
});
