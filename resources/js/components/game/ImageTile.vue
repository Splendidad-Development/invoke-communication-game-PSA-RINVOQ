<script setup lang="ts">
import GripIcon from '@/components/game/GripIcon.vue';
import ZoomIcon from '@/components/game/ZoomIcon.vue';
import type { GameImage } from '@/types';

defineProps<{
    image: GameImage;
    /** Fill the parent slot; tiles in a slot stack, so a drop preview never changes the layout. */
    fill?: boolean;
    /** Show a button that takes the image off the board and back to the tray. */
    removable?: boolean;
    /** Show the grip that drags the image (touch tablets, where touching the image itself scrolls). */
    handle?: boolean;
}>();

const emit = defineEmits<{
    zoom: [image: GameImage];
    remove: [image: GameImage];
}>();
</script>

<template>
    <div
        class="tile group shrink-0 cursor-grab touch-manipulation bg-white bg-cover bg-center bg-no-repeat select-none"
        :class="
            fill
                ? 'absolute inset-0 size-full rounded-[5px]'
                : 'relative h-24 w-32 overflow-hidden rounded-lg shadow-[0_4px_14px_rgb(0_0_0/.4)]'
        "
        :style="{ backgroundImage: `url(${image.url})` }"
        :data-image="image.id"
    >
        <span
            v-if="handle"
            class="drag-handle"
            aria-hidden="true"
            title="Drag to a box"
            @click.stop
        >
            <GripIcon class="size-[15px]" />
        </span>
        <button
            type="button"
            class="zoom-button"
            aria-label="Enlarge image"
            @click.stop="emit('zoom', image)"
        >
            <ZoomIcon class="size-[15px]" />
        </button>
        <button
            v-if="removable"
            type="button"
            class="remove-button"
            :aria-label="`Remove image ${image.id} from this box`"
            title="Remove from box"
            @click.stop="emit('remove', image)"
        >
            <span aria-hidden="true">×</span>
        </button>
    </div>
</template>
