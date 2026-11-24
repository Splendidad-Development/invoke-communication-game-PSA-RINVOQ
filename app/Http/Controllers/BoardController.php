<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Arrangement;
use App\SymphonyBoard;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BoardController extends Controller
{
    public function show(Request $request, SymphonyBoard $board, Answer $answer): Response
    {
        abort_unless($answer->player_id === $this->currentPlayer($request)->id, 403);

        return self::render($board, $answer);
    }

    /**
     * Render the board, optionally with a submitted arrangement shown as the result.
     */
    public static function render(SymphonyBoard $board, Answer $answer, ?Arrangement $arrangement = null): Response
    {
        $answer->loadMissing('player');

        return Inertia::render('game/Board', [
            'player' => $answer->player->only('id', 'name'),
            'group' => ['number' => $answer->group, ...$board->group($answer->group)],
            'answerId' => $answer->id,
            'layout' => $board->layout(),
            'slotIds' => $board->slotIds(),
            'images' => $board->images(),
            'result' => $arrangement === null ? null : [
                ...$arrangement->only('id', 'placements', 'pattern', 'score'),
                'mistakes' => $board->mistakes($arrangement->placements),
                'solution' => $board->solution($arrangement->placements),
            ],
        ]);
    }
}
