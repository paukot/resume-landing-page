import { watch, onUnmounted, getCurrentInstance, type Ref } from 'vue';

export function useScrollLock(isLocked: Ref<boolean>) {
    const lock = () => {
        if (typeof document !== 'undefined') {
            document.body.style.overflow = 'hidden';
        }
    };

    const unlock = () => {
        if (typeof document !== 'undefined') {
            document.body.style.overflow = '';
        }
    };

    watch(
        isLocked,
        (locked) => {
            if (locked) {
                lock();
            } else {
                unlock();
            }
        },
        { immediate: true },
    );

    if (getCurrentInstance()) {
        onUnmounted(() => {
            unlock();
        });
    }
}
