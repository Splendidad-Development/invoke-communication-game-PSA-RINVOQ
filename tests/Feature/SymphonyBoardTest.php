<?php

use App\SymphonyBoard;
use Database\Factories\ArrangementFactory;

it('has 16 slots in board order', function () {
    $slotIds = app(SymphonyBoard::class)->slotIds();

    expect($slotIds)->toHaveCount(16)
        ->and($slotIds[0])->toBe('unmet-0')
        ->and(end($slotIds))->toBe('safety-0');
});

it('scores a perfect board as 16 with an all-correct pattern', function () {
    $result = app(SymphonyBoard::class)->score(ArrangementFactory::perfectPlacements());

    expect($result)->toBe(['score' => 16, 'pattern' => str_repeat('1', 16)]);
});

it('uses the PsA deck answers for each box', function () {
    expect(ArrangementFactory::perfectPlacements())->toBe([
        'unmet-0' => 1,
        'rtb-0' => 3,
        'rtb_rapid-0' => 4,
        'ctrl-0' => 2,
        'ctrl-1' => 5,
        'ctrl-2' => 7,
        'joint-0' => 6,
        'mda-0' => 8,
        'mda-1' => 9,
        'joint_rct-0' => 10,
        'joint_rct-1' => 11,
        'skin-0' => 12,
        'skin-1' => 13,
        'mda_rwe-0' => 14,
        'multi-0' => 15,
        'safety-0' => 16,
    ]);
});

it('marks the slots holding wrong images in the pattern', function () {
    $placements = ArrangementFactory::perfectPlacements();
    [$placements['unmet-0'], $placements['rtb-0']] = [$placements['rtb-0'], $placements['unmet-0']];

    $result = app(SymphonyBoard::class)->score($placements);

    expect($result['score'])->toBe(14)
        ->and($result['pattern'][0])->toBe('0')
        ->and($result['pattern'][1])->toBe('0');
});

it('accepts the control images in any order within their box group', function () {
    $placements = ArrangementFactory::perfectPlacements();
    [$placements['ctrl-0'], $placements['ctrl-2']] = [$placements['ctrl-2'], $placements['ctrl-0']];

    expect(app(SymphonyBoard::class)->score($placements)['score'])->toBe(16);
});

it('builds the correct arrangement, keeping the boxes the player got right', function () {
    $board = app(SymphonyBoard::class);
    $placements = ArrangementFactory::perfectPlacements();
    [$placements['ctrl-0'], $placements['ctrl-1']] = [$placements['ctrl-1'], $placements['ctrl-0']];
    [$placements['skin-0'], $placements['mda_rwe-0']] = [$placements['mda_rwe-0'], $placements['skin-0']];

    $solution = $board->solution($placements);

    expect(array_keys($solution))->toBe($board->slotIds())
        ->and($board->score($solution)['score'])->toBe(16)
        ->and($solution['ctrl-0'])->toBe($placements['ctrl-0'])
        ->and($solution['skin-1'])->toBe(13)
        ->and($solution['skin-0'])->toBe(12)
        ->and($solution['mda_rwe-0'])->toBe(14);
});

it('leaves the correct answers out of the client layout', function () {
    $layout = app(SymphonyBoard::class)->layout();

    expect(json_encode($layout))->not->toContain('answers')
        ->and(collect($layout)->flatMap(fn (array $column) => collect($column['rows'])->flatten(1)->pluck('slots')->flatten()))->toHaveCount(16);
});
