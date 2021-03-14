<?php

/**
 * @return int|float
 */
function add(int $a, int $b)
{
    return $a + $b;
}

$cases = [
    [1, 2, 3],
    [100, 200, 300],
];

foreach ($cases as [$a, $b, $expected]) {
    $actual = add($a, $b);
    assert($expected == $actual);
}
echo "ok\n";
