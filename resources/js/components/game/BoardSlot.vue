<script setup lang="ts">
import { VueDraggable } from 'vue-draggable-plus';
import type { DraggableEvent } from 'vue-draggable-plus';
import ImageTile from '@/components/game/ImageTile.vue';
import { useDeviceLayout } from '@/composables/useDeviceLayout';
import { dragHandleOptions, dragOptions } from '@/lib/game';
import type { GameImage } from '@/types';

const props = defineProps<{
    slotId: string;
    label: string;
    images: Map<number, GameImage>;
    locked: boolean;
    /** null while the board is in play; true/false once it has been scored. */
    isCorrect: boolean | null;
    isPlaying: boolean;
    /** The box the picker is open for (tap to select). */
    isTarget?: boolean;
}>();

const list = defineModel<number[]>({ required: true });

/** No drag and drop on phones (tap the box and pick an image); touch tablets drag by the grip. */
const { isPhone, usesDragHandle } = useDeviceLayout();

const emit = defineEmits<{
    dropped: [slotId: string, imageId: number, fromList: string];
    dragStart: [];
    dragEnd: [];
    open: [slotId: string];
    zoom: [image: GameImage];
    remove: [slotId: string];
}>();

function onAdd(event: DraggableEvent): void {
    const imageId = Number((event.item as HTMLElement).dataset.image);
    const fromList = (event.from as HTMLElement).dataset.list ?? '';

    // Wait until both lists involved in the drop have been updated.
    queueMicrotask(() => emit('dropped', props.slotId, imageId, fromList));
}

function ariaLabel(): string {
    const imageId = list.value[0];

    if (!imageId) {
        return `${props.label}: empty box`;
    }

    const state =
        props.isCorrect === null
            ? ''
            : props.isCorrect
              ? ', correct'
              : ', incorrect';

    return `${props.label}: image ${imageId} placed${state}`;
}
</script>

<template>
    <VueDraggable
        v-model="list"
        v-bind="usesDragHandle ? dragHandleOptions : dragOptions"
        :sort="false"
        :disabled="locked || isPhone"
        :data-list="slotId"
        role="button"
        tabindex="0"
        :aria-label="ariaLabel()"
        class="slot"
        :class="{
            'is-empty': !list.length,
            'is-filled': list.length > 0,
            'is-ok': isCorrect === true,
            'is-bad': isCorrect === false,
            'is-playing': isPlaying,
            'is-target': isTarget,
            'is-locked': locked,
        }"
        @add="onAdd"
        @start="emit('dragStart')"
        @end="emit('dragEnd')"
        @click="emit('open', slotId)"
        @keydown.enter.prevent="emit('open', slotId)"
        @keydown.space.prevent="emit('open', slotId)"
    >
        <ImageTile
            v-for="imageId in list"
            :key="imageId"
            :image="images.get(imageId)!"
            fill
            :removable="!locked"
            :handle="usesDragHandle && !locked"
            @zoom="emit('zoom', $event)"
            @remove="emit('remove', slotId)"
        />
        <span
            v-if="isCorrect !== null && list.length"
            class="pointer-events-none absolute -top-2.5 -right-2.5 z-10 grid size-[22px] place-items-center rounded-full text-[.8rem] font-extrabold"
            :class="isCorrect ? 'bg-ok text-ink' : 'bg-bad text-white'"
            aria-hidden="true"
        >
            {{ isCorrect ? '✓' : '✕' }}
        </span>
    </VueDraggable>
</template>
