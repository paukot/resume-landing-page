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
                scroll_next: 'Scroll to next section',
                github: 'GitHub',
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
    languages: [{ name: 'English', level: 'C1' }],
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

const allSectionsOn = {
    showAbout: true,
    showExperience: true,
    showSkills: true,
    showEducation: true,
    showProjects: true,
    showContact: true,
};

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

    it('scrolls to about when it is the first visible section', async () => {
        // Arrange
        const about = document.createElement('section');
        about.id = 'about';
        document.body.appendChild(about);
        const scrollSpy = vi.spyOn(about, 'scrollIntoView');
        const wrapper = mount(HeroSection, {
            props: { data: createMockResumeData() },
        });

        // Act
        await wrapper
            .find('button[aria-label="Scroll to next section"]')
            .trigger('click');

        // Assert
        expect(scrollSpy).toHaveBeenCalled();
        about.remove();
    });

    it('skips disabled and empty sections when choosing the scroll target', async () => {
        // Arrange
        const experience = document.createElement('section');
        experience.id = 'experience';
        document.body.appendChild(experience);
        const scrollSpy = vi.spyOn(experience, 'scrollIntoView');
        const data = createMockResumeData({
            settings: { ...allSectionsOn, showAbout: false },
            projects: [],
            experience: [
                {
                    id: 1,
                    role: 'Engineer',
                    company: 'Acme',
                    period: '2020 - 2024',
                    highlights: [],
                    technologies: [],
                },
            ],
        });
        const wrapper = mount(HeroSection, { props: { data } });

        // Act
        await wrapper
            .find('button[aria-label="Scroll to next section"]')
            .trigger('click');

        // Assert
        expect(scrollSpy).toHaveBeenCalled();
        experience.remove();
    });

    it('hides the scroll button when no section below the hero is visible', () => {
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
        const wrapper = mount(HeroSection, { props: { data } });

        // Assert
        expect(
            wrapper
                .find('button[aria-label="Scroll to next section"]')
                .exists(),
        ).toBe(false);
    });

    it('hides the CV, LinkedIn and GitHub buttons when they are not set', () => {
        // Arrange
        const data = createMockResumeData({
            cvPdfUrl: '',
            contact: { email: 'paulina@example.com' },
        });

        // Act
        const wrapper = mount(HeroSection, { props: { data } });

        // Assert
        expect(wrapper.text()).not.toContain('Download CV');
        expect(wrapper.find('a[aria-label="LinkedIn"]').exists()).toBe(false);
        expect(wrapper.find('a[aria-label="GitHub"]').exists()).toBe(false);
        expect(wrapper.text()).toContain('Message Me');
    });
});
