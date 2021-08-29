<?php

return [
    'chain' => base_path() . env('CHAIN_CERT'),
    'client' => [
        'cert' => base_path() . env('CLIENT_CERT'),
        'key' => base_path() . env('CLIENT_KEY')
    ]
];
