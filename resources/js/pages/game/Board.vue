<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { VueDraggable } from 'vue-draggable-plus';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import BoardColumnHeading from '@/components/game/BoardColumnHeading.vue';
import BoardSection from '@/components/game/BoardSection.vue';
import ColourKey from '@/components/game/ColourKey.vue';
import ImageLightbox from '@/components/game/ImageLightbox.vue';
import ImagePickerModal from '@/components/game/ImagePickerModal.vue';
import ImageTile from '@/components/game/ImageTile.vue';
import LeaderboardButton from '@/components/game/LeaderboardButton.vue';
import MistakesModal from '@/components/game/MistakesModal.vue';
import ResultModal from '@/components/game/ResultModal.vue';
import { TRAY, useBoardArrangement } from '@/composables/useBoardArrangement';
import { useStickyEdgeScroll } from '@/composables/useStickyEdgeScroll';
import { useSymphonyPlayer } from '@/composables/useSymphonyPlayer';
import { dragOptions, imageMap, RESULT_PLAYER_KEY } from '@/lib/game';
import GameLayout from '@/layouts/GameLayout.vue';
import { store as storeArrangement } from '@/routes/arrangements';
import type {
    ArrangementResult,
    BoardColumn,
    GameImage,
    Group,
    Player,
} from '@/types';

const props = defineProps<{
    player: Player;
    group: Group;
    answerId: number;
    layout: BoardColumn[];
    slotIds: string[];
    images: GameImage[];
    result: ArrangementResult | null;
}>();

const page = usePage();
const symphony = useSymphonyPlayer();
const imagesById = computed(() => imageMap(props.images));
const board = useBoardArrangement(
    props.slotIds,
    props.images.map((image) => image.id),
);

/** After submitting, the board shows the player's own arrangement with wrong boxes marked; they can switch to the correct one. */
type BoardView = 'correct' | 'mine';
const view = ref<BoardView>('mine');

function viewPlacements(): Record<string, number> | null {
    if (!props.result) {
        return null;
    }

    return view.value === 'correct'
        ? props.result.solution
        : props.result.placements;
}

// Deterministic on the server and during hydration; shuffled once mounted in the browser.
board.reset(viewPlacements(), { shuffled: false });
onMounted(() => board.reset(viewPlacements()));

function showView(next: BoardView): void {
    view.value = next;
    board.reset(viewPlacements());
}

const isLocked = computed(() => props.result !== null);
const isResultOpen = ref(props.result !== null);

const isSubmitting = ref(false);
const lightboxImage = ref<GameImage | null>(null);
const pickerSlotId = ref<string | null>(null);
const isMistakesOpen = ref(false);

// Inertia keeps this component mounted when moving between the board and its result,
// so reset the board whenever a different arrangement (or a fresh board) arrives.
watch(
    () => props.result?.id ?? null,
    () => {
        symphony.stop();
        view.value = 'mine';
        board.reset(viewPlacements());
        isResultOpen.value = props.result !== null;
        pickerSlotId.value = null;
        lightboxImage.value = null;
        isMistakesOpen.value = false;
    },
);
const submitError = computed(
    () => Object.values(page.props.errors ?? {})[0] ?? null,
);

const sectionTitles = computed(
    () =>
        new Map(
            props.layout.flatMap((column) =>
                column.rows.flatMap((row) =>
                    row.flatMap((section) =>
                        section.slots.map(
                            (slotId) => [slotId, section.title] as const,
                        ),
                    ),
                ),
            ),
        ),
);

/** A drag ends with a click on some browsers; ignore clicks right after a drop. */
let lastDragEndedAt = 0;
const topBar = ref<HTMLElement | null>(null);
const edgeScroll = useStickyEdgeScroll(topBar);

function onDragEnd(): void {
    lastDragEndedAt = Date.now();
    edgeScroll.stop();
}

