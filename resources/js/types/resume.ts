export interface QuickStat {
    value: string;
    label: string;
    description: string;
}

export interface ExperienceItem {
    id: number;
    role: string;
    company: string;
    companyUrl?: string | null;
    location: string;
    period: string;
    isCurrent?: boolean;
    description?: string;
    highlights: string[];
    technologies: string[];
}

export interface EducationItem {
    id: number;
    degree: string;
    field: string;
    institution: string;
    institutionUrl?: string | null;
    location: string;
    period: string;
}

export interface SkillCategory {
    id: number;
    name: string;
    icon: string;
    skills: string[];
}

export interface LanguageItem {
    name: string;
    level: string;
}

export interface ProjectItem {
    id: number;
    title: string;
    tagline: string;
    category: string;
    description: string;
    content?: string | null;
    coverImageUrl?: string | null;
    galleryUrls?: string[];
    highlights: string[];
    technologies: string[];
    liveUrl?: string;
    githubUrl?: string;
    featured?: boolean;
}

export interface SectionSettings {
    showAbout: boolean;
    showExperience: boolean;
    showSkills: boolean;
    showEducation: boolean;
    showProjects: boolean;
    showContact: boolean;
}

export interface ResumeData {
    name: string;
    title: string;
    intro: string;
    summary: string;
    contact: {
        email: string;
        phone: string;
        location: string;
        linkedin: string;
        github?: string;
    };
    cvPdfUrl: string;
    settings?: SectionSettings;
    languages: LanguageItem[];
    quickStats: QuickStat[];
    experience: ExperienceItem[];
    education: EducationItem[];
    skillCategories: SkillCategory[];
    projects: ProjectItem[];
}
