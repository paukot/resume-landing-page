import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import ProjectsSection from '@/components/ProjectsSection.vue';
import type { ProjectItem } from '@/types/resume';

vi.mock('@inertiajs/vue3', () => ({
    usePage: () => ({
        props: {
            locale: 'en',
            translations: {
                section: {
                    portfolio: 'Portfolio',
                },
                open_project: 'Open project: :title',
                featured: 'Featured',
                code: 'Code',
            },
        },
    }),
}));

const mockProjects: ProjectItem[] = [
    {
        id: 1,
        title: 'Project Canvas',
        tagline: 'High performance rendering',
        category: 'Frontend & WebGL',
        description:
            'A cutting edge canvas application with real-time effects.',
        highlights: ['60fps smooth render loop', 'Zero memory leaks'],
        technologies: ['Vue 3', 'TypeScript', 'Canvas API'],
        coverImageUrl: 'https://example.com/cover.jpg',
        featured: true,
    },
    {
        id: 2,
        title: 'Cloud Orchestrator',
        tagline: 'Microservices manager',
        category: 'DevOps & Backend',
        description: 'Auto-scaling infrastructure coordinator.',
        highlights: [],
        technologies: ['Go', 'Docker', 'Kubernetes'],
        coverImageUrl: null,
        featured: false,
    },
];

describe('ProjectsSection.vue', () => {
    it('renders the section title and projects', () => {
        // Arrange & Act
        const wrapper = mount(ProjectsSection, {
            props: { projects: mockProjects },
        });

        // Assert
        expect(wrapper.text()).toContain('Portfolio');
        expect(wrapper.text()).toContain('Project Canvas');
        expect(wrapper.text()).toContain('Cloud Orchestrator');
    });

    it('shows the featured project first as the spotlight card', () => {
        // Arrange
        const projects = [mockProjects[1], mockProjects[0]];

        // Act
        const wrapper = mount(ProjectsSection, {
            props: { projects },
        });

        // Assert
        const cards = wrapper.findAll('article');
        expect(cards).toHaveLength(2);
        expect(cards[0].text()).toContain('Project Canvas');
    });

    it('stretches a regular project left alone on the last row to full width', () => {
        // Arrange & Act
        const wrapper = mount(ProjectsSection, {
            props: { projects: mockProjects },
        });

        // Assert
        const [featured, regular] = wrapper.findAll('article');
        expect(featured.classes()).toContain('md:col-span-2');
        expect(regular.classes()).toContain('md:col-span-2');
    });

    it('keeps paired regular projects at half width', () => {
        // Arrange
        const thirdProject = { ...mockProjects[1], id: 3, title: 'Third' };

        // Act
        const wrapper = mount(ProjectsSection, {
            props: { projects: [...mockProjects, thirdProject] },
        });

        // Assert
        const [, second, third] = wrapper.findAll('article');
        expect(second.classes()).not.toContain('md:col-span-2');
        expect(third.classes()).not.toContain('md:col-span-2');
    });

    it('renders cover image with lazy loading when available', () => {
        // Arrange & Act
        const wrapper = mount(ProjectsSection, {
            props: { projects: mockProjects },
        });

        // Assert
        const img = wrapper.find('img[src="https://example.com/cover.jpg"]');
        expect(img.exists()).toBe(true);
        expect(img.attributes('loading')).toBe('lazy');
    });

    it('emits select-project when a card is clicked', async () => {
        // Arrange
        const wrapper = mount(ProjectsSection, {
            props: { projects: mockProjects },
        });

        // Act - click thumbnail of first project
        const firstCard = wrapper.findAll('article[role="button"]')[0];
        await firstCard.trigger('click');

        // Assert
        expect(wrapper.emitted('select-project')).toBeTruthy();
        expect(wrapper.emitted('select-project')?.[0]).toEqual([
            mockProjects[0],
        ]);
    });

    it('emits select-project on Enter or Space keydown for accessibility', async () => {
        // Arrange
        const wrapper = mount(ProjectsSection, {
            props: { projects: mockProjects },
        });

        // Act
        const firstCard = wrapper.findAll('article[role="button"]')[0];
        await firstCard.trigger('keydown.enter');

        // Assert
        expect(wrapper.emitted('select-project')).toBeTruthy();
        expect(wrapper.emitted('select-project')?.[0]).toEqual([
            mockProjects[0],
        ]);
    });
});
