export interface SocialLink {
    name: string;
    url: string;
    icon: string;
    label: string;
}

export interface QuickStat {
    value: string;
    label: string;
    description: string;
}

export interface QuickFact {
    label: string;
    value: string;
    icon: string;
}

export interface ExperienceItem {
    id: string;
    role: string;
    company: string;
    companyUrl?: string;
    location: string;
    period: string;
    isCurrent?: boolean;
    description: string;
    highlights: string[];
    technologies: string[];
}

export interface EducationItem {
    id: string;
    degree: string;
    field: string;
    institution: string;
    institutionUrl?: string;
    location: string;
    period: string;
    gpa?: string;
    honors?: string;
    keyCourses?: string[];
}

export interface CertificationItem {
    id: string;
    name: string;
    issuer: string;
    issueDate: string;
    credentialUrl?: string;
    badge?: string;
}

export interface SkillItem {
    name: string;
    level: 'Familiar' | 'Proficient' | 'Advanced' | 'Expert';
    percentage: number;
    highlight?: boolean;
}

export interface SkillCategory {
    id: string;
    name: string;
    icon: string;
    description: string;
    skills: SkillItem[];
}

export interface ProjectItem {
    id: string;
    title: string;
    tagline: string;
    category: 'Full-Stack' | 'Open Source' | 'AI / Tools' | 'Web Apps';
    description: string;
    highlights: string[];
    technologies: string[];
    liveUrl?: string;
    githubUrl?: string;
    featured?: boolean;
    stats?: { label: string; value: string };
    gradient: string;
}

export interface ResumeData {
    name: string;
    title: string;
    roles: string[];
    tagline: string;
    about: string[];
    status: {
        available: boolean;
        text: string;
    };
    contact: {
        email: string;
        location: string;
        timezone: string;
        phone?: string;
        website?: string;
    };
    quickStats: QuickStat[];
    quickFacts: QuickFact[];
    socials: SocialLink[];
    experience: ExperienceItem[];
    education: EducationItem[];
    certifications: CertificationItem[];
    skillCategories: SkillCategory[];
    projects: ProjectItem[];
}
