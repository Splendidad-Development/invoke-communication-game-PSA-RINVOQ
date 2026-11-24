<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import GameLayout from '@/layouts/GameLayout.vue';
import { store } from '@/routes/players';
</script>

<template>
    <Head title="Communication flow" />
    <GameLayout>
        <section
            class="flex min-h-screen flex-col items-center justify-end gap-5 px-4 pt-8 pb-[12vh] text-center"
            aria-labelledby="welcome-title"
        >
            <h1
                id="welcome-title"
                class="brush m-0 mb-[.145em] text-[clamp(2.6rem,6.4vw,5.75rem)]"
            >
                Communication Flow
            </h1>
            <p
                class="m-0 text-[clamp(1rem,1.6vw,1.375rem)] font-semibold [text-shadow:0_2px_12px_rgb(0_0_0/.6)]"
            >
                Build the PsA brand story, then hear how it sounds.
            </p>

            <Form
                v-bind="store.form()"
                autocomplete="off"
                class="w-full max-w-[520px]"
                #default="{ errors, processing }"
            >
                <div
                    class="flex items-center gap-2.5 rounded-full border border-white bg-night/75 py-2 pr-2 pl-6 shadow-[0_10px_40px_rgb(0_0_0/.4)] backdrop-blur-md"
                >
                    <label for="player-name" class="sr-only">Your name</label>
                    <input
                        id="player-name"
                        v-focus
                        name="name"
                        maxlength="40"
                        placeholder="Enter your name"
                        required
                        class="min-w-0 flex-1 bg-transparent text-[17px] font-semibold text-cream placeholder:text-muted focus:outline-none"
                    />
                    <button class="btn" type="submit" :disabled="processing">
                        Start
                    </button>
                </div>
                <p v-if="errors.name" class="mt-3 font-semibold text-bad">
                    {{ errors.name }}
                </p>
            </Form>
        </section>
    </GameLayout>
</template>
