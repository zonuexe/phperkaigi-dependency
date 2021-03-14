<?php

namespace zonuexe\PHPerKaigi2021;

/**
 * @param positive-int $timestamp
 * @return array{'Y-m-d H:i:s': string, timestamp: int}
 */
function api_now(int $timestamp): array
{
    return [
        'Y-m-d H:i:s' => date('Y-m-d H:i:s', $timestamp),
        'timestamp' => $timestamp,
    ];
}

/**
 * @return array{formula: string, result: int|float}
 */
function api_add(int $x, int $y): array
{
    return [
        'formula' => "{$x} + {$y}",
        'result' => $x + $y,
    ];
}
