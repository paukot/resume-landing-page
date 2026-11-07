import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import ResumeModal from '@/components/ResumeModal.vue';
import type { ResumeData } from '@/types/resume';

vi.mock('@inertiajs/vue3', () => ({
    usePage: () => ({
        props: {
            locale: 'en',
            translations: {
                resume_preview: 'Curriculum Vitae Preview',
                download_pdf: 'Download PDF',
                print: 'Print',
            },
        },
    }),
}));

const mockData: ResumeData = {
    name: 'Paulina Test',
    title: 'Senior Backend Engineer',
    intro: 'Intro text',
    summary: 'Summary text',
    contact: {
        email: 'paulina@example.com',
        phone: '+48 123 456 789',
        location: 'Warsaw, Poland',
        linkedin: 'https://linkedin.com/in/paulina',
    },
    cvPdfUrl: 'https://example.com/cv.pdf',
    languages: [],
    quickStats: [],
    experience: [],
    education: [],
    skillCategories: [],
    projects: [],
};

describe('ResumeModal.vue', () => {
    it('does not render dialog when isOpen is false', () => {
        // Arrange & Act
        const wrapper = mount(ResumeModal, {
            props: {
                data: mockData,
                isOpen: false,
            },
        });

        // Assert
        expect(wrapper.find('[role="dialog"]').exists()).toBe(false);
    });

    it('renders dialog with accessibility attributes when isOpen is true', () => {
        // Arrange & Act
        const wrapper = mount(ResumeModal, {
            props: {
                data: mockData,
                isOpen: true,
            },
        });

        // Assert
        const dialog = wrapper.find('[role="dialog"]');
        expect(dialog.exists()).toBe(true);
        expect(dialog.attributes('aria-modal')).toBe('true');
        expect(dialog.text()).toContain('Paulina Test');
        expect(dialog.text()).toContain('Senior Backend Engineer');
    });

    it('emits close event when backdrop or close button is clicked', async () => {
        // Arrange
        const wrapper = mount(ResumeModal, {
            props: {
                data: mockData,
                isOpen: true,
            },
        });

        // Act - close button
        const closeBtn = wrapper.find('button[aria-label="Close modal"]');
        await closeBtn.trigger('click');

        // Assert
        expect(wrapper.emitted('close')).toHaveLength(1);
    });

    it('emits close event when Escape key is pressed', async () => {
        // Arrange
        const wrapper = mount(ResumeModal, {
            props: {
                data: mockData,
                isOpen: true,
            },
        });

        // Act
        window.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape' }));

        // Assert
        expect(wrapper.emitted('close')).toBeTruthy();
    });

    it('calls window.print when print button is clicked', async () => {
        // Arrange
        const printSpy = vi.spyOn(window, 'print');
        const wrapper = mount(ResumeModal, {
            props: {
                data: mockData,
                isOpen: true,
            },
        });

        // Act
        const printBtn = wrapper
            .findAll('button')
            .find((b) => b.text().includes('Print'));
        expect(printBtn).toBeDefined();
        if (printBtn) {
            await printBtn.trigger('click');
        }

        // Assert
        expect(printSpy).toHaveBeenCalled();
    });
});
