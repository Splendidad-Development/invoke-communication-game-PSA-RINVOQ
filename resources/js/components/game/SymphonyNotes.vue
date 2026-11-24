<script setup lang="ts">
import { noteHeight } from '@/lib/symphony';
import { cn } from '@/lib/utils';

/**
 * A row of bars, one per slot, shaped like the melody. Gold for correct notes, pink for sour ones.
 */
const props = withDefaults(
    defineProps<{
        notes: boolean[];
        /** Index of the note currently playing (-1 for none). */
        activeIndex?: number;
        /** When true, bars only light up once they have been played. */
        revealAsPlayed?: boolean;
        size?: 'small' | 'large';
    }>(),
    { activeIndex: -1, revealAsPlayed: false, size: 'small' },
);

function barClass(index: number, isGood: boolean): string {
    const isRevealed = !props.revealAsPlayed || index <= props.activeIndex;

    if (props.size === 'large') {
        return cn(
            'rounded-[3px] bg-white/14 transition-colors',
            isRevealed &&
                props.activeIndex >= 0 &&
                (isGood ? 'bg-gold' : 'bg-bad'),
        );
    }

    // While a row plays, the current note is outlined and the notes still to come are dimmed.
    return cn(
        'rounded-[1px]',
        isGood ? 'bg-gold' : 'bg-bad',
        index === props.activeIndex &&
            'outline-2 -outline-offset-2 outline-white',
        props.activeIndex >= 0 && index > props.activeIndex && 'opacity-45',
    );
}
</script>

<template>
    <div
        aria-hidden="true"
        :class="
            size === 'large'
                ? 'grid h-[86px] w-full max-w-[480px] items-end gap-1'
                : 'grid h-6 items-end gap-px sm:gap-0.5'
        "
        :style="{
            gridTemplateColumns:
                size === 'large'
                    ? `repeat(${notes.length}, 1fr)`
                    : `repeat(${notes.length}, 4px)`,
        }"
    >
        <i
            v-for="(isGood, index) in notes"
            :key="index"
            class="block"
            :class="barClass(index, isGood)"
            :style="{
                height: `${noteHeight(index, size === 'large' ? 25 : 30)}%`,
            }"
        />
    </div>
</template>