function correctness(slotId: string): boolean | null {
    if (!props.result) {
        return null;
    }

    if (view.value === 'correct') {
        return true;
    }

    return props.result.pattern[props.slotIds.indexOf(slotId)] === '1';
}

const playingSlotId = computed(() =>
    symphony.playingKey.value === RESULT_PLAYER_KEY
        ? (props.slotIds[symphony.currentStep.value] ?? null)
        : null,
);

function openSlot(slotId: string): void {
    if (Date.now() - lastDragEndedAt < 250) {
        return;
    }

    const imageId = board.lists[slotId][0];

    if (isLocked.value) {
        if (imageId) {
            lightboxImage.value = imagesById.value.get(imageId) ?? null;
        }

        return;
    }

    pickerSlotId.value = slotId;
}

function zoomTrayTile(event: MouseEvent): void {
    if (Date.now() - lastDragEndedAt < 250) {
        return;
    }

    const tile = (event.target as HTMLElement).closest<HTMLElement>('.tile');

    if (tile) {
        lightboxImage.value =
            imagesById.value.get(Number(tile.dataset.image)) ?? null;
    }
}

function placedIn(imageId: number): { slotId: string; title: string } | null {
    const slotId = board.slotOf(imageId);

    return slotId
        ? { slotId, title: sectionTitles.value.get(slotId) ?? '' }
        : null;
}

function placeFromPicker(imageId: number, slotId: string): void {
    board.place(imageId, slotId);
    pickerSlotId.value = null;
}

function clearFromPicker(slotId: string): void {
    board.clearSlot(slotId);
    pickerSlotId.value = null;
}

function submit(): void {
    router.post(
        storeArrangement(props.answerId).url,
        { placements: board.placements.value },
        {
            onStart: () => {
                isSubmitting.value = true;
            },
            onFinish: () => {
                isSubmitting.value = false;
            },
        },
    );
}

const submitLabel = computed(() => {
    if (isSubmitting.value) {
        return 'Submitting…';
    }

    const remaining = props.slotIds.length - board.placedCount.value;

    return remaining > 0
        ? `Submit arrangement (${remaining} to go)`
        : 'Submit arrangement';
});

function showCorrectArrangement(): void {
    isResultOpen.value = false;
    showView('correct');
}

onBeforeUnmount(() => symphony.stop());
</script>

