import { createInertiaApp } from '@inertiajs/vue3';
import 'flag-icons/css/flag-icons.min.css';
import { reveal, spotlight } from '@/lib/directives';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    withApp: (app) => {
        app.directive('reveal', reveal);
        app.directive('spotlight', spotlight);
        app.directive('focus', {
            mounted: (el: HTMLElement, shouldFocus) => {
                if (shouldFocus.value !== false) {
                    el.focus();
                }
            },
        });
    },
    progress: {
        color: '#7c3aed',
    },
});
