<?php
return [
    'tester' => [
        'type' => 1,
    ],
    'developer' => [
        'type' => 1,
        'children' => [
            'tester',
        ],
    ],
    'manager' => [
        'type' => 1,
        'children' => [
            'developer',
        ],
    ],
];
