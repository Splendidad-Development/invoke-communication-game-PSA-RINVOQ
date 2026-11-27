<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Arrangement;
use App\SymphonyBoard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Arrangement>
 */
class ArrangementFactory extends Factory
{
    /**
     * Define the model's default state: a random, fully filled board, scored by the real rules.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $board = app(SymphonyBoard::class);
        $placements = array_combine($board->slotIds(), fake()->shuffleArray($board->imageIds()));

        return [
            'answer_id' => Answer::factory(),
            'player_id' => fn (array $attributes): int => Answer::query()->findOrFail($attributes['answer_id'])->player_id,
            'group' => fn (array $attributes): int => Answer::query()->findOrFail($attributes['answer_id'])->group,
            'placements' => $placements,
            ...$board->score($placements),
        ];
    }

    /**
     * Indicate that every image is in a correct slot.
     */
    public function perfect(): static
    {
        $placements = self::perfectPlacements();

        return $this->state(fn (): array => [
            'placements' => $placements,
            'pattern' => str_repeat('1', count($placements)),
            'score' => count($placements),
        ]);
    }

    /**
     * A placement map where every slot holds a correct image.
     *
     * @return array<string, int>
     */
    public static function perfectPlacements(): array
    {
        // The correct board for an empty arrangement: every box filled, image copies included.
        return array_map(intval(...), app(SymphonyBoard::class)->solution([]));
    }
}
