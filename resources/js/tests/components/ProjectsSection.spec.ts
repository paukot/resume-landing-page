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
                    featured_projects: 'Featured Projects',
                },
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
        expect(wrapper.text()).toContain('Featured Projects');
        expect(wrapper.text()).toContain('Project Canvas');
        expect(wrapper.text()).toContain('Cloud Orchestrator');
    });

    it('renders 2 projects per column responsive grid', () => {
        // Arrange & Act
        const wrapper = mount(ProjectsSection, {
            props: { projects: mockProjects },
        });

        // Assert
        const grid = wrapper.find('.grid-cols-1.md\\:grid-cols-2');
        expect(grid.exists()).toBe(true);
        expect(wrapper.findAll('article')).toHaveLength(2);
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

    it('emits select-project when card thumbnail or title is clicked', async () => {
        // Arrange
        const wrapper = mount(ProjectsSection, {
            props: { projects: mockProjects },
        });

        // Act - click thumbnail of first project
        const firstThumbnail = wrapper.findAll('div[role="button"]')[0];
        await firstThumbnail.trigger('click');

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
        const firstThumbnail = wrapper.findAll('div[role="button"]')[0];
        await firstThumbnail.trigger('keydown.enter');

        // Assert
        expect(wrapper.emitted('select-project')).toBeTruthy();
        expect(wrapper.emitted('select-project')?.[0]).toEqual([
            mockProjects[0],
        ]);
    });
});
