import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import Navbar from '@/components/Navbar.vue';
import type { SectionId } from '@/lib/sections';

vi.mock('@inertiajs/vue3', () => ({
    Link: {
        template: '<a :href="href"><slot /></a>',
        props: ['href'],
    },
    usePage: () => ({
        props: {
            locale: 'en',
            translations: {
                nav: {
                    about: 'About',
                    experience: 'Experience',
                    skills: 'Skills',
                    education: 'Education',
                    projects: 'Portfolio',
                    contact: 'Contact',
                },
                download_cv: 'Download CV',
                english: 'English',
                polish: 'Polish',
                toggle_theme: 'Toggle theme',
                toggle_menu: 'Toggle menu',
            },
        },
    }),
}));

const mountNavbar = (
    overrides: Partial<{ cvPdfUrl: string; sections: SectionId[] }> = {},
) =>
    mount(Navbar, {
        props: {
            name: 'Paulina Dev',
            isDark: false,
            cvPdfUrl: 'https://example.com/cv.pdf',
            sections: ['about', 'projects', 'experience'],
            ...overrides,
        },
    });

describe('Navbar.vue', () => {
    it('renders the brand name', () => {
        // Arrange & Act
        const wrapper = mountNavbar();

        // Assert
        expect(wrapper.find('header').text()).toContain('Paulina Dev');
    });

    it('renders one navigation link per visible section, in the given order', () => {
        // Arrange & Act
        const wrapper = mountNavbar({
            sections: ['about', 'projects', 'skills'],
        });

        // Assert
        const links = wrapper.findAll('nav a');
        expect(links.map((link) => link.text())).toEqual([
            'About',
            'Portfolio',
            'Skills',
        ]);
        expect(links.map((link) => link.attributes('href'))).toEqual([
            '#about',
            '#projects',
            '#skills',
        ]);
    });

    it('emits toggle-theme when theme toggle button is clicked', async () => {
        // Arrange
        const wrapper = mountNavbar();

        // Act
        await wrapper
            .find('button[aria-label="Toggle theme"]')
            .trigger('click');

        // Assert
        expect(wrapper.emitted('toggle-theme')).toHaveLength(1);
    });

    it('toggles mobile menu open and closed', async () => {
        // Arrange
        const wrapper = mountNavbar();
        const mobileToggle = wrapper.find('button[aria-label="Toggle menu"]');
        expect(mobileToggle.attributes('aria-expanded')).toBe('false');

        // Act - open
        await mobileToggle.trigger('click');

        // Assert - opened
        expect(mobileToggle.attributes('aria-expanded')).toBe('true');
        expect(wrapper.find('#mobile-nav-menu').exists()).toBe(true);

        // Act - close
        await mobileToggle.trigger('click');

        // Assert - closed
        expect(mobileToggle.attributes('aria-expanded')).toBe('false');
        expect(wrapper.find('#mobile-nav-menu').exists()).toBe(false);
    });

    it('hides every CV download link when no PDF is uploaded', async () => {
        // Arrange
        const wrapper = mountNavbar({ cvPdfUrl: '' });

        // Act
        await wrapper.find('button[aria-label="Toggle menu"]').trigger('click');

        // Assert
        expect(wrapper.find('#mobile-nav-menu').exists()).toBe(true);
        expect(wrapper.text()).not.toContain('Download CV');
    });
});
