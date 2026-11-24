<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import GameModal from '@/components/game/GameModal.vue';
import PlayIcon from '@/components/game/PlayIcon.vue';
import SymphonyNotes from '@/components/game/SymphonyNotes.vue';
import { useLeaderboard } from '@/composables/useLeaderboard';
import { useSymphonyPlayer } from '@/composables/useSymphonyPlayer';
import { patternToNotes } from '@/lib/symphony';

const leaderboard = useLeaderboard();
const player = useSymphonyPlayer();

const filters = [
    { value: 'all', label: 'All groups' },
    { value: '1', label: 'Group 1' },
    { value: '2', label: 'Group 2' },
    { value: '3', label: 'Group 3' },
] as const;

const filter = ref<(typeof filters)[number]['value']>('all');

const rows = computed(() =>
    (leaderboard.entries.value ?? []).filter(
        (entry) =>
            filter.value === 'all' || String(entry.group) === filter.value,
    ),
);

const summary = computed(() => {
    const plays = rows.value.length;

    return plays ? `${plays} ${plays === 1 ? 'play' : 'plays'}` : '';
});

const playKey = (id: number): string => `leaderboard-${id}`;

function selectFilter(value: (typeof filters)[number]['value']): void {
    player.stop();
    filter.value = value;
}

function close(): void {
    player.stop();
    leaderboard.close();
}

watch(leaderboard.isOpen, (isOpen) => {
    if (!isOpen) {
        player.stop();
    }
});

const rankColour = (index: number): string =>
    ['text-gold', 'text-[#d9d9e3]', 'text-sunset'][index] ?? 'text-muted';

const isPlaying = (id: number): boolean =>
    player.playingKey.value === playKey(id);
</script>

<template>
    <GameModal
        :open="leaderboard.isOpen.value"
        labelledby="leaderboard-title"
        panel-class="flex max-w-[780px] flex-col gap-3.5 px-[26px] pt-6 pb-[22px]"
        @close="close"
    >
        <div class="mr-12 flex items-center gap-3">
            <h2
                id="leaderboard-title"
                class="m-0 font-display text-[34px] leading-normal font-normal text-gold"
            >
                Leaderboard
            </h2>
            <span class="text-sm font-medium text-muted">{{ summary }}</span>
        </div>

        <div
            class="inline-flex w-fit flex-wrap gap-1 rounded-full bg-white/7 p-1"
            role="group"
            aria-label="Filter by group"
        >
            <button
                v-for="option in filters"
                :key="option.value"
                type="button"
                class="cursor-pointer rounded-full px-3.5 py-[7px] text-[13px] font-bold"
                :class="
                    filter === option.value ? 'bg-gold text-ink' : 'text-cream'
                "
                :aria-pressed="filter === option.value"
                @click="selectFilter(option.value)"
            >
                {{ option.label }}
            </button>
        </div>

        <div class="max-h-[60vh] overflow-auto">
            <table v-if="rows.length" class="w-full border-collapse">
                <thead>
                    <tr
                        class="text-left text-[11px] font-bold tracking-[.06em] text-muted uppercase"
                    >
                        <th class="w-11 border-b border-white/12 px-2 py-1">
                            #
                        </th>
                        <th
                            class="w-[200px] border-b border-white/12 px-2 py-1"
                        >
                            Name
                        </th>
                        <th
                            class="hidden w-[100px] border-b border-white/12 px-2 py-1 sm:table-cell"
                        >
                            Group
                        </th>
                        <th
                            class="w-[110px] border-b border-white/12 px-2 py-1"
                        >
                            Score
                        </th>
                        <th
                            class="w-[170px] border-b border-white/12 px-2 py-1"
                        >
                            Notes
                        </th>
                        <th
                            class="w-[60px] border-b border-white/12 py-1 pr-0 pl-4"
                        >
                            <span class="sr-only">Play</span>
                        </th>
                        <!-- Spare width goes here, so the columns keep the design's widths. -->
                        <th
                            class="border-b border-white/12"
                            aria-hidden="true"
                        />
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(entry, index) in rows"
                        :key="entry.id"
                        :class="entry.mine && 'bg-gold/8'"
                    >
                        <td
                            class="border-b border-white/6 px-2 py-2.5 font-display text-[22px] leading-normal"
                            :class="rankColour(index)"
                        >
                            {{ index + 1 }}
                        </td>
                        <td
                            class="border-b border-white/6 px-2 py-2.5 text-base font-bold text-cream"
                        >
                            {{ entry.name }}
                            <small
                                v-if="entry.mine"
                                class="block text-xs font-medium text-muted"
                            >
                                you
                            </small>
                        </td>
                        <td
                            class="hidden border-b border-white/6 px-2 py-2.5 sm:table-cell"
                        >
                            <span
                                class="rounded-full bg-white/10 px-2.5 py-1 text-xs font-bold whitespace-nowrap text-cream"
                            >
                                Group {{ entry.group }}
                            </span>
                        </td>
                        <td
                            class="border-b border-white/6 px-2 py-2.5 font-display text-2xl leading-normal whitespace-nowrap text-gold"
                        >
                            {{ entry.score
                            }}<small class="text-sm text-cream"
                                >/{{ entry.pattern.length }}</small
                            >
                        </td>
                        <td class="border-b border-white/6 px-2 py-2.5">
                            <SymphonyNotes
                                :notes="patternToNotes(entry.pattern)"
                                :active-index="
                                    isPlaying(entry.id)
                                        ? player.currentStep.value
                                        : -1
                                "
                            />
                        </td>
                        <td class="border-b border-white/6 py-2.5 pr-0 pl-4">
                            <button
                                type="button"
                                class="mx-auto grid size-[38px] cursor-pointer place-items-center rounded-full text-ink"
                                :class="
                                    isPlaying(entry.id) ? 'bg-white' : 'bg-gold'
                                "
                                :aria-pressed="isPlaying(entry.id)"
                                :aria-label="`Play ${entry.name}’s arrangement`"
                                @click="
                                    player.toggle(
                                        playKey(entry.id),
                                        patternToNotes(entry.pattern),
                                    )
                                "
                            >
                                <PlayIcon
                                    class="size-[15px]"
                                    :playing="isPlaying(entry.id)"
                                />
                            </button>
                        </td>
                        <td
                            class="border-b border-white/6"
                            aria-hidden="true"
                        />
                    </tr>
                </tbody>
            </table>

            <p v-else class="px-4 py-8 text-center text-muted">
                {{
                    leaderboard.isLoading.value &&
                    leaderboard.entries.value === null
                        ? 'Loading scores…'
                        : 'No scores yet. Finish a board to be the first on the leaderboard.'
                }}
            </p>
        </div>

        <p class="m-0 text-[13px] font-medium text-muted">
            Scores are shared with everyone who opens this game. Press play to
            hear a player’s arrangement: gold bars are correct notes, pink bars
            are sour ones.
        </p>
    </GameModal>
</template>
