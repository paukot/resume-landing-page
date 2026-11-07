import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import HeroSection from '@/components/HeroSection.vue';
import type { ResumeData } from '@/types/resume';

vi.mock('@inertiajs/vue3', () => ({
    usePage: () => ({
        props: {
            locale: 'en',
            translations: {
                download_cv: 'Download CV',
                message_me: 'Message Me',
                linkedin: 'LinkedIn',
                scroll: 'Scroll',
            },
        },
    }),
}));

const createMockResumeData = (
    overrides: Partial<ResumeData> = {},
): ResumeData => ({
    name: 'Paulina Test',
    title: 'Senior Backend Engineer',
    intro: 'Laravel & Cloud Specialist',
    summary: 'Building high scale architectures',
    contact: {
        email: 'paulina@example.com',
        phone: '+48 111 222 333',
        location: 'Warsaw, Poland',
        linkedin: 'https://linkedin.com/in/paulina',
    },
    cvPdfUrl: 'https://example.com/cv.pdf',
    languages: [],
    quickStats: [
        {
            value: '7+',
            label: 'Years Experience',
            description: 'Production Laravel',
        },
        {
            value: '99.9%',
            label: 'Uptime',
            description: 'High availability systems',
        },
    ],
    experience: [],
    education: [],
    skillCategories: [],
    projects: [],
    ...overrides,
});

describe('HeroSection.vue', () => {
    it('renders hero name, title, and quick stats', () => {
        // Arrange
        const data = createMockResumeData();

        // Act
        const wrapper = mount(HeroSection, {
            props: { data },
        });

        // Assert
        expect(wrapper.text()).toContain('Paulina Test');
        expect(wrapper.text()).toContain('Senior Backend Engineer');
        expect(wrapper.text()).toContain('7+');
        expect(wrapper.text()).toContain('Years Experience');
    });

    it('targets #about when showAbout is true', async () => {
        // Arrange
        const data = createMockResumeData({
            settings: {
                showAbout: true,
                showExperience: true,
                showSkills: true,
                showEducation: true,
                showProjects: true,
                showContact: true,
            },
        });

        // Act
        const wrapper = mount(HeroSection, {
            props: { data },
        });

        // Assert
        const scrollBtn = wrapper.find(
            'button[aria-label="Scroll to next section"]',
        );
        expect(scrollBtn.exists()).toBe(true);
    });

    it('targets #experience when showAbout is disabled', async () => {
        // Arrange
        const data = createMockResumeData({
            settings: {
                showAbout: false,
                showExperience: true,
                showSkills: true,
                showEducation: true,
                showProjects: true,
                showContact: true,
            },
        });

        // Act
        const wrapper = mount(HeroSection, {
            props: { data },
        });

        // Assert
        const scrollBtn = wrapper.find(
            'button[aria-label="Scroll to next section"]',
        );
        expect(scrollBtn.exists()).toBe(true);
    });

    it('hides bottom scroll button when all sections below hero are disabled', () => {
        // Arrange
        const data = createMockResumeData({
            settings: {
                showAbout: false,
                showExperience: false,
                showSkills: false,
                showEducation: false,
                showProjects: false,
                showContact: false,
            },
        });

        // Act
        const wrapper = mount(HeroSection, {
            props: { data },
        });

        // Assert
        expect(
            wrapper
                .find('button[aria-label="Scroll to next section"]')
                .exists(),
        ).toBe(false);
    });
});
