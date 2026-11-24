<script setup lang="ts">
import GameModal from '@/components/game/GameModal.vue';
import type { GameImage } from '@/types';

defineProps<{ image: GameImage | null }>();

const emit = defineEmits<{ close: [] }>();
</script>

<template>
    <GameModal
        :open="image !== null"
        label="Image preview"
        close-variant="prominent"
        panel-class="max-w-[1300px] bg-white p-4"
        @close="emit('close')"
    >
        <img
            v-if="image"
            :src="image.url"
            :alt="`Image ${image.id}`"
            class="mx-auto block h-auto max-h-[calc(94vh-2rem)] w-auto max-w-full rounded-lg object-contain"
        />
    </GameModal>
</template>
