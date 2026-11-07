import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import Navbar from '@/components/Navbar.vue';
import type { SectionSettings } from '@/types/resume';

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
                    projects: 'Projects',
                    contact: 'Contact',
                },
                download_cv: 'Download CV',
                english: 'English',
                polish: 'Polish',
            },
        },
    }),
}));

describe('Navbar.vue', () => {
    it('renders the brand name with tracking-tight style', () => {
        // Arrange & Act
        const wrapper = mount(Navbar, {
            props: {
                name: 'Paulina Dev',
                isDark: false,
                cvPdfUrl: 'https://example.com/cv.pdf',
            },
        });

        // Assert
        const brand = wrapper.find('span.tracking-tight');
        expect(brand.exists()).toBe(true);
        expect(brand.text()).toBe('Paulina Dev');
    });

    it('renders navigation links based on visible section settings', () => {
        // Arrange
        const settings: SectionSettings = {
            showAbout: true,
            showExperience: false,
            showSkills: true,
            showEducation: false,
            showProjects: true,
            showContact: false,
        };

        // Act
        const wrapper = mount(Navbar, {
            props: {
                name: 'Paulina Dev',
                isDark: false,
                cvPdfUrl: 'https://example.com/cv.pdf',
                settings,
            },
        });

        // Assert
        const navText = wrapper.find('nav').text();
        expect(navText).toContain('About');
        expect(navText).toContain('Skills');
        expect(navText).toContain('Projects');
        expect(navText).not.toContain('Experience');
        expect(navText).not.toContain('Education');
        expect(navText).not.toContain('Contact');
    });

    it('emits toggle-theme when theme toggle button is clicked', async () => {
        // Arrange
        const wrapper = mount(Navbar, {
            props: {
                name: 'Paulina Dev',
                isDark: false,
                cvPdfUrl: 'https://example.com/cv.pdf',
            },
        });

        // Act
        const themeBtn = wrapper.find('button[aria-label="Toggle theme"]');
        await themeBtn.trigger('click');

        // Assert
        expect(wrapper.emitted('toggle-theme')).toHaveLength(1);
    });

    it('toggles mobile menu open and closed', async () => {
        // Arrange
        const wrapper = mount(Navbar, {
            props: {
                name: 'Paulina Dev',
                isDark: false,
                cvPdfUrl: 'https://example.com/cv.pdf',
            },
        });

        // Act - open
        const mobileToggle = wrapper.find(
            'button[aria-label="Toggle mobile menu"]',
        );
        expect(mobileToggle.attributes('aria-expanded')).toBe('false');
        await mobileToggle.trigger('click');

        // Assert - opened
        expect(mobileToggle.attributes('aria-expanded')).toBe('true');
        expect(wrapper.find('#mobile-nav-menu').exists()).toBe(true);

        // Act - close
        await mobileToggle.trigger('click');
        expect(mobileToggle.attributes('aria-expanded')).toBe('false');
        expect(wrapper.find('#mobile-nav-menu').exists()).toBe(false);
    });
});
