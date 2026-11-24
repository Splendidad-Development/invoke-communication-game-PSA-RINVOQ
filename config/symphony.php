<?php

/*
|--------------------------------------------------------------------------
| Communication Game – Symphony Board
|--------------------------------------------------------------------------
|
| Content for the PsA (RINVOQ) communication game. Each section is a labelled area
| on the board with a number of boxes ("slots") and the image numbers that
| are correct for it. Images live in public/images/game/{number}.jpg.
|
| Source: "Content Communication game flow.pptx", PSA RINVOQ section (slides 29–44).
| Image N is the image on slide 28 + N, and each section is the box title on those slides.
|
| The board's slot order (columns → rows → sections → slots) is also the
| order in which the symphony is played back, one note per slot.
|
*/

return [

    'image_count' => 16,

    'images_path' => '/images/game',

    /*
    | persona: shown on the question card and the board.
    | caption: shown under the group's card on the group picker; bold parts are highlighted.
    */
    'groups' => [
        1 => [
            'persona' => 'Traditionalist: cycles TNFis, hesitant to use JAKis due to safety concerns (1st call)',
            'caption' => [
                ['text' => 'Traditionalist: '],
                ['text' => 'Cycles TNFis', 'bold' => true],
                ['text' => ', hesitant to use JAKis due to '],
                ['text' => 'safety concerns', 'bold' => true],
                ['text' => ' (1st call)'],
            ],
            'question' => 'How would you address this HCP’s challenge in your call? Describe your approach.',
        ],
        2 => [
            'persona' => 'Customizer: switch from 1L TNFis to IL-17is in 2L, reserves JAKis for 3L+ (2nd+ call)',
            'caption' => [
                ['text' => 'Customizer: '],
                ['text' => 'Switch from 1L TNFis to IL-17is in 2L', 'bold' => true],
                ['text' => ', reserves JAKis for 3L+ (2nd+ call)'],
            ],
            'question' => 'Which evidence would you lead with for this HCP, and why?',
        ],
        3 => [
            'persona' => 'Pioneer: prefers IL-17i over JAKis as 1L due to long-term safety (1st call)',
            'caption' => [
                ['text' => 'Pioneer: '],
                ['text' => 'Prefers IL-17i over JAKis', 'bold' => true],
                ['text' => ' as 1L due to long-term safety (1st call)'],
            ],
            'question' => 'How would you open a short call with this HCP? Write your key message.',
        ],
    ],

    'sections' => [
        'unmet' => ['title' => 'Unmet need', 'tone' => 'unmet', 'slots' => 1, 'answers' => [1]],
        'rtb' => ['title' => 'Reason to Believe', 'tone' => 'rtb', 'slots' => 1, 'answers' => [3]],
        'rtb_rapid' => ['title' => 'Reason to believe in Rapid efficacy across key PsA manifestations', 'tone' => 'rtb', 'slots' => 1, 'answers' => [4]],
        'ctrl' => ['title' => 'Control that’s fast, shown to last', 'tone' => 'ctrl', 'slots' => 3, 'answers' => [2, 5, 7]],
        'joint' => ['title' => 'Joint efficacy', 'tone' => 'rct', 'slots' => 1, 'answers' => [6]],
        'mda' => ['title' => 'MDA response (RCT)', 'tone' => 'rct', 'slots' => 2, 'answers' => [8, 9]],
        'joint_rct' => ['title' => 'Joint efficacy (RCT)', 'tone' => 'rct', 'slots' => 2, 'answers' => [10, 11]],
        'skin' => ['title' => 'Skin clearance (RCT)', 'tone' => 'rct', 'slots' => 2, 'answers' => [12, 13]],
        'mda_rwe' => ['title' => 'MDA (RWE)', 'tone' => 'rwe', 'slots' => 1, 'answers' => [14]],
        'multi' => ['title' => 'Multiple indications', 'tone' => 'rct', 'slots' => 1, 'answers' => [15]],
        'safety' => ['title' => 'Integrated safety', 'tone' => 'rct', 'slots' => 1, 'answers' => [16]],
    ],

    /*
    | The deck gives the boxes but not the column headings, so these headings
    | (taken from the deck's box titles and the CLM navigation) need sign-off.
    */
    'columns' => [
        [
            'key' => 'c1',
            'tone' => 'unmet',
            'title' => 'Unmet need',
            'description' => null,
            'rows' => [['unmet']],
        ],
        [
            'key' => 'c2',
            'tone' => 'how',
            'title' => 'How RINVOQ works',
            'description' => null,
            'rows' => [['rtb'], ['rtb_rapid']],
        ],
        [
            'key' => 'c3',
            'tone' => 'simpl',
            'title' => 'Control that’s fast, shown to last',
            'description' => null,
            'rows' => [['ctrl'], ['joint']],
        ],
        [
            'key' => 'c4',
            'tone' => 'simpl',
            'title' => 'PsA manifestations',
            'description' => null,
            'rows' => [['mda', 'joint_rct'], ['skin', 'mda_rwe']],
        ],
        [
            'key' => 'c5',
            'tone' => 'simpl',
            'title' => 'Safety data & experience',
            'description' => null,
            'rows' => [['multi'], ['safety']],
        ],
    ],

];
