<?php

namespace zonuexe\PHPerKaigi2021;

use DateTimeImmutable;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use zonuexe\PHPerKaigi2021\Step4\HttpHandler\Now;

require_once __DIR__ . '/../../vendor/autoload.php';

$psr17 = new \Nyholm\Psr7\Factory\Psr17Factory();
$request = $psr17->createServerRequest('GET', '/dummy');

$now = DateTimeImmutable::createFromFormat(
    'Y-m-d H:i:s', '2112-09-03 12:12:12');
$clock = new \Bag2\Clock\FixedClock($now);

$subject = new Now($psr17, $psr17, $clock);
$actual = $subject->handle($request);

$actual_content_type = $actual->getHeader('Content-Type');
assert($actual_content_type === ['application/json']);

$actual_data = json_decode((string)$actual->getBody(), true);
$expected = [
    'Y-m-d H:i:s' => '2112-09-03 12:12:12',
    'timestamp' => 4502347932,
];

assert($actual_data === $expected);

echo "ok\n";
