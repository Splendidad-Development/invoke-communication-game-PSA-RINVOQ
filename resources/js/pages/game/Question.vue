<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import GameLayout from '@/layouts/GameLayout.vue';
import { store } from '@/routes/answers';
import { groups } from '@/routes/game';
import type { Group, Player } from '@/types';

defineProps<{
    player: Player;
    group: Group;
    /** Abbreviations used in the personas, shown in the bottom-left corner. */
    footnote: string;
}>();

const answer = ref('');
</script>

<template>
    <Head :title="`Group ${group.number}`" />
    <GameLayout>
        <section
            class="relative flex min-h-screen flex-col items-center justify-center px-4 pt-8 pb-8 sm:pb-16"
        >
            <Form
                v-bind="store.form(group.number)"
                class="flex w-full max-w-[760px] flex-col gap-5 rounded-[22px] border border-white bg-night/82 p-[clamp(1.4rem,4vw,38px)] shadow-[0_20px_60px_rgb(0_0_0/.45)] backdrop-blur-xl"
                #default="{ errors, processing }"
            >
                <div
                    class="flex flex-col gap-1 rounded-[6px_22px_6px_22px] bg-gold px-5 py-4 text-ink"
                >
                    <span class="font-display text-[22px] leading-normal">
                        Group {{ group.number }}
                    </span>
                    <span class="text-[17px] font-medium">
                        {{ group.persona }}
                    </span>
                </div>

                <label
                    for="answer"
                    class="block text-[clamp(1.15rem,2.4vw,1.375rem)] font-bold text-cream"
                >
                    {{ group.question }}
                </label>
                <p
                    id="answer-example"
                    class="-mt-2 text-[15px] font-medium text-muted italic"
                >
                    {{ group.example }}
                </p>
                <div>
                    <textarea
                        id="answer"
                        v-model="answer"
                        v-focus
                        name="body"
                        aria-describedby="answer-example"
                        placeholder="Type your answer here"
                        class="block h-[170px] w-full resize-y rounded-[14px] border-2 border-gold bg-black/28 px-[18px] py-4 text-base font-medium text-cream placeholder:text-muted focus:outline-none"
                    />
                    <p v-if="errors.body" class="mt-2 font-semibold text-bad">
                        {{ errors.body }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4">
                    <Link :href="groups()" class="btn-ghost">Change group</Link>
                    <button
                        class="btn"
                        type="submit"
                        :disabled="!answer.trim() || processing"
                    >
                        Continue to the board
                    </button>
                </div>
            </Form>

            <!-- In the bottom-left corner from tablet up; on phones it follows the card. -->
            <p
                class="m-0 mt-6 max-w-[480px] self-start text-xs font-medium text-muted [text-shadow:0_1px_6px_rgb(0_0_0/.7)] sm:absolute sm:bottom-6 sm:left-6 sm:mt-0"
            >
                {{ footnote }}
            </p>
        </section>
    </GameLayout>
</template>
