<?php

declare(strict_types=1);

namespace zonuexe\PHPerKaigi2021\Step4\HttpHandler;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PsrProposal\Clock\ClockInterface;

final class Now implements RequestHandlerInterface
{
    private ResponseFactoryInterface $response_factory;
    private StreamFactoryInterface $stream_factory;
    private ClockInterface $clock;

    public function __construct(
        ResponseFactoryInterface $response_factory,
        StreamFactoryInterface $stream_factory,
        ClockInterface $clock
    ) {
        $this->response_factory = $response_factory;
        $this->stream_factory = $stream_factory;
        $this->clock = $clock;
    }

    public function handle(
        ServerRequestInterface $request
    ): ResponseInterface
    {
        $timestamp = $this->clock->now()->getTimestamp();
        $data = [
            'Y-m-d H:i:s' => date('Y-m-d H:i:s', $timestamp),
            'timestamp' => $timestamp,
        ];

        return $this->response_factory->createResponse()
            ->withHeader('Content-Type', 'application/json')
            ->withBody(
                $this->stream_factory->createStream(
                    json_encode($data)
                )
            );
    }
}
