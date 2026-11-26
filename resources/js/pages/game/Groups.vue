<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import GameLayout from '@/layouts/GameLayout.vue';
import { create } from '@/routes/answers';
import type { GroupCard, Player } from '@/types';

defineProps<{
    player: Player;
    groups: GroupCard[];
    /** Abbreviations used in the personas, shown in the bottom-left corner. */
    footnote: string;
}>();
</script>

<template>
    <Head title="Choose your group" />
    <GameLayout>
        <section
            class="relative flex min-h-screen flex-col items-center justify-center gap-3.5 px-4 pt-24 pb-8 sm:pt-36"
            aria-labelledby="groups-title"
        >
            <h1
                id="groups-title"
                class="m-0 text-center font-display text-[clamp(2.25rem,4vw,3.5rem)] leading-normal font-normal text-gold [text-shadow:0_3px_20px_rgb(0_0_0/.6)]"
            >
                Hi {{ player.name }}
            </h1>
            <p
                class="m-0 mb-[34px] text-center text-xl font-medium [text-shadow:0_2px_10px_rgb(0_0_0/.7)]"
            >
                Choose your group
            </p>

            <div
                class="grid w-full max-w-80 grid-cols-1 justify-center gap-5 sm:max-w-none sm:grid-cols-[repeat(3,220px)]"
            >
                <div
                    v-for="group in groups"
                    :key="group.number"
                    class="relative flex flex-col items-center gap-4"
                >
                    <Link
                        :href="create(group.number)"
                        class="flex h-[190px] w-full flex-col items-center justify-center gap-1.5 rounded-[18px] border border-gold/25 bg-night/78 text-cream shadow-[0_14px_40px_rgb(0_0_0/.35)] backdrop-blur-[10px] transition hover:border-2 hover:border-gold focus-visible:border-2 focus-visible:border-gold"
                    >
                        <span
                            class="font-display text-[64px] leading-normal text-gold"
                        >
                            {{ group.number }}
                        </span>
                        <span class="text-lg font-bold">
                            Group {{ group.number }}
                        </span>
                    </Link>
                    <p
                        class="m-0 w-[211px] text-center text-xs font-medium text-muted opacity-70 sm:absolute sm:top-full sm:mt-4"
                    >
                        <template
                            v-for="(part, index) in group.caption"
                            :key="index"
                        >
                            <b v-if="part.bold" class="font-bold">{{
                                part.text
                            }}</b>
                            <template v-else>{{ part.text }}</template>
                        </template>
                    </p>
                </div>
            </div>

            <!-- In the bottom-left corner from tablet up; on phones it follows the stacked group cards. -->
            <p
                class="m-0 mt-8 max-w-[480px] self-start text-xs font-medium text-muted [text-shadow:0_1px_6px_rgb(0_0_0/.7)] sm:absolute sm:bottom-6 sm:left-6 sm:mt-0"
            >
                {{ footnote }}
            </p>
        </section>
    </GameLayout>
</template>
