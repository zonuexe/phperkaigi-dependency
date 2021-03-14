<?php

function print_add($a, $b)
{
    echo $a + $b;
}

$cases = [
    [1, 2, 3],
    [100, 200, 300],
];

foreach ($cases as [$a, $b, $expected]) {
    ob_start();
    print_add($a, $b);
    $actual = ob_get_clean();
    assert($expected == $actual);
}
echo "ok\n";
