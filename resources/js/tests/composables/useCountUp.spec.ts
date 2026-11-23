import { describe, it, expect } from 'vitest';
import { formatStatValue, parseStatValue } from '@/composables/useCountUp';

describe('parseStatValue', () => {
    it('keeps the suffix around the animated number', () => {
        const parsed = parseStatValue('10+');

        expect(parsed).toEqual({
            prefix: '',
            number: 10,
            decimals: 0,
            suffix: '+',
        });
        expect(formatStatValue(parsed!, 4)).toBe('4+');
    });

    it('keeps prefix, decimals and word suffixes', () => {
        const parsed = parseStatValue('~99.9% uptime');

        expect(parsed).toEqual({
            prefix: '~',
            number: 99.9,
            decimals: 1,
            suffix: '% uptime',
        });
        expect(formatStatValue(parsed!, 50)).toBe('~50.0% uptime');
    });

    it('returns null for values without a number', () => {
        expect(parseStatValue('Remote')).toBeNull();
    });
});
