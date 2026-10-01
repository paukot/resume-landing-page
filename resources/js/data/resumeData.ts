import type { ResumeData } from '@/types/resume';

export const resumeData: ResumeData = {
    name: 'Paulina Kot',
    title: 'Backend PHP Developer',
    summary:
        'Backend PHP Developer with 3.5+ years of experience designing, developing, and scaling robust web applications and microservices using PHP, Laravel, and MySQL. Skilled in RESTful API architecture, database query optimization, Redis caching, Docker containerization, and automated testing (PHPUnit/PEST). Focused on writing clean, test-driven code that enhances system performance and supports rapid business growth.',
    status: {
        available: true,
        text: 'Available for opportunities',
    },
    contact: {
        email: 'paulinakot@proton.me',
        phone: '+48 792 659 674',
        location: 'Rzeszów, Poland (Remote / Hybrid)',
        linkedin: 'https://linkedin.com/in/paulikot',
        github: 'https://github.com/paukot',
    },
    cvPdfUrl: '/paulina_kot_php_developer_en.pdf',
    quickStats: [
        {
            value: '3.5+',
            label: 'Years of Experience',
            description: 'Building & scaling backend PHP/Laravel systems',
        },
        {
            value: '30+',
            label: 'REST APIs Architected',
            description: 'Documented with OpenAPI/Swagger standards',
        },
        {
            value: '80%',
            label: 'Test Coverage Achieved',
            description: 'Robust automated testing with PHPUnit & PEST',
        },
        {
            value: '~70%',
            label: 'Admin Workload Reduced',
            description: 'Via AI-based verification & automation workflows',
        },
    ],
    experience: [
        {
            id: 'lead-investments',
            role: 'Mid Backend PHP Developer',
            company: 'Lead Investments Sp. z o.o.',
            location: 'Remote',
            period: '06.2023 — 01.2025',
            isCurrent: false,
            description:
                'Engineered core API layers, automation pipelines, and third-party integrations for a high-traffic campaign and affiliate platform.',
            highlights: [
                'Architected and implemented over 30+ REST API endpoints in Laravel for frontend and mobile applications, providing comprehensive documentation using OpenAPI/Swagger.',
                'Implemented an AI-based verification system in campaign registration, processing over 100 daily tickets and reducing manual administrative workload by ~70%.',
                'Optimized application performance by 10–20% through targeted code refactoring, database query indexing, and intelligent Redis caching.',
                'Integrated and managed third-party survey APIs (primarily Cint), increasing the total number of connected data providers by 50%.',
                'Ensured application reliability by writing and maintaining comprehensive PHPUnit test suites targeting critical business logic, achieving 80% code coverage.',
                'Expanded core platform capabilities by designing and integrating 3 new partnership models and dynamic campaign types.',
                'Supported ~5k daily active users interacting with the platform in close collaboration with a 5-person development team in a Docker environment.',
                'Engineered and deployed a customizable A/B testing framework for affiliate partners, enabling data-driven campaign optimization.',
            ],
            technologies: [
                'PHP',
                'Laravel',
                'REST APIs',
                'Redis',
                'Docker',
                'OpenAPI / Swagger',
                'PHPUnit',
                'MySQL',
            ],
        },
        {
            id: 'appgo',
            role: 'Junior Backend PHP Developer',
            company: 'Appgo Sp. z o.o.',
            location: 'Rzeszów, Poland',
            period: '05.2021 — 05.2023',
            isCurrent: false,
            description:
                'Contributed to client projects, payment gateways, custom e-commerce CMS systems, and database optimization in an agile environment.',
            highlights: [
                'Developed and deployed 6+ corporate websites and e-commerce platforms for clients utilizing a proprietary in-house Laravel CMS, ensuring tailored functionality and seamless content management.',
                'Integrated third-party Adyen payment gateways (Apple Pay, PayPal, TWINT) to deploy 3 new secure and reliable payment processing workflows.',
                'Optimized complex MySQL queries and refactored inefficient Laravel Query Builder logic, significantly improving API response times by over 50%.',
                'Fine-tuned OCR model parameters to diagnose and resolve discrepancies in automated serial number verification, improving reading accuracy to 95%.',
                'Authored from ground up comprehensive unit and feature tests using PHPUnit/PEST for administrative dashboards.',
                'Collaborated within an Agile/Scrum environment alongside 20+ cross-functional team members across QA, UI/UX, and Product Managers, delivering scalable enhancements for platforms processing over 1,000 orders daily.',
                'Built custom reporting modules based on complex requirements using user information stored in MongoDB.',
                'Configured and maintained production server infrastructure (Nginx, Linux), upholding code quality through active PR reviews in Git.',
            ],
            technologies: [
                'PHP',
                'Laravel',
                'MySQL',
                'MongoDB',
                'Adyen API',
                'OCR',
                'PHPUnit',
                'PEST',
                'Nginx',
                'Git',
            ],
        },
    ],
    education: [
        {
            id: 'uitm',
            degree: 'Bachelor of Engineering in Computer Science (Inżynier)',
            field: 'Software Engineering & Computer Networks',
            institution:
                'University of Information Technology and Management in Rzeszów',
            location: 'Rzeszów, Poland',
            period: '2020 — 2026',
        },
    ],
    skillCategories: [
        {
            id: 'backend',
            name: 'Backend & Frameworks',
            icon: 'database',
            skills: [
                'PHP',
                'Laravel',
                'RESTful APIs',
                'Asynchronous Laravel Queues',
                'Microservices',
            ],
        },
        {
            id: 'databases',
            name: 'Databases & Caching',
            icon: 'layers',
            skills: ['MySQL', 'Redis', 'MongoDB', 'Elasticsearch'],
        },
        {
            id: 'testing-tools',
            name: 'Testing & DevOps',
            icon: 'cpu',
            skills: [
                'PHPUnit',
                'PEST',
                'Docker',
                'Git',
                'Nginx',
                'OpenAPI / Swagger',
                'CI/CD',
            ],
        },
        {
            id: 'admin-frontend',
            name: 'Admin & Frontend',
            icon: 'code',
            skills: [
                'FilamentPHP',
                'Livewire',
                'JavaScript (ES6+)',
                'Tailwind CSS',
            ],
        },
    ],
    languages: [
        { name: 'Polish', level: 'Native' },
        { name: 'English', level: 'Advanced / C1' },
    ],
    projects: [
        {
            id: 'campaign-api-engine',
            title: 'Campaign & Survey Integration Engine',
            tagline:
                'High-throughput survey aggregation service with OpenAPI documentation',
            category: 'API Architecture',
            featured: true,
            description:
                'A modular Laravel microservice designed to ingest, normalize, and dispatch survey opportunities from Cint and external partners, with Redis caching and Swagger specs.',
            highlights: [
                'Sub-80ms API response time with Redis caching and query indexing.',
                'Comprehensive OpenAPI / Swagger specification with automated request validation.',
                'Automated webhook handlers with failure recovery and exponential backoff.',
            ],
            technologies: [
                'PHP',
                'Laravel',
                'Redis',
                'MySQL',
                'OpenAPI / Swagger',
                'Docker',
            ],
            githubUrl: 'https://github.com/paukot',
        },
        {
            id: 'ai-verification-pipeline',
            title: 'Automated Ticket & Document OCR Pipeline',
            tagline:
                'Background worker service for automated document and ticket validation',
            category: 'Backend Automation',
            featured: true,
            description:
                'An automated verification queue worker integrating OCR models and rule-based validation to verify campaign submissions and user uploads in real-time.',
            highlights: [
                'Processed 100+ daily tickets, reducing administrative processing overhead by ~70%.',
                'Fine-tuned serial number OCR parsing achieving 95% verification accuracy.',
                'Full automated test coverage with PHPUnit and PEST.',
            ],
            technologies: [
                'PHP',
                'Laravel',
                'Laravel Queues',
                'OCR',
                'PEST',
                'MySQL',
            ],
            githubUrl: 'https://github.com/paukot',
        },
        {
            id: 'filament-backoffice',
            title: 'Filament Backoffice & Reporting Dashboard',
            tagline:
                'Administrative management portal for partner payouts and analytics',
            category: 'Admin Panel',
            featured: true,
            description:
                'A clean, responsive administrative portal built with FilamentPHP and Livewire to manage affiliate partners, dynamic campaign models, and financial reports.',
            highlights: [
                'Role-based permissions with audit logging for critical administrative actions.',
                'MongoDB and MySQL aggregated analytics reports exported directly to CSV and Excel.',
                'Interactive metric cards tracking daily active users and conversion ratios.',
            ],
            technologies: [
                'FilamentPHP',
                'Livewire',
                'Laravel',
                'MongoDB',
                'MySQL',
                'Tailwind CSS',
            ],
            githubUrl: 'https://github.com/paukot',
        },
        {
            id: 'payment-gateway-hub',
            title: 'Adyen Multi-Gateway Payment Hub',
            tagline:
                'Secure checkout and webhook reconciliation for e-commerce',
            category: 'Payments',
            featured: false,
            description:
                'Payment integration service unifying Adyen workflows (Apple Pay, PayPal, TWINT) with robust cryptographic signature validation and refund management.',
            highlights: [
                'Idempotent webhook processing to prevent duplicate billing events.',
                'Unit and feature test suite covering successful, declined, and chargeback flows.',
            ],
            technologies: ['PHP', 'Laravel', 'Adyen API', 'PHPUnit', 'MySQL'],
            githubUrl: 'https://github.com/paukot',
        },
    ],
};
