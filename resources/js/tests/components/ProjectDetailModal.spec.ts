import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import ProjectDetailModal from '@/components/ProjectDetailModal.vue';
import type { ProjectItem } from '@/types/resume';

vi.mock('@inertiajs/vue3', () => ({
    usePage: () => ({
        props: {
            locale: 'en',
            translations: {
                featured: 'Featured',
                code: 'Code',
            },
        },
    }),
}));

const mockProjects: ProjectItem[] = [
    {
        id: 1,
        title: 'Project Alpha',
        tagline: 'High scale backend',
        category: 'Architecture',
        description: 'First project description',
        content: '<p>Rich story for Project Alpha</p>',
        coverImageUrl: 'https://example.com/alpha-cover.jpg',
        galleryUrls: [
            'https://example.com/alpha-screen1.jpg',
            'https://example.com/alpha-screen2.jpg',
        ],
        highlights: ['Reduced latency by 40%'],
        technologies: ['Laravel', 'Redis', 'PostgreSQL'],
        liveUrl: 'https://alpha.example.com',
        githubUrl: 'https://github.com/example/alpha',
        featured: true,
    },
    {
        id: 2,
        title: 'Project Beta',
        tagline: 'Payment engine',
        category: 'Fintech',
        description: 'Second project description',
        content: '<p>Rich story for Project Beta</p>',
        coverImageUrl: null,
        galleryUrls: [],
        highlights: [],
        technologies: ['PHP 8.3', 'Docker'],
        liveUrl: undefined,
        githubUrl: undefined,
        featured: false,
    },
];

describe('ProjectDetailModal.vue', () => {
    it('does not render when isOpen is false', () => {
        // Arrange & Act
        const wrapper = mount(ProjectDetailModal, {
            props: {
                project: mockProjects[0],
                isOpen: false,
                projects: mockProjects,
            },
        });

        // Assert
        expect(wrapper.find('[role="dialog"]').exists()).toBe(false);
    });

    it('renders project title, category, and dialog accessibility attributes when open', () => {
        // Arrange & Act
        const wrapper = mount(ProjectDetailModal, {
            props: {
                project: mockProjects[0],
                isOpen: true,
                projects: mockProjects,
            },
        });

        // Assert
        const dialog = wrapper.find('[role="dialog"]');
        expect(dialog.exists()).toBe(true);
        expect(dialog.attributes('aria-modal')).toBe('true');
        expect(dialog.attributes('aria-labelledby')).toBe('modal-title-1');
        expect(wrapper.find('#modal-title-1').text()).toBe('Project Alpha');
    });

    it('emits close event when Escape key is pressed', async () => {
        // Arrange
        const wrapper = mount(ProjectDetailModal, {
            props: {
                project: mockProjects[0],
                isOpen: true,
                projects: mockProjects,
            },
        });

        // Act
        window.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape' }));

        // Assert
        expect(wrapper.emitted('close')).toBeTruthy();
    });

    it('emits navigate event to next project on ArrowRight key', async () => {
        // Arrange
        const wrapper = mount(ProjectDetailModal, {
            props: {
                project: mockProjects[0],
                isOpen: true,
                projects: mockProjects,
            },
        });

        // Act
        window.dispatchEvent(
            new KeyboardEvent('keydown', { key: 'ArrowRight' }),
        );

        // Assert
        expect(wrapper.emitted('navigate')).toBeTruthy();
        expect(wrapper.emitted('navigate')?.[0]).toEqual([mockProjects[1]]);
    });

    it('emits navigate event to previous project on ArrowLeft key', async () => {
        // Arrange
        const wrapper = mount(ProjectDetailModal, {
            props: {
                project: mockProjects[1],
                isOpen: true,
                projects: mockProjects,
            },
        });

        // Act
        window.dispatchEvent(
            new KeyboardEvent('keydown', { key: 'ArrowLeft' }),
        );

        // Assert
        expect(wrapper.emitted('navigate')).toBeTruthy();
        expect(wrapper.emitted('navigate')?.[0]).toEqual([mockProjects[0]]);
    });

    it('switches active gallery preview when thumbnail is clicked', async () => {
        // Arrange
        const wrapper = mount(ProjectDetailModal, {
            props: {
                project: mockProjects[0],
                isOpen: true,
                projects: mockProjects,
            },
        });

        // Act - find thumbnails
        const thumbButtons = wrapper.findAll('.overflow-x-auto button');
        expect(thumbButtons.length).toBeGreaterThan(1);
        await thumbButtons[1].trigger('click');

        // Assert
        const mainImg = wrapper.find('.aspect-video img');
        expect(mainImg.attributes('src')).toBe(
            'https://example.com/alpha-screen1.jpg',
        );
    });
});
