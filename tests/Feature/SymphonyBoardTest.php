<?php

use App\SymphonyBoard;
use Database\Factories\ArrangementFactory;

it('has 16 slots in board order', function () {
    $slotIds = app(SymphonyBoard::class)->slotIds();

    expect($slotIds)->toHaveCount(16)
        ->and($slotIds[0])->toBe('unmet-0')
        ->and(end($slotIds))->toBe('multi-0');
});

it('scores a perfect board as 16 with an all-correct pattern', function () {
    $result = app(SymphonyBoard::class)->score(ArrangementFactory::perfectPlacements());

    expect($result)->toBe(['score' => 16, 'pattern' => str_repeat('1', 16)]);
});

it('uses the PsA deck answers for each box', function () {
    expect(ArrangementFactory::perfectPlacements())->toBe([
        'unmet-0' => 1,
        'rtb-0' => 3,
        'ctrl-0' => 2,
        'ctrl-1' => 5,
        'joint-0' => 6,
        'joint-1' => 11,
        'rtb_rapid-0' => 4,
        'mda-0' => 8,
        'mda-1' => 9,
        'joint_rct-0' => 10,
        'joint_rct-1' => 7,
        'skin-0' => 12,
        'skin-1' => 13,
        'mda_rwe-0' => 14,
        'safety-0' => 16,
        'multi-0' => 15,
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
    [$placements['ctrl-0'], $placements['ctrl-1']] = [$placements['ctrl-1'], $placements['ctrl-0']];

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

it('shows image 11 on tile 7, its second copy', function () {
    $images = collect(app(SymphonyBoard::class)->images())->keyBy('id');

    expect($images)->toHaveCount(16)
        ->and($images[7]['url'])->toBe($images[11]['url'])
        ->and($images[7]['url'])->toEndWith('/11.jpg');
});

it('scores either copy of image 11 in either of its boxes', function () {
    $placements = ArrangementFactory::perfectPlacements();
    [$placements['joint-1'], $placements['joint_rct-1']] = [$placements['joint_rct-1'], $placements['joint-1']];

    expect($placements['joint-1'])->toBe(7)
        ->and(app(SymphonyBoard::class)->score($placements)['score'])->toBe(16);
});

it('counts image 11 only once in a box group that needs it once', function () {
    $board = app(SymphonyBoard::class);
    $placements = ArrangementFactory::perfectPlacements();
    // Both copies in Joint efficacy; its 6 goes where the second copy belonged.
    [$placements['joint-0'], $placements['joint_rct-1']] = [$placements['joint_rct-1'], $placements['joint-0']];

    $result = $board->score($placements);
    $solution = $board->solution($placements);

    expect([$placements['joint-0'], $placements['joint-1'], $placements['joint_rct-1']])->toBe([7, 11, 6])
        ->and($result['score'])->toBe(14)
        ->and(collect($board->mistakes($placements))->pluck('belongsIn', 'slotId')->all())->toBe([
            'joint-1' => 'Joint efficacy or Joint efficacy (RCT)',
            'joint_rct-1' => 'Joint efficacy',
        ])
        ->and($board->score($solution)['score'])->toBe(16)
        ->and([$solution['joint-0'], $solution['joint-1'], $solution['joint_rct-1']])->toBe([7, 6, 11])
        ->and(array_count_values($solution))->each->toBe(1);
});
