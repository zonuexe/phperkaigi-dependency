<?php

declare(strict_types=1);

namespace zonuexe\PHPerKaigi2021\Step3\HttpHandler;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Now implements RequestHandlerInterface
{
    private ResponseFactoryInterface $response_factory;
    private StreamFactoryInterface $stream_factory;

    public function __construct(
        ResponseFactoryInterface $response_factory,
        StreamFactoryInterface $stream_factory
    ) {
        $this->response_factory = $response_factory;
        $this->stream_factory = $stream_factory;
    }

    public function handle(
        ServerRequestInterface $request
    ): ResponseInterface
    {
        $timestamp = time();
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
