import { describe, it, expect } from 'vitest';
import { visibleSections } from '@/lib/sections';
import type { ResumeData } from '@/types/resume';

const fullResume = (overrides: Partial<ResumeData> = {}): ResumeData => ({
    name: 'Paulina Test',
    title: 'Engineer',
    intro: 'Intro',
    summary: 'Summary',
    contact: {
        email: 'paulina@example.com',
        linkedin: 'https://linkedin.com/in/p',
    },
    cvPdfUrl: '',
    languages: [],
    quickStats: [],
    experience: [
        {
            id: 1,
            role: 'Dev',
            company: 'Acme',
            period: '2020',
            highlights: [],
            technologies: [],
        },
    ],
    education: [
        {
            id: 1,
            degree: 'MSc',
            field: 'CS',
            institution: 'Uni',
            period: '2015',
        },
    ],
    skillCategories: [
        { id: 1, name: 'Backend', icon: 'server', skills: ['PHP'] },
    ],
    projects: [
        {
            id: 1,
            title: 'Project',
            tagline: '',
            category: '',
            description: '',
            highlights: [],
            technologies: [],
        },
    ],
    ...overrides,
});

describe('visibleSections', () => {
    it('places the portfolio right after the about section', () => {
        expect(visibleSections(fullResume())).toEqual([
            'about',
            'projects',
            'experience',
            'skills',
            'education',
            'contact',
        ]);
    });

    it('drops sections that are switched off in the admin', () => {
        // Arrange
        const resume = fullResume({
            settings: {
                showAbout: true,
                showExperience: false,
                showSkills: true,
                showEducation: true,
                showProjects: false,
                showContact: true,
            },
        });

        // Act & Assert
        expect(visibleSections(resume)).toEqual([
            'about',
            'skills',
            'education',
            'contact',
        ]);
    });

    it('drops sections that have no content', () => {
        // Arrange
        const resume = fullResume({
            projects: [],
            experience: [],
            education: [],
            skillCategories: [
                { id: 1, name: 'Empty', icon: 'code', skills: [] },
            ],
        });

        // Act & Assert
        expect(visibleSections(resume)).toEqual(['about', 'contact']);
    });

    it('hides about and contact when there is no summary, language or contact detail', () => {
        // Arrange
        const resume = fullResume({ summary: '', languages: [], contact: {} });

        // Act & Assert
        expect(visibleSections(resume)).not.toContain('about');
        expect(visibleSections(resume)).not.toContain('contact');
    });
});
