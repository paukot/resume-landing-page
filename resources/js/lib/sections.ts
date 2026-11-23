import type { ResumeData, SectionSettings } from '@/types/resume';

export type SectionId =
    | 'about'
    | 'projects'
    | 'experience'
    | 'skills'
    | 'education'
    | 'contact';

interface SectionDefinition {
    id: SectionId;
    setting: keyof SectionSettings;
    hasContent: (resume: ResumeData) => boolean;
}

const hasAnyContact = (resume: ResumeData): boolean =>
    Object.values(resume.contact ?? {}).some(Boolean);

/**
 * Page sections in display order. Portfolio follows the intro (hero) and summary (about).
 */
export const SECTIONS: readonly SectionDefinition[] = [
    {
        id: 'about',
        setting: 'showAbout',
        hasContent: (resume) =>
            Boolean(resume.summary) ||
            (resume.languages?.length ?? 0) > 0 ||
            hasAnyContact(resume),
    },
    {
        id: 'projects',
        setting: 'showProjects',
        hasContent: (resume) => (resume.projects?.length ?? 0) > 0,
    },
    {
        id: 'experience',
        setting: 'showExperience',
        hasContent: (resume) => (resume.experience?.length ?? 0) > 0,
    },
    {
        id: 'skills',
        setting: 'showSkills',
        hasContent: (resume) =>
            (resume.skillCategories ?? []).some(
                (category) => category.skills?.length > 0,
            ),
    },
    {
        id: 'education',
        setting: 'showEducation',
        hasContent: (resume) => (resume.education?.length ?? 0) > 0,
    },
    {
        id: 'contact',
        setting: 'showContact',
        hasContent: (resume) =>
            Boolean(
                resume.contact?.email ||
                resume.contact?.linkedin ||
                resume.contact?.github,
            ),
    },
];

/**
 * Ids of the sections that are switched on in the admin and have something to show, in display order.
 */
export function visibleSections(resume: ResumeData): SectionId[] {
    return SECTIONS.filter(
        (section) =>
            (resume.settings?.[section.setting] ?? true) &&
            section.hasContent(resume),
    ).map((section) => section.id);
}
