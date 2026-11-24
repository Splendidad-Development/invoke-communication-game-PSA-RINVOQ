<script setup lang="ts">
import { computed, nextTick, ref, watch } from 'vue';
import GameModal from '@/components/game/GameModal.vue';
import type { GameImage } from '@/types';

const props = defineProps<{
    /** The slot being filled, or null when the picker is closed. */
    slotId: string | null;
    slotTitle: string;
    order: number[];
    images: Map<number, GameImage>;
    currentImageId: number | null;
    /** Where each image currently sits: section title per image, or undefined when in the tray. */
    placedIn: (imageId: number) => { slotId: string; title: string } | null;
}>();

const emit = defineEmits<{
    close: [];
    place: [imageId: number, slotId: string];
    clear: [slotId: string];
}>();

/** Images still free to place: those in the tray plus the one already in this box. Images in other boxes are left out. */
const available = computed(() =>
    props.order.filter((imageId) => {
        const placement = props.placedIn(imageId);

        return !placement || placement.slotId === props.slotId;
    }),
);

const selected = ref<number | null>(null);
const track = ref<HTMLElement | null>(null);

watch(
    () => props.slotId,
    async (slotId) => {
        if (!slotId) {
            return;
        }

        selected.value = props.currentImageId;
        await nextTick();

        const current = track.value?.querySelector<HTMLElement>(
            '[aria-pressed="true"]',
        );

        if (current) {
            current.scrollIntoView({ inline: 'center', block: 'nearest' });
        } else if (track.value) {
            track.value.scrollLeft = 0;
        }
    },
);

function step(direction: 1 | -1): void {
    track.value?.scrollBy({
        left: direction * track.value.clientWidth * 0.8,
        behavior: 'smooth',
    });
}

function confirm(): void {
    if (props.slotId && selected.value) {
        emit('place', selected.value, props.slotId);
    }
}

function onKeydown(event: KeyboardEvent): void {
    if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
        step(event.key === 'ArrowRight' ? 1 : -1);
    }
}
</script>

<template>
    <GameModal
        :open="slotId !== null"
        labelledby="picker-title"
        panel-class="border border-white p-[clamp(1rem,2.5vw,26px)]"
        @close="emit('close')"
    >
        <div class="flex flex-col gap-3.5" @keydown="onKeydown">
            <div class="mr-12 flex flex-col gap-1">
                <h2
                    id="picker-title"
                    class="m-0 text-[clamp(1.15rem,2vw,22px)] font-bold text-cream"
                >
                    Choose an image for “{{ slotTitle }}”
                </h2>
                <p class="m-0 text-[15px] font-medium text-muted">
                    Scroll left or right, pick the image that fits this box,
                    then place it. Images already on the board are not shown.
                </p>
            </div>

            <div class="relative">
                <button
                    type="button"
                    class="absolute top-[150px] left-1 z-10 grid size-[46px] cursor-pointer place-items-center rounded-full bg-gold text-2xl font-extrabold text-ink shadow-[0_6px_20px_rgb(0_0_0/.5)] max-sm:top-[calc(50%-35px)]"
                    aria-label="Previous images"
                    @click="step(-1)"
                >
                    ‹
                </button>
                <div
                    ref="track"
                    class="flex snap-x snap-mandatory gap-6 overflow-x-auto pt-1.5 pb-4"
                >
                    <button
                        v-for="(imageId, index) in available"
                        :key="imageId"
                        type="button"
                        class="flex w-[min(440px,78vw)] shrink-0 cursor-pointer snap-center flex-col gap-2 text-left text-cream"
                        :aria-pressed="selected === imageId"
                        @click="selected = imageId"
                        @dblclick="
                            selected = imageId;
                            confirm();
                        "
                    >
                        <div
                            class="aspect-[4/3] w-full rounded-lg bg-white bg-contain bg-center bg-no-repeat transition"
                            :class="
                                selected === imageId
                                    ? 'border-5 border-gold'
                                    : 'hover:shadow-[0_0_0_3px_rgb(255_210_31/.6)]'
                            "
                            :style="{
                                backgroundImage: `url(${images.get(imageId)?.url})`,
                            }"
                        />
                        <div
                            class="flex w-full justify-between gap-2 text-[13px] font-semibold"
                            :class="
                                selected === imageId
                                    ? 'text-gold'
                                    : 'text-muted'
                            "
                        >
                            <span class="whitespace-nowrap"
                                >Image {{ index + 1 }} of
                                {{ available.length }}</span
                            >
                            <span class="truncate">
                                {{ placedIn(imageId) ? 'In this box' : '' }}
                            </span>
                        </div>
                    </button>
                </div>
                <button
                    type="button"
                    class="absolute top-[150px] right-1 z-10 grid size-[46px] cursor-pointer place-items-center rounded-full bg-gold text-2xl font-extrabold text-ink shadow-[0_6px_20px_rgb(0_0_0/.5)] max-sm:top-[calc(50%-35px)]"
                    aria-label="Next images"
                    @click="step(1)"
                >
                    ›
                </button>
            </div>

            <div class="flex flex-wrap justify-end gap-3">
                <button
                    v-if="currentImageId"
                    type="button"
                    class="btn-ghost"
                    @click="slotId && emit('clear', slotId)"
                >
                    Empty this box
                </button>
                <button
                    type="button"
                    class="btn"
                    :disabled="!selected || selected === currentImageId"
                    @click="confirm"
                >
                    Place image
                </button>
            </div>
        </div>
    </GameModal>
</template>
