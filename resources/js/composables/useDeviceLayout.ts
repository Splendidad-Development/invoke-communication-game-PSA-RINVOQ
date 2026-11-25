import { computed, onMounted, readonly, ref } from 'vue';
import type { ComputedRef, Ref } from 'vue';

/**
 * Phones only: narrower than a tablet in portrait, or a touch screen too short to be a tablet
 * (a phone in landscape). iPads and other tablets, in either orientation, do not match.
 */
const PHONE_QUERY =
    '(max-width: 639px), (pointer: coarse) and (max-height: 500px)';

/** The main input is a finger: phones, iPads and other tablets (even with a keyboard case attached). */
const TOUCH_QUERY = '(pointer: coarse)';

const isPhone = ref(false);
const isTouch = ref(false);
let listening = false;

function watchQuery(query: string, target: Ref<boolean>): void {
    const list = window.matchMedia(query);
    const update = (): void => {
        target.value = list.matches;
    };
    update();
    list.addEventListener('change', update);
}

export type DeviceLayout = {
    /** No drag and drop: a box is filled by tapping it and picking an image. */
    isPhone: Readonly<Ref<boolean>>;
    /** Touch tablet: images are dragged by their grip handle, so touching the image itself still scrolls. */
    usesDragHandle: ComputedRef<boolean>;
};

/**
 * How the board is used on this device. Everything is false during SSR and hydration (the desktop board),
 * resolved once mounted and kept in sync on rotation or resizing. The media-query listeners are shared by every caller.
 */
export function useDeviceLayout(): DeviceLayout {
    onMounted(() => {
        if (listening) {
            return;
        }

        listening = true;
        watchQuery(PHONE_QUERY, isPhone);
        watchQuery(TOUCH_QUERY, isTouch);
    });

    return {
        isPhone: readonly(isPhone),
        usesDragHandle: computed(() => isTouch.value && !isPhone.value),
    };
}
