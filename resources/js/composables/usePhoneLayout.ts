import { onMounted, readonly, ref } from 'vue';
import type { Ref } from 'vue';

/**
 * Phones only: narrower than a tablet in portrait, or a touch screen too short to be a tablet
 * (a phone in landscape). iPads and other tablets, in either orientation, do not match.
 */
const PHONE_QUERY =
    '(max-width: 639px), (pointer: coarse) and (max-height: 500px)';

const isPhone = ref(false);
let query: MediaQueryList | null = null;

/**
 * Whether the board is shown on a phone, where drag and drop is turned off and images are placed
 * by tapping a box instead. Always false during SSR and hydration; resolved once mounted, and kept
 * in sync if the viewport changes (rotation, resizing). One media-query listener is shared by every caller.
 */
export function usePhoneLayout(): Readonly<Ref<boolean>> {
    onMounted(() => {
        if (query) {
            return;
        }

        query = window.matchMedia(PHONE_QUERY);
        const update = (): void => {
            isPhone.value = query!.matches;
        };
        update();
        query.addEventListener('change', update);
    });

    return readonly(isPhone);
}
