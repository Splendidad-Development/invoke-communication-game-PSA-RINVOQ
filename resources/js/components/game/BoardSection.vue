<script setup lang="ts">
import { computed } from 'vue';
import BoardSlot from '@/components/game/BoardSlot.vue';
import { sectionToneClasses } from '@/lib/game';
import type { BoardSection, GameImage } from '@/types';

const props = defineProps<{
    section: BoardSection;
    lists: Record<string, number[]>;
    images: Map<number, GameImage>;
    locked: boolean;
    correctness: (slotId: string) => boolean | null;
    playingSlotId: string | null;
    targetSlotId?: string | null;
    /** Section titles are the answers, so they only show once the board is scored. */
    showLabel: boolean;
}>();

const emit = defineEmits<{
    'update:list': [slotId: string, list: number[]];
    dropped: [slotId: string, imageId: number, fromList: string];
    dragStart: [];
    dragEnd: [];
    open: [slotId: string];
    zoom: [image: GameImage];
    remove: [slotId: string];
}>();

/** Gap between boxes in a section, in rem (matches gap-2). */
const SLOT_GAP_REM = 0.5;

/**
 * Sections with several boxes keep them on one row: each box is a little smaller than a single box
 * and shrinks further to share the section's width, so the row never wraps onto a second line.
 */
const slotStyle = computed(() => {
    const count = props.section.slots.length;

    if (count < 2) {
        return undefined;
    }

    return {
        width: `min(calc(var(--slot) * .85), calc((100% - ${(count - 1) * SLOT_GAP_REM}rem) / ${count}))`,
    };
});
</script>

<template>
    <!-- While in play the titles are hidden, so each section gets its own framed area to keep groups apart. -->
    <div
        class="flex w-full flex-col items-center gap-[7px]"
        :class="
            !showLabel &&
            'rounded-xl bg-white/[.04] px-2 py-4 ring-1 ring-white/12'
        "
    >
        <div
            v-if="showLabel"
            class="max-w-full rounded-xs px-2.5 py-1 text-center text-[13px] font-bold text-ink"
            :class="sectionToneClasses[section.tone]"
        >
            {{ section.title }}
        </div>
        <div class="flex w-full flex-nowrap justify-center gap-2">
            <BoardSlot
                v-for="slotId in section.slots"
                :key="slotId"
                :style="slotStyle"
                :model-value="lists[slotId]"
                :slot-id="slotId"
                :label="section.title"
                :images="images"
                :locked="locked"
                :is-correct="correctness(slotId)"
                :is-playing="playingSlotId === slotId"
                :is-target="targetSlotId === slotId"
                @update:model-value="emit('update:list', slotId, $event)"
                @dropped="(...args) => emit('dropped', ...args)"
                @drag-start="emit('dragStart')"
                @drag-end="emit('dragEnd')"
                @open="emit('open', $event)"
                @zoom="emit('zoom', $event)"
                @remove="emit('remove', $event)"
            />
        </div>
    </div>
</template>
