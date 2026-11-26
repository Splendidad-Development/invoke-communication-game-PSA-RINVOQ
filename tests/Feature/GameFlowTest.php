<?php

use App\Models\Answer;
use App\Models\Arrangement;
use App\Models\Player;
use Database\Factories\ArrangementFactory;
use Inertia\Testing\AssertableInertia as Assert;

function playAs(Player $player): void
{
    test()->withSession(['player_id' => $player->id]);
}

it('shows the name screen', function () {
    $this->get(route('game.welcome'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('game/Welcome'));
});

it('registers a player and remembers them in the session', function () {
    $this->post(route('players.store'), ['name' => '  Sara  '])
        ->assertRedirect(route('game.groups'))
        ->assertSessionHas('player_id');

    expect(Player::query()->sole()->name)->toBe('Sara');
});

it('requires a name', function () {
    $this->post(route('players.store'), ['name' => ''])->assertSessionHasErrors('name');

    expect(Player::query()->count())->toBe(0);
});

it('sends visitors without a player back to the name screen', function () {
    $this->get(route('game.groups'))->assertRedirect(route('game.welcome'));
});

it('shows the group picker and the group question', function () {
    playAs($player = Player::factory()->create());

    $this->get(route('game.groups'))
        ->assertInertia(fn (Assert $page) => $page->component('game/Groups')
            ->where('player.name', $player->name)
            ->has('groups', 3)
            ->where('groups.1.number', 2)
            ->where('footnote', config('symphony.footnote'))
            ->where('groups.1.caption.1', ['text' => 'Bimzelx driven by strong perception of IL-17is', 'bold' => true]));

    $this->get(route('answers.create', 2))
        ->assertInertia(fn (Assert $page) => $page->component('game/Question')
            ->where('group.number', 2)
            ->where('group.persona', config('symphony.groups.2.persona'))
            ->where('group.question', config('symphony.groups.2.question'))
            ->where('group.example', config('symphony.groups.2.example'))
            ->where('group.footnote', config('symphony.groups.2.footnote')));
});

it('returns not found for a group that does not exist', function () {
    playAs(Player::factory()->create());

    $this->get('/groups/4')->assertNotFound();
});

it('saves the challenge answer and opens the board', function () {
    playAs($player = Player::factory()->create());

    $response = $this->post(route('answers.store', 1), ['body' => 'Lead with the safety data.']);

    $answer = $player->answers()->sole();
    $response->assertRedirect(route('board.show', $answer));
    expect($answer->group)->toBe(1)->and($answer->body)->toBe('Lead with the safety data.');

    $this->get(route('board.show', $answer))
        ->assertInertia(fn (Assert $page) => $page->component('game/Board')
            ->has('slotIds', 16)
            ->has('images', 16)
            ->where('result', null));
});

it('requires an answer before the board', function () {
    playAs(Player::factory()->create());

    $this->post(route('answers.store', 1), ['body' => ''])->assertSessionHasErrors('body');
});

it('does not show another player’s board', function () {
    playAs(Player::factory()->create());

    $this->get(route('board.show', Answer::factory()->create()))->assertForbidden();
});

it('saves the arrangement with the order of images and the server-side score', function () {
    $answer = Answer::factory()->create(['group' => 3]);
    playAs($answer->player);
    $placements = ArrangementFactory::perfectPlacements();
    [$placements['unmet-0'], $placements['safety-0']] = [$placements['safety-0'], $placements['unmet-0']];

    $response = $this->post(route('arrangements.store', $answer), ['placements' => $placements]);

    $arrangement = Arrangement::query()->sole();
    $response->assertRedirect(route('arrangements.show', $arrangement));
    expect($arrangement->placements)->toBe($placements)
        ->and($arrangement->score)->toBe(14)
        ->and($arrangement->pattern)->toBe('0'.str_repeat('1', 13).'01')
        ->and($arrangement->group)->toBe(3)
        ->and($arrangement->player_id)->toBe($answer->player_id);

    $this->get(route('arrangements.show', $arrangement))
        ->assertInertia(fn (Assert $page) => $page->component('game/Board')
            ->where('result.score', 14)
            ->where('result.mistakes', [
                ['slotId' => 'unmet-0', 'imageId' => 16, 'placedIn' => 'Unmet need', 'belongsIn' => 'Integrated safety'],
                ['slotId' => 'safety-0', 'imageId' => 1, 'placedIn' => 'Integrated safety', 'belongsIn' => 'Unmet need'],
            ])
            ->where('result.solution', ArrangementFactory::perfectPlacements()));
});

it('rejects a board with missing or repeated images', function (array $placements) {
    $answer = Answer::factory()->create();
    playAs($answer->player);

    $this->post(route('arrangements.store', $answer), ['placements' => $placements])->assertSessionHasErrors();

    expect(Arrangement::query()->count())->toBe(0);
})->with([
    'missing slot' => fn () => array_slice(ArrangementFactory::perfectPlacements(), 0, 15, true),
    'repeated image' => fn () => array_replace(ArrangementFactory::perfectPlacements(), ['unmet-0' => 2]),
    'unknown image' => fn () => array_replace(ArrangementFactory::perfectPlacements(), ['unmet-0' => 99]),
]);

it('does not let a player submit a board for someone else’s answer', function () {
    playAs(Player::factory()->create());

    $this->post(route('arrangements.store', Answer::factory()->create()), ['placements' => ArrangementFactory::perfectPlacements()])
        ->assertForbidden();
});

it('loads the leaderboard best first only when asked for', function () {
    $answer = Answer::factory()->create();
    playAs($answer->player);
    Arrangement::factory()->for($answer)->create(['player_id' => $answer->player_id, 'group' => $answer->group, 'score' => 5, 'pattern' => str_repeat('0', 16)]);
    Arrangement::factory()->perfect()->create();

    $this->get(route('game.groups'))
        ->assertInertia(fn (Assert $page) => $page->missing('leaderboard'));

    $this->get(route('game.groups'))
        ->assertInertia(fn (Assert $page) => $page
            ->reloadOnly('leaderboard', fn (Assert $reload) => $reload
                ->has('leaderboard', 2)
                ->where('leaderboard.0.score', 16)
                ->where('leaderboard.0.mine', false)
                ->where('leaderboard.1.mine', true)));
});
