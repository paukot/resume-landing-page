import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3';

type Messages = { [key: string]: string | Messages };

export function useTranslations() {
    const page = usePage();
    const messages = computed(() => page.props.translations as Messages);
    const locale = computed(() => page.props.locale as string);

    const t = (key: string, replace: Record<string, string | number> = {}): string => {
        const value = key
            .split('.')
            .reduce<string | Messages | undefined>(
                (node, part) => (typeof node === 'object' ? node[part] : undefined),
                messages.value,
            );

        if (typeof value !== 'string') return key; // visible fallback while developing

        return Object.entries(replace).reduce(
            (text, [name, val]) => text.replaceAll(`:${name}`, String(val)),
            value,
        );
    };

    return {t, locale};
}
