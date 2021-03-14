<?php

namespace zonuexe\PHPerKaigi2021;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use zonuexe\PHPerKaigi2021\Step3\HttpHandler\Now;

require_once __DIR__ . '/../../vendor/autoload.php';

$psr17 = new \Nyholm\Psr7\Factory\Psr17Factory();
$request = $psr17->createServerRequest('GET', '/dummy');

$subject = new Now($psr17, $psr17);
$actual = $subject->handle($request);

$actual_content_type = $actual->getHeader('Content-Type');
assert($actual_content_type === ['application/json']);

$actual_data = json_decode((string)$actual->getBody(), true);
$timestamp = time();
$expected = [
    'Y-m-d H:i:s' => date('Y-m-d H:i:s', $timestamp),
    'timestamp' => $timestamp,
];

assert($actual_data === $expected);

echo "ok\n";
