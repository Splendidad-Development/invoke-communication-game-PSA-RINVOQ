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
| Images come from "Content Communication game flow.pptx", PSA RINVOQ section
| (image N is the image on slide 28 + N). The board itself follows the SpA
| Narrative Train-the-Trainer deck, RINVOQ PsA answer board (slides 29–31).
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
            'persona' => 'Customizer: Prefers TNFis and IL-17is as 1L and reserves JAKis for 3L+ AT (2nd+ call)',
            'caption' => [
                ['text' => 'Customizer: '],
                ['text' => 'Prefers TNFis and IL-17is', 'bold' => true],
                ['text' => ' as 1L and reserves JAKis for 3L+ AT (2nd+ call)'],
            ],
            'question' => 'How would you address this HCP’s challenge in your call? Describe your approach.',
        ],
        2 => [
            'persona' => 'Customizer: Influenced by rapid uptake of Bimzelx driven by strong perception of IL-17is (1st call)',
            'caption' => [
                ['text' => 'Customizer: Influenced by rapid uptake of '],
                ['text' => 'Bimzelx driven by strong perception of IL-17is', 'bold' => true],
                ['text' => ' (1st call)'],
            ],
            'question' => 'Which evidence would you lead with for this HCP, and why?',
        ],
        3 => [
            'persona' => 'Customizer: Prefers Tremfya (guselkumab IL-23i) and relegates JAKi usage to 4L therapy in PsA (2nd+ call)',
            'caption' => [
                ['text' => 'Customizer: '],
                ['text' => 'Prefers Tremfya (guselkumab IL-23i) and relegates JAKi usage', 'bold' => true],
                ['text' => ' to 4L therapy in PsA (2nd+ call)'],
            ],
            'question' => 'How would you open a short call with this HCP? Write your key message.',
        ],
    ],

    /*
    | Boxes, titles and answers follow the RINVOQ PsA answer board in
    | "SpA Narrative Train the Trainer program – post-meeting asset – Communication Flow Game.pptx" (slides 29–31).
    */
    'sections' => [
        'unmet' => ['title' => 'Unmet need', 'tone' => 'unmet', 'slots' => 1, 'answers' => [1]],
        'rtb' => ['title' => 'Reason to Believe', 'tone' => 'rtb', 'slots' => 1, 'answers' => [3]],
        'ctrl' => ['title' => 'Control that’s fast, shown to last', 'tone' => 'ctrl', 'slots' => 2, 'answers' => [2, 5]],
        // Left: SELECT-PsA 1 (non-biologic DMARD-IR). Right: SELECT-PsA 2 (bDMARD-IR).
        'joint' => ['title' => 'Joint efficacy', 'tone' => 'rct', 'slots' => 2, 'answers' => [6, 7]],
        'rtb_rapid' => ['title' => 'Reason to believe', 'tone' => 'rtb', 'slots' => 1, 'answers' => [4]],
        'mda' => ['title' => 'MDA response (RCT)', 'tone' => 'rct', 'slots' => 2, 'answers' => [8, 9]],
        'joint_rct' => ['title' => 'Joint efficacy (RCT)', 'tone' => 'rct', 'slots' => 2, 'answers' => [10, 11]],
        'skin' => ['title' => 'Skin clearance (RCT)', 'tone' => 'rct', 'slots' => 2, 'answers' => [12, 13]],
        'mda_rwe' => ['title' => 'MDA (RWE)', 'tone' => 'rwe', 'slots' => 1, 'answers' => [14]],
        'safety' => ['title' => 'Integrated safety', 'tone' => 'rct', 'slots' => 1, 'answers' => [16]],
        'multi' => ['title' => 'Multiple indications', 'tone' => 'rct', 'slots' => 1, 'answers' => [15]],
    ],

    'columns' => [
        [
            'key' => 'c1',
            'tone' => 'unmet',
            'title' => 'Unmet need',
            'description' => 'PsA is a complex and heterogeneous disease where the involvement of multiple domains contributes to disease activity, reduced physical function, and reduced QoL',
            'rows' => [['unmet']],
        ],
        [
            'key' => 'c2',
            'tone' => 'how',
            'title' => 'How RINVOQ works',
            'description' => null,
            'rows' => [['rtb']],
        ],
        [
            'key' => 'c3',
            'tone' => 'simpl',
            'title' => 'SIMPL3',
            'description' => '1. Rapid efficacy across key PsA manifestations',
            'rows' => [['ctrl'], ['joint'], ['rtb_rapid']],
        ],
        [
            'key' => 'c4',
            'tone' => 'simpl',
            'title' => 'SIMPL3',
            'description' => '2. Durable efficacy across key PsA manifestations',
            'rows' => [['mda', 'joint_rct'], ['skin'], ['mda_rwe']],
        ],
        [
            'key' => 'c5',
            'tone' => 'simpl',
            'title' => 'SIMPL3',
            'description' => '3. Proven efficacy across multiple indications with a consistent and long-term safety profile',
            'rows' => [['safety'], ['multi']],
        ],
    ],

];
