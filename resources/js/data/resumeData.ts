import type { ResumeData } from '../types/resume';

export const resumeData: ResumeData = {
    name: 'Alex Morgan',
    title: 'Senior Full-Stack Engineer & Product Architect',
    roles: [
        'Senior Full-Stack Engineer',
        'Laravel & Vue.js Specialist',
        'System & Cloud Architect',
        'Open Source Creator',
    ],
    tagline:
        'Building scalable, high-performance web applications with obsessive attention to craft, performance, and user experience.',
    status: {
        available: true,
        text: 'Available for high-impact roles & consulting',
    },
    contact: {
        email: 'alex.morgan.dev@example.com',
        location: 'San Francisco, CA (Open to Remote)',
        timezone: 'PST (UTC-8)',
        website: 'https://alexmorgan.dev',
    },
    quickStats: [
        {
            value: '7+',
            label: 'Years Experience',
            description: 'Shipping production software at scale',
        },
        {
            value: '45+',
            label: 'Projects Delivered',
            description: 'From zero-to-one startups to enterprise systems',
        },
        {
            value: '99.98%',
            label: 'Target Uptime',
            description: 'Robust, resilient distributed architectures',
        },
        {
            value: '1.2M+',
            label: 'Active End Users',
            description: 'Across products architected and scaled',
        },
    ],
    quickFacts: [
        {
            label: 'Primary Stack',
            value: 'Laravel, Vue 3, Inertia, TypeScript, PostgreSQL',
            icon: 'layers',
        },
        {
            label: 'Current Focus',
            value: 'Distributed APIs, Real-time WebSockets, Micro-frontends',
            icon: 'cpu',
        },
        {
            label: 'Work Preference',
            value: 'Remote / Hybrid (Global overlap)',
            icon: 'globe',
        },
        {
            label: 'Languages',
            value: 'English (Native), Spanish (Conversational)',
            icon: 'message',
        },
    ],
    socials: [
        {
            name: 'GitHub',
            url: 'https://github.com',
            icon: 'github',
            label: 'github.com/alexmorgan',
        },
        {
            name: 'LinkedIn',
            url: 'https://linkedin.com',
            icon: 'linkedin',
            label: 'linkedin.com/in/alexmorgan',
        },
        {
            name: 'X / Twitter',
            url: 'https://x.com',
            icon: 'twitter',
            label: '@alexmorgan_dev',
        },
        {
            name: 'Email',
            url: 'mailto:alex.morgan.dev@example.com',
            icon: 'mail',
            label: 'alex.morgan.dev@example.com',
        },
    ],
    about: [
        'I am a product-minded Senior Full-Stack Engineer with over 7 years of hands-on experience designing, building, and maintaining modern web platforms. My core philosophy centers on shipping clean, resilient architectures that deliver real business impact without unnecessary complexity.',
        'Throughout my career, I have led technical initiatives across fast-growing venture-backed startups and established scale-ups. I specialize in the modern Laravel ecosystem (Inertia.js, Pest, Octane) paired with reactive modern Vue 3 frontends, backed by high-throughput PostgreSQL and Redis infrastructure.',
        'Outside of client and company work, I am passionate about developer tooling, contributing to open-source libraries, mentoring upcoming engineers, and writing pragmatic technical articles on software design.',
    ],
    experience: [
        {
            id: 'exp-1',
            role: 'Lead Full-Stack Architect',
            company: 'Vanguard Cloud Systems',
            companyUrl: 'https://example.com',
            location: 'San Francisco, CA (Remote)',
            period: '2023 — Present',
            isCurrent: true,
            description:
                'Architected the core SaaS orchestration platform handling multi-tenant infrastructure, billing workflows, and live telemetry dashboards.',
            highlights: [
                'Designed and transitioned a monolithic legacy portal into an event-driven Inertia.js + Laravel 11 SPA, slashing p95 latency by 52%.',
                'Spearheaded the real-time event pipeline using Laravel Reverb and Redis streams, processing over 12 million events daily with sub-80ms propagation.',
                'Mentored 8 mid-level engineers, instituted automated CI/CD test gates with Pest, raising test coverage from 44% to 91%.',
                'Reduced AWS compute expenditure by $3,200/month by optimizing database indexing, Redis caching layers, and asynchronous queue workers.',
            ],
            technologies: [
                'Laravel 11',
                'Vue 3',
                'Inertia.js',
                'TypeScript',
                'Tailwind CSS',
                'PostgreSQL',
                'Redis',
                'Docker',
                'AWS',
            ],
        },
        {
            id: 'exp-2',
            role: 'Senior Software Engineer',
            company: 'Hyperion Analytics',
            companyUrl: 'https://example.com',
            location: 'Austin, TX (Remote)',
            period: '2021 — 2023',
            isCurrent: false,
            description:
                'Built and scaled client-facing customer data platforms, analytics report generators, and high-frequency webhook pipelines.',
            highlights: [
                'Engineered high-throughput asynchronous job workers processing CSV/JSON ingestion payloads up to 500MB without memory leaks.',
                'Created custom dashboard widget framework in Vue 3 with virtualized scrolling, supporting 100,000+ data rows at 60 FPS.',
                'Collaborated with product designers to implement an accessible, tokenized design system adhering to WCAG 2.1 AA standards.',
                'Automated multi-region database replication and zero-downtime Blue/Green deployments.',
            ],
            technologies: [
                'Vue.js',
                'Laravel',
                'PHP 8',
                'TypeScript',
                'MySQL',
                'Elasticsearch',
                'Pest PHP',
                'Vite',
                'GitHub Actions',
            ],
        },
        {
            id: 'exp-3',
            role: 'Full-Stack Developer',
            company: 'Nexus Interactive Labs',
            companyUrl: 'https://example.com',
            location: 'San Diego, CA',
            period: '2019 — 2021',
            isCurrent: false,
            description:
                'Developed customized e-commerce, CRM, and internal operations tools for high-growth enterprise clients.',
            highlights: [
                'Built bespoke Stripe and PayPal payment routing systems with webhook reconciliation, processing $4.5M+ in ARR.',
                'Refactored legacy REST APIs into structured endpoints with strict validation and OpenAPI documentation.',
                'Improved Lighthouse accessibility and SEO scores from 68 to 98 across primary marketing portals.',
            ],
            technologies: [
                'Laravel',
                'Vue.js',
                'Tailwind CSS',
                'PostgreSQL',
                'Redis',
                'Stripe API',
                'Git',
            ],
        },
        {
            id: 'exp-4',
            role: 'Junior Software Engineer',
            company: 'Starlight Digital Agency',
            companyUrl: 'https://example.com',
            location: 'San Francisco, CA',
            period: '2018 — 2019',
            isCurrent: false,
            description:
                'Collaborated in an agile team to construct interactive client websites, RESTful backends, and CMS plugins.',
            highlights: [
                'Constructed responsive component libraries and accessible navigation patterns.',
                'Configured automated unit tests and integration tests for core authentication modules.',
            ],
            technologies: [
                'PHP',
                'JavaScript',
                'HTML5 / SASS',
                'MySQL',
                'Git',
                'WordPress REST API',
            ],
        },
    ],
    education: [
        {
            id: 'edu-1',
            degree: 'Bachelor of Science in Computer Science',
            field: 'Software Engineering & Distributed Systems',
            institution: 'University of California, Berkeley',
            institutionUrl: 'https://berkeley.edu',
            location: 'Berkeley, CA',
            period: '2014 — 2018',
            gpa: '3.86 / 4.0',
            honors: 'Magna Cum Laude, Dean’s Honors List',
            keyCourses: [
                'Data Structures & Algorithms',
                'Database System Principles',
                'Operating Systems & Networking',
                'Software Design Patterns',
                'Computer Security & Cryptography',
            ],
        },
    ],
    certifications: [
        {
            id: 'cert-1',
            name: 'AWS Certified Solutions Architect – Associate',
            issuer: 'Amazon Web Services',
            issueDate: '2024',
            badge: 'AWS-SAA',
            credentialUrl: 'https://aws.amazon.com',
        },
        {
            id: 'cert-2',
            name: 'Meta Certified Frontend Developer Professional',
            issuer: 'Meta',
            issueDate: '2023',
            badge: 'Meta-FED',
            credentialUrl: 'https://coursera.org',
        },
    ],
    skillCategories: [
        {
            id: 'frontend',
            name: 'Frontend Engineering',
            icon: 'code',
            description:
                'Crafting reactive, accessible, and silky smooth user interfaces.',
            skills: [
                {
                    name: 'Vue 3 / Composition API',
                    level: 'Expert',
                    percentage: 96,
                    highlight: true,
                },
                {
                    name: 'TypeScript',
                    level: 'Expert',
                    percentage: 94,
                    highlight: true,
                },
                {
                    name: 'Inertia.js',
                    level: 'Expert',
                    percentage: 95,
                    highlight: true,
                },
                {
                    name: 'Tailwind CSS v4',
                    level: 'Expert',
                    percentage: 98,
                    highlight: true,
                },
                {
                    name: 'Vite & Frontend Bundlers',
                    level: 'Advanced',
                    percentage: 90,
                },
                {
                    name: 'State Management (Pinia)',
                    level: 'Advanced',
                    percentage: 88,
                },
                {
                    name: 'Responsive UI & Animations',
                    level: 'Advanced',
                    percentage: 92,
                },
                {
                    name: 'Accessibility (a11y) & SEO',
                    level: 'Advanced',
                    percentage: 86,
                },
            ],
        },
        {
            id: 'backend',
            name: 'Backend & APIs',
            icon: 'database',
            description:
                'Constructing bulletproof APIs, domain logic, and resilient data layers.',
            skills: [
                {
                    name: 'PHP 8.3 / 8.4',
                    level: 'Expert',
                    percentage: 97,
                    highlight: true,
                },
                {
                    name: 'Laravel Framework',
                    level: 'Expert',
                    percentage: 98,
                    highlight: true,
                },
                {
                    name: 'PostgreSQL & MySQL',
                    level: 'Advanced',
                    percentage: 91,
                    highlight: true,
                },
                {
                    name: 'REST & GraphQL APIs',
                    level: 'Expert',
                    percentage: 94,
                },
                {
                    name: 'Redis Caching & PubSub',
                    level: 'Advanced',
                    percentage: 88,
                },
                {
                    name: 'Queues & Background Jobs',
                    level: 'Expert',
                    percentage: 93,
                },
                {
                    name: 'Event-Driven Architectures',
                    level: 'Advanced',
                    percentage: 87,
                },
                {
                    name: 'Security & Auth (OAuth, Sanctum)',
                    level: 'Advanced',
                    percentage: 90,
                },
            ],
        },
        {
            id: 'devops',
            name: 'DevOps & Infrastructure',
            icon: 'cloud',
            description:
                'Automating deployments, containerization, and cloud infrastructure.',
            skills: [
                {
                    name: 'Docker & Compose',
                    level: 'Advanced',
                    percentage: 88,
                    highlight: true,
                },
                {
                    name: 'AWS (S3, ECS, RDS, CloudFront)',
                    level: 'Advanced',
                    percentage: 85,
                    highlight: true,
                },
                {
                    name: 'CI/CD (GitHub Actions)',
                    level: 'Advanced',
                    percentage: 90,
                },
                {
                    name: 'Nginx & Linux Server Admin',
                    level: 'Advanced',
                    percentage: 84,
                },
                {
                    name: 'Laravel Octane / Swoole',
                    level: 'Proficient',
                    percentage: 82,
                },
                {
                    name: 'Monitoring (Sentry, Prometheus)',
                    level: 'Proficient',
                    percentage: 80,
                },
            ],
        },
        {
            id: 'testing-tools',
            name: 'Architecture & Testing',
            icon: 'cpu',
            description:
                'Writing tests with confidence and adhering to solid software principles.',
            skills: [
                {
                    name: 'Pest PHP & PHPUnit',
                    level: 'Expert',
                    percentage: 95,
                    highlight: true,
                },
                {
                    name: 'Vitest / Vue Test Utils',
                    level: 'Advanced',
                    percentage: 86,
                },
                {
                    name: 'Domain-Driven Design (DDD)',
                    level: 'Advanced',
                    percentage: 85,
                },
                {
                    name: 'Git & Trunk-Based Dev',
                    level: 'Expert',
                    percentage: 96,
                },
                {
                    name: 'Performance Profiling',
                    level: 'Advanced',
                    percentage: 89,
                },
                {
                    name: 'Microservices & Monoliths',
                    level: 'Advanced',
                    percentage: 88,
                },
            ],
        },
    ],
    projects: [
        {
            id: 'proj-1',
            title: 'PulseFlow Analytics',
            tagline:
                'Real-time telemetry and error monitoring engine for modern web apps',
            category: 'Full-Stack',
            featured: true,
            description:
                'A developer-first observability suite that aggregates request logs, SQL query bottlenecks, and live exception traces in under 20ms using WebSockets.',
            highlights: [
                'Live stream viewer with customizable alerts and Slack/Discord webhook integrations.',
                'Sub-millisecond query inspection using Redis circular buffers and PostgreSQL timescale partitioning.',
                'Interactive flamegraph explorer built in Vue 3 and SVG canvas.',
            ],
            technologies: [
                'Laravel 11',
                'Inertia.js',
                'Vue 3',
                'TypeScript',
                'Tailwind CSS',
                'Redis',
                'PostgreSQL',
            ],
            liveUrl: 'https://example.com/demo/pulseflow',
            githubUrl: 'https://github.com/example/pulseflow',
            stats: { label: 'GitHub Stars', value: '1.4k+' },
            gradient: 'from-violet-600 via-indigo-600 to-purple-800',
        },
        {
            id: 'proj-2',
            title: 'NovaBlade UI',
            tagline:
                'High-performance component kit and layout system for Laravel & Inertia',
            category: 'Open Source',
            featured: true,
            description:
                'An accessible, copy-paste Vue 3 + Tailwind v4 UI library tailored specifically for Laravel Inertia developers, with zero runtime overhead.',
            highlights: [
                'Over 38 fully accessible primitives with keyboard navigation and focus management.',
                'Built-in dark mode, fluid typography, and dynamic theme tokens.',
                'Adopted by over 400+ developers with 98% positive satisfaction.',
            ],
            technologies: [
                'Vue 3',
                'TypeScript',
                'Tailwind CSS v4',
                'Vite',
                'Lucide Icons',
            ],
            liveUrl: 'https://example.com/novablade',
            githubUrl: 'https://github.com/example/novablade-ui',
            stats: { label: 'Weekly Downloads', value: '12k+' },
            gradient: 'from-sky-500 via-blue-600 to-indigo-800',
        },
        {
            id: 'proj-3',
            title: 'AuraDocs AI',
            tagline:
                'Automated technical documentation and API sandbox generator',
            category: 'AI / Tools',
            featured: true,
            description:
                'Scans Laravel route schemas and PHP attributes to generate interactive API documentation with mock responses, token auth playground, and code snippet exports.',
            highlights: [
                'Automatic OpenAPI 3.1 specification generation from route controllers and form requests.',
                'Interactive live API sandbox with bearer token injection and response diffing.',
                'Instant search indexed with client-side fuzzy algorithms.',
            ],
            technologies: [
                'Laravel',
                'Vue 3',
                'Inertia.js',
                'OpenAPI',
                'Tailwind CSS',
            ],
            liveUrl: 'https://example.com/auradocs',
            githubUrl: 'https://github.com/example/auradocs',
            stats: { label: 'Active Teams', value: '250+' },
            gradient: 'from-emerald-500 via-teal-600 to-cyan-800',
        },
        {
            id: 'proj-4',
            title: 'Beacon Queue Monitor',
            tagline:
                'Visual distributed queue inspector and rate-limiter dashboard',
            category: 'Full-Stack',
            featured: false,
            description:
                'A lightweight companion package for managing Redis, SQS, and database queues with live throughput dials, dead-letter retry queues, and latency analytics.',
            highlights: [
                'One-click retry of failed jobs with payload modification capability.',
                'Real-time metrics visualizer with automated stall detection.',
            ],
            technologies: ['PHP 8.3', 'Laravel', 'Vue 3', 'Redis', 'Chart.js'],
            liveUrl: 'https://example.com/beacon',
            githubUrl: 'https://github.com/example/beacon-queue',
            stats: { label: 'Packagist', value: '45k dl' },
            gradient: 'from-amber-500 via-orange-600 to-rose-700',
        },
    ],
};
