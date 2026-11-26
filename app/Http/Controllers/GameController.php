<?php

namespace App\Http\Controllers;

use App\SymphonyBoard;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GameController extends Controller
{
    public function welcome(): Response
    {
        return Inertia::render('game/Welcome');
    }

    public function groups(Request $request, SymphonyBoard $board): Response
    {
        return Inertia::render('game/Groups', [
            'player' => $this->currentPlayer($request)->only('id', 'name'),
            'groups' => $board->groupCards(),
            'footnote' => $board->footnote(),
        ]);
    }
}
