<?php

declare(strict_types=1);

use Elasticsearch\ConnectionPool\SimpleConnectionPool;

$defaults = [
    'connectionParams' => [
        'client' => [
            'timeout' => get_float(env('ES_CLIENT_TIMEOUT')) ?? 5,
            'connect_timeout' => get_float(env('ES_CLIENT_CONNECT_TIMEOUT')) ?? 0.5,
        ],
    ],
    'connectionPool' => [SimpleConnectionPool::class],
];
$parseHosts = fn ($envName) => explode(' ', env($envName, 'localhost:9200'));

return [
    'connections' => [
        'default' => array_merge($defaults, [
            'hosts' => $parseHosts('ES_HOST'),
        ]),
        'scores' => array_merge($defaults, [
            'hosts' => $parseHosts('ES_SCORES_HOST'),
        ]),
    ],
];