<template>
    <Head :title="`Group ${group.number} board`" />
    <GameLayout focused>
        <section class="flex min-h-screen flex-col pb-8">
            <!-- Sticky top bar with the tray of images still to place -->
            <div
                ref="topBar"
                class="sticky top-0 z-30 bg-night/94 pt-[env(safe-area-inset-top,0px)] shadow-[0_6px_24px_rgb(0_0_0/.35)] backdrop-blur-xl"
            >
                <div
                    class="flex flex-wrap items-center gap-4 px-[clamp(.8rem,2vw,24px)] py-3"
                >
                    <div class="flex min-w-0 flex-1 flex-col gap-0.5">
                        <b
                            class="font-display text-[22px] leading-normal font-normal text-gold"
                        >
                            {{ player.name }}
                        </b>
                        <small
                            class="truncate text-[13px] font-medium text-muted"
                        >
                            Group {{ group.number }} · {{ group.persona }}
                        </small>
                    </div>
                    <button
                        v-if="result?.mistakes.length"
                        type="button"
                        class="inline-flex shrink-0 cursor-pointer items-center gap-2 rounded-full bg-white/8 px-[17.5px] py-[10.5px] text-sm font-bold text-cream shadow-[inset_0_0_0_1.5px_rgb(255_210_31/.55)] hover:bg-gold hover:text-ink"
                        :class="isMistakesOpen && 'bg-gold text-ink'"
                        :aria-expanded="isMistakesOpen"
                        @click="isMistakesOpen = true"
                    >
                        Wrong answers
                        <span
                            class="grid min-w-5 place-items-center rounded-full bg-bad px-1.5 text-xs text-white"
                            >{{ result.mistakes.length }}</span
                        >
                    </button>
                    <LeaderboardButton />
                    <div class="text-[15px] font-bold whitespace-nowrap">
                        <span class="text-gold">{{
                            board.placedCount.value
                        }}</span>
                        / {{ slotIds.length }} placed
                    </div>
                </div>

                <div
                    v-if="!isLocked"
                    class="flex flex-col gap-2 border-t border-white/8 pt-2.5 pb-3.5"
                >
                    <p
                        class="m-0 px-[clamp(.8rem,2vw,24px)] text-[13px] font-medium text-muted"
                    >
                        Hover or tap an image to see it large, then drag it down
                        into the box where it belongs.
                    </p>
                    <VueDraggable
                        v-model="board.lists[TRAY]"
                        v-bind="dragOptions"
                        :data-list="TRAY"
                        class="flex min-h-[110px] [scrollbar-color:var(--color-gold)_transparent] gap-3 overflow-x-auto px-[clamp(.8rem,2vw,24px)] pt-1 pb-2.5"
                        aria-label="Images to place"
                        @start="edgeScroll.start"
                        @end="onDragEnd"
                        @click="zoomTrayTile"
                    >
                        <ImageTile
                            v-for="imageId in board.lists[TRAY]"
                            :key="imageId"
                            :image="imagesById.get(imageId)!"
                            @zoom="lightboxImage = $event"
                        />
                        <p
                            v-if="!board.lists[TRAY].length"
                            class="pointer-events-none self-center text-sm text-muted"
                        >
                            All images are on the board. Drag between boxes to
                            swap, click a box to change its image, or submit.
                        </p>
                    </VueDraggable>
                </div>
            </div>

            <!-- Brief -->
            <div
                class="mx-auto flex w-full max-w-[1680px] flex-col items-start gap-3.5 px-[clamp(.8rem,2vw,24px)] pt-[26px] pb-1.5"
            >
                <div class="flex max-w-full flex-wrap items-center">
                    <div
                        class="shrink-0 border-3 border-white bg-gold px-5 py-1.5 font-display text-2xl leading-normal text-ink"
                    >
                        Group {{ group.number }}
                    </div>
                    <div
                        class="min-w-[260px] flex-1 bg-linear-to-r from-gold-deep via-[#d8b54a] via-80% to-[#d8b54a]/0 py-3 pr-[60px] pl-6 text-base font-medium text-ink"
                    >
                        {{ group.persona }}
                    </div>
                </div>
                <p class="m-0 text-[clamp(1rem,1.4vw,19px)] font-semibold">
                    Select and order the most relevant brand story elements and
                    CLM tiles to address the assigned challenge.
                </p>
                <ColourKey v-if="isLocked" />
                <div v-if="isLocked" class="flex flex-wrap items-center gap-3">
                    <div
                        class="inline-flex w-fit gap-1 rounded-full bg-white/7 p-1"
                        role="group"
                        aria-label="Board view"
                    >
                        <button
                            v-for="option in [
                                { value: 'mine', label: 'Your answers' },
                                {
                                    value: 'correct',
                                    label: 'Correct arrangement',
                                },
                            ] as const"
                            :key="option.value"
                            type="button"
                            class="cursor-pointer rounded-full px-3.5 py-[7px] text-[13px] font-bold"
                            :class="
                                view === option.value
                                    ? 'bg-gold text-ink'
                                    : 'text-cream'
                            "
                            :aria-pressed="view === option.value"
                            @click="showView(option.value)"
                        >
                            {{ option.label }}
                        </button>
                    </div>
                    <p class="m-0 text-[13px] font-medium text-muted">
                        {{
                            view === 'correct'
                                ? 'Every image in the box where it belongs.'
                                : 'Your board: green boxes are right, pink boxes are wrong.'
                        }}
                    </p>
                </div>
            </div>

            <!-- Board -->
            <div
                class="mx-auto grid w-full max-w-[1680px] grid-cols-1 items-start px-[clamp(.8rem,2vw,24px)] pt-[18px] pb-2.5 sm:grid-cols-2 board:grid-cols-[190fr_150fr_400fr_400fr_190fr]"
                :class="isLocked ? 'gap-3.5' : 'gap-6'"
            >
                <div
                    v-for="column in layout"
                    :key="column.key"
                    class="flex min-w-0 flex-col items-center gap-3.5"
                    :class="[
                        column.rows.some((row) => row.length > 1) &&
                            'sm:col-span-2 board:col-span-1',
                        !isLocked &&
                            'rounded-2xl p-2 ring-1 ring-white/8 ring-inset',
                    ]"
                >
                    <BoardColumnHeading v-if="isLocked" :column="column" />
                    <div
                        :class="[
                            column.rows.some((row) => row.length > 1)
                                ? 'grid w-full grid-cols-2 items-start'
                                : 'flex w-full flex-col',
                            isLocked ? 'gap-3.5' : 'gap-x-3 gap-y-5',
                        ]"
                    >
                        <template
                            v-for="(row, rowIndex) in column.rows"
                            :key="rowIndex"
                        >
                            <BoardSection
                                v-for="section in row"
                                :key="section.key"
                                :class="row.length === 1 && 'col-span-2'"
                                :section="section"
                                :show-label="isLocked"
                                :lists="board.lists"
                                :images="imagesById"
                                :locked="isLocked"
                                :correctness="correctness"
                                :playing-slot-id="playingSlotId"
                                :target-slot-id="pickerSlotId"
                                @update:list="
                                    (slotId, list) =>
                                        (board.lists[slotId] = list)
                                "
                                @dropped="board.settleDrop"
                                @drag-start="edgeScroll.start"
                                @drag-end="onDragEnd"
                                @open="openSlot"
                                @zoom="lightboxImage = $event"
                                @remove="board.clearSlot"
                            />
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center gap-[22px] px-4 pt-[22px]">
                <div class="flex flex-wrap items-center justify-center gap-3.5">
                    <template v-if="!isLocked">
                        <button
                            type="button"
                            class="btn-ghost"
                            @click="board.reset()"
                        >
                            Clear board
                        </button>
                        <button
                            type="button"
                            class="btn"
                            :disabled="!board.isComplete.value || isSubmitting"
                            @click="submit"
                        >
                            {{ submitLabel }}
                        </button>
                    </template>
                    <button
                        v-else
                        type="button"
                        class="btn"
                        @click="isResultOpen = true"
                    >
                        See your score
                    </button>
                </div>
                <p
                    v-if="submitError"
                    class="m-0 text-center font-semibold text-bad"
                    role="alert"
                >
                    {{ submitError }}
                </p>
                <p class="m-0 text-center text-xs font-semibold text-muted">
                    INTERNAL USE ONLY – Not for promotional use
                </p>
            </div>
        </section>

        <ImagePickerModal
            :slot-id="pickerSlotId"
            :slot-title="
                pickerSlotId ? (sectionTitles.get(pickerSlotId) ?? '') : ''
            "
            :order="board.order.value"
            :images="imagesById"
            :current-image-id="
                pickerSlotId ? (board.lists[pickerSlotId][0] ?? null) : null
            "
            :placed-in="placedIn"
            @close="pickerSlotId = null"
            @place="placeFromPicker"
            @clear="clearFromPicker"
        />

        <MistakesModal
            v-if="result"
            :open="isMistakesOpen"
            :mistakes="result.mistakes"
            :images="imagesById"
            @close="isMistakesOpen = false"
            @zoom="lightboxImage = $event"
        />

        <ImageLightbox :image="lightboxImage" @close="lightboxImage = null" />

        <ResultModal
            v-if="result"
            :open="isResultOpen"
            :result="result"
            :player-name="player.name"
            @close="isResultOpen = false"
            @show-correct="showCorrectArrangement"
        />
    </GameLayout>
</template>
