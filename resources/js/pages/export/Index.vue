<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import GameLayout from '@/layouts/GameLayout.vue';
import { excel, pdf } from '@/routes/export';

type ExportAnswer = {
    group: number;
    persona: string;
    body: string;
    answeredAt: string;
    boards: number;
    bestScore: number | null;
};

type ExportPlayer = {
    id: number;
    name: string;
    joinedAt: string;
    answers: ExportAnswer[];
};

const props = defineProps<{
    title: string;
    players: ExportPlayer[];
    /** Boxes on the board: what a best score is out of. */
    totalSlots: number;
}>();

const answerCount = computed(() =>
    props.players.reduce((total, player) => total + player.answers.length, 0),
);
const boardCount = computed(() =>
    props.players.reduce(
        (total, player) =>
            total +
            player.answers.reduce((sum, answer) => sum + answer.boards, 0),
        0,
    ),
);
</script>

<template>
    <Head title="Export" />
    <GameLayout focused>
        <section
            class="mx-auto flex min-h-screen w-full max-w-[1280px] flex-col gap-6 px-4 py-10 sm:px-6"
        >
            <header class="flex flex-wrap items-end justify-between gap-4">
                <div class="flex flex-col gap-1">
                    <h1
                        class="m-0 font-display text-[clamp(1.75rem,3vw,2.5rem)] leading-normal font-normal text-gold"
                    >
                        Player export
                    </h1>
                    <p class="m-0 text-sm font-medium text-muted">
                        {{ title }} · {{ players.length }} players ·
                        {{ answerCount }} answers · {{ boardCount }} boards
                        submitted · times in UTC
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a :href="pdf().url" class="btn" download> Download PDF </a>
                    <a :href="excel().url" class="btn-ghost py-3.5" download>
                        Download Excel
                    </a>
                </div>
            </header>

            <div
                class="overflow-x-auto rounded-[18px] border border-white/15 bg-night/82 shadow-[0_20px_60px_rgb(0_0_0/.45)] backdrop-blur-xl"
            >
                <table class="w-full min-w-[880px] border-collapse text-left">
                    <thead
                        class="text-xs font-bold tracking-wide text-gold uppercase"
                    >
                        <tr class="border-b border-white/15">
                            <th class="px-4 py-3">Player</th>
                            <th class="px-4 py-3">Group</th>
                            <th class="px-4 py-3">HCP profile</th>
                            <th class="px-4 py-3">Answer</th>
                            <th class="px-4 py-3 whitespace-nowrap">
                                Answered
                            </th>
                            <th class="px-4 py-3 text-center">Boards</th>
                            <th class="px-4 py-3 text-center whitespace-nowrap">
                                Best score
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-cream">
                        <template v-for="player in players" :key="player.id">
                            <template v-if="player.answers.length">
                                <tr
                                    v-for="(answer, index) in player.answers"
                                    :key="index"
                                    class="border-b border-white/8 align-top"
                                >
                                    <td
                                        v-if="index === 0"
                                        :rowspan="player.answers.length"
                                        class="border-r border-white/8 px-4 py-3"
                                    >
                                        <b class="block font-bold">{{
                                            player.name
                                        }}</b>
                                        <small class="text-xs text-muted">
                                            Joined {{ player.joinedAt }}
                                        </small>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {{ answer.group }}
                                    </td>
                                    <td
                                        class="max-w-[260px] px-4 py-3 text-muted"
                                    >
                                        {{ answer.persona }}
                                    </td>
                                    <td
                                        class="max-w-[420px] px-4 py-3 break-words whitespace-pre-wrap"
                                    >
                                        {{ answer.body }}
                                    </td>
                                    <td
                                        class="px-4 py-3 whitespace-nowrap text-muted"
                                    >
                                        {{ answer.answeredAt }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {{ answer.boards }}
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-bold whitespace-nowrap"
                                    >
                                        <template
                                            v-if="answer.bestScore !== null"
                                        >
                                            <span class="text-gold">{{
                                                answer.bestScore
                                            }}</span>
                                            / {{ totalSlots }}
                                        </template>
                                        <span v-else class="text-muted">–</span>
                                    </td>
                                </tr>
                            </template>
                            <tr v-else class="border-b border-white/8">
                                <td class="border-r border-white/8 px-4 py-3">
                                    <b class="block font-bold">{{
                                        player.name
                                    }}</b>
                                    <small class="text-xs text-muted">
                                        Joined {{ player.joinedAt }}
                                    </small>
                                </td>
                                <td
                                    colspan="6"
                                    class="px-4 py-3 text-muted italic"
                                >
                                    No answer yet
                                </td>
                            </tr>
                        </template>
                        <tr v-if="!players.length">
                            <td
                                colspan="7"
                                class="px-4 py-10 text-center text-muted"
                            >
                                No players yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </GameLayout>
</template>
