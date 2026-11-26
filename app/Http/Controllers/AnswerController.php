<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnswerRequest;
use App\SymphonyBoard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AnswerController extends Controller
{
    public function create(Request $request, SymphonyBoard $board, int $group): Response
    {
        return Inertia::render('game/Question', [
            'player' => $this->currentPlayer($request)->only('id', 'name'),
            'group' => ['number' => $group, ...$board->group($group)],
        ]);
    }

    public function store(StoreAnswerRequest $request, int $group): RedirectResponse
    {
        $answer = $this->currentPlayer($request)->answers()->create([
            'group' => $group,
            'body' => $request->validated('body'),
        ]);

        return redirect()->route('board.show', $answer);
    }
}
