<script setup lang="ts">
import GameModal from '@/components/game/GameModal.vue';
import type { GameImage } from '@/types';

defineProps<{ image: GameImage | null }>();

const emit = defineEmits<{ close: [] }>();
</script>

<!--
    Fluid container with a max width: the panel spans the screen up to 1300px and the image always fills it edge to edge.
    On short screens the max width also shrinks with the height (× 1.27, the narrowest image's width-to-height ratio),
    so the whole image stays in view without white bars beside it. 94vh less 6rem leaves room for the panel padding
    and the close button above the panel.
-->
<template>
    <GameModal
        :open="image !== null"
        label="Image preview"
        close-variant="prominent"
        panel-class="w-full max-w-[min(1300px,calc((94vh_-_6rem)_*_1.27_+_2rem))] bg-white p-4"
        @close="emit('close')"
    >
        <img
            v-if="image"
            :src="image.url"
            :alt="`Image ${image.id}`"
            class="block h-auto max-h-[calc(94vh-6rem)] w-full rounded-lg object-contain"
        />
    </GameModal>
</template>
