<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import GameModal from '@/components/game/GameModal.vue';
import PlayIcon from '@/components/game/PlayIcon.vue';
import SymphonyNotes from '@/components/game/SymphonyNotes.vue';
import TrophyIcon from '@/components/game/TrophyIcon.vue';
import { useLeaderboard } from '@/composables/useLeaderboard';
import { useSymphonyPlayer } from '@/composables/useSymphonyPlayer';
import { RESULT_PLAYER_KEY, verdictFor } from '@/lib/game';
import { patternToNotes } from '@/lib/symphony';
import { groups, welcome } from '@/routes/game';
import type { ArrangementResult } from '@/types';

const props = defineProps<{
    open: boolean;
    result: ArrangementResult;
    playerName: string;
}>();

const emit = defineEmits<{ close: []; showCorrect: [] }>();

const player = useSymphonyPlayer();
const leaderboard = useLeaderboard();

const notes = computed(() => patternToNotes(props.result.pattern));
const isPlaying = computed(() => player.playingKey.value === RESULT_PLAYER_KEY);
const hasPlayed = computed(
    () => isPlaying.value || player.currentStep.value >= 0,
);

function close(): void {
    player.stop();
    emit('close');
}

function showCorrect(): void {
    player.stop();
    emit('showCorrect');
}

function openLeaderboard(): void {
    close();
    leaderboard.open();
}
</script>

<template>
    <GameModal
        :open="open"
        labelledby="result-score"
        :show-close="false"
        panel-class="flex max-w-[620px] flex-col items-center gap-3.5 border border-white bg-[radial-gradient(ellipse_at_center,#3a2c3f_0%,#1b1c38_60%)] px-[clamp(1rem,4vw,32px)] pt-[34px] pb-[30px] text-center"
        @close="close"
    >
        <div
            id="result-score"
            class="flex items-baseline gap-1 font-display leading-normal"
        >
            <span class="text-[clamp(4rem,14vw,112px)] text-gold">{{
                result.score
            }}</span>
            <span class="text-[clamp(2rem,6vw,50px)] text-cream"
                >/{{ notes.length }}</span
            >
        </div>
        <p class="m-0 text-lg font-semibold">
            {{ verdictFor(result.score, notes.length, playerName) }}
        </p>

        <SymphonyNotes
            :notes="notes"
            size="large"
            reveal-as-played
            :active-index="
                isPlaying
                    ? player.currentStep.value
                    : hasPlayed
                      ? notes.length
                      : -1
            "
        />

        <button
            type="button"
            class="btn"
            data-autofocus
            @click="player.toggle(RESULT_PLAYER_KEY, notes)"
        >
            <PlayIcon class="size-5" :playing="isPlaying" />
            <span>{{ isPlaying ? 'Stop' : 'Play your arrangement' }}</span>
        </button>

        <div class="flex flex-wrap justify-center gap-3">
            <button type="button" class="btn-ghost" @click="close">
                Review your board
            </button>
            <button type="button" class="btn-ghost" @click="showCorrect">
                Correct answer
            </button>
            <Link :href="welcome()" class="btn-ghost" @click="player.stop()">
                Restart
            </Link>
            <Link :href="groups()" class="btn-ghost" @click="player.stop()">
                Back to groups
            </Link>
            <button type="button" class="btn-ghost" @click="openLeaderboard">
                <TrophyIcon class="size-[18px]" />
                Leaderboard
            </button>
        </div>
    </GameModal>
</template>
