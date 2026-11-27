<script lang="ts">
/**
 * Open dialogs, most recent last, shared by every GameModal. The dialog opened last always sits on top
 * (e.g. an image preview opened from the wrong answers list), whatever order the dialogs were mounted in,
 * and Escape only closes that one.
 */
const openStack: symbol[] = [];
const BASE_Z_INDEX = 100;
</script>

<script setup lang="ts">
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { cn } from '@/lib/utils';

const props = withDefaults(
    defineProps<{
        open: boolean;
        label?: string;
        labelledby?: string;
        panelClass?: string;
        showClose?: boolean;
        /**
         * "prominent" makes the close button a solid, labelled pill that sits above the panel's top-right corner,
         * outside it, so it never covers the content (used on image previews).
         */
        closeVariant?: 'default' | 'prominent';
    }>(),
    {
        label: undefined,
        labelledby: undefined,
        panelClass: '',
        showClose: true,
        closeVariant: 'default',
    },
);

const emit = defineEmits<{ close: [] }>();

const panel = ref<HTMLElement | null>(null);
let previouslyFocused: HTMLElement | null = null;

const id = Symbol('game-modal');
const zIndex = ref(BASE_Z_INDEX);

function isTopmost(): boolean {
    return openStack[openStack.length - 1] === id;
}

function removeFromStack(): void {
    const index = openStack.indexOf(id);

    if (index !== -1) {
        openStack.splice(index, 1);
    }
}

/**
 * The dialog only renders in the browser. Lifecycle hooks don't run during SSR, so the server and the
 * hydrating client both render a plain placeholder (no Teleport), and the DOM is only touched once mounted.
 */
const isMounted = ref(false);

function onKeydown(event: KeyboardEvent): void {
    if (event.key === 'Escape' && isTopmost()) {
        emit('close');
    }
}

async function activate(): Promise<void> {
    previouslyFocused = document.activeElement as HTMLElement | null;
    removeFromStack();
    openStack.push(id);
    zIndex.value = BASE_Z_INDEX + openStack.length;
    document.addEventListener('keydown', onKeydown);
    await nextTick();
    panel.value
        ?.querySelector<HTMLElement>('[data-autofocus], button')
        ?.focus({ preventScroll: true });
}

function deactivate(): void {
    removeFromStack();
    document.removeEventListener('keydown', onKeydown);
    previouslyFocused?.focus?.({ preventScroll: true });
    previouslyFocused = null;
}

onMounted(() => {
    isMounted.value = true;

    if (props.open) {
        void activate();
    }
});

watch(
    () => props.open,
    (isOpen) => (isOpen ? void activate() : deactivate()),
);

onBeforeUnmount(() => {
    removeFromStack();
    document.removeEventListener('keydown', onKeydown);
});
</script>

<template>
    <Teleport v-if="isMounted" to="body">
        <div
            v-if="open"
            class="fixed inset-0 flex items-center justify-center bg-[rgb(10_10_24/.72)] p-4 leading-normal"
            :style="{ zIndex }"
            role="dialog"
            aria-modal="true"
            :aria-label="label"
            :aria-labelledby="labelledby"
            @click.self="emit('close')"
        >
            <div
                ref="panel"
                :class="
                    cn(
                        'relative max-h-[94vh] w-full max-w-[1100px] overflow-auto rounded-[20px] bg-panel-solid p-6 text-cream shadow-[0_30px_90px_rgb(0_0_0/.6)]',
                        // Room above the panel for the close button; panel and button together stay within 94vh.
                        closeVariant === 'prominent' &&
                            'mt-16 max-h-[calc(94vh-4rem)] overflow-visible',
                        panelClass,
                    )
                "
            >
                <button
                    v-if="showClose && closeVariant === 'prominent'"
                    type="button"
                    class="absolute right-0 bottom-full z-10 mb-3 inline-flex cursor-pointer items-center gap-2 rounded-full bg-gold py-2.5 pr-5 pl-4 text-base font-extrabold text-ink shadow-[0_0_0_3px_var(--color-night),0_8px_24px_rgb(0_0_0/.45)] transition hover:bg-gold-light focus-visible:outline-3 focus-visible:outline-offset-2 focus-visible:outline-gold active:scale-[.97]"
                    aria-label="Close preview"
                    @click="emit('close')"
                >
                    <span class="text-2xl leading-none" aria-hidden="true"
                        >×</span
                    >
                    Close
                </button>
                <button
                    v-else-if="showClose"
                    type="button"
                    class="absolute top-6 right-[26px] z-10 grid size-[38px] cursor-pointer place-items-center rounded-full bg-white/10 text-xl font-semibold text-white hover:bg-gold hover:text-ink"
                    aria-label="Close"
                    @click="emit('close')"
                >
                    ×
                </button>
                <slot />
            </div>
        </div>
    </Teleport>
</template>
