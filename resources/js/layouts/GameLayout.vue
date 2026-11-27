<script setup lang="ts">
import LeaderboardButton from '@/components/game/LeaderboardButton.vue';
import LeaderboardModal from '@/components/game/LeaderboardModal.vue';

withDefaults(
    defineProps<{
        /** Blur the backdrop and hide the floating leaderboard button (the board has its own). */
        focused?: boolean;
    }>(),
    { focused: false },
);
</script>

<template>
    <div
        class="relative min-h-screen bg-night font-sans leading-normal text-cream antialiased"
    >
        <div class="fixed inset-0 -z-0 overflow-hidden" aria-hidden="true">
            <div
                class="absolute inset-0 bg-cover bg-right-top"
                style="background-image: url('/images/game/background.jpg')"
                :class="focused && 'scale-105 blur-[5px]'"
            />
            <div
                class="absolute inset-0 bg-linear-to-b from-[#f6b35a]/0 to-[#2b2340]"
            />
            <div
                class="absolute inset-0"
                :class="
                    focused
                        ? 'bg-night/78'
                        : 'bg-linear-to-b from-night/35 via-night/70 via-55% to-night/95'
                "
            />
        </div>

        <LeaderboardButton
            v-if="!focused"
            class="fixed top-[calc(env(safe-area-inset-top,0px)+24px)] left-6 z-40 bg-night/80 backdrop-blur-md"
        />

        <div class="relative">
            <slot />
        </div>

        <LeaderboardModal />
    </div>
</template>
