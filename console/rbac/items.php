<?php
return [
    'user' => [
        'type' => 1,
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'user',
        ],
    ],
];
