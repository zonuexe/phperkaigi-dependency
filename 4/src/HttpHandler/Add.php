<?php

declare(strict_types=1);

namespace zonuexe\PHPerKaigi2021\Step4\HttpHandler;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Add implements RequestHandlerInterface
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
        $uri = parse_url((string)$request->getUri(), PHP_URL_PATH);

        preg_match('@add/(?<x>[\d+])/(?<y>[\d+])@u', $uri, $matches);
        ['x' => $x, 'y' => $y] = $matches;

        $data = $this->add((int)$x, (int)$y);

        return $this->response_factory->createResponse()
            ->withHeader('Content-Type', 'application/json')
            ->withBody(
                $this->stream_factory->createStream(
                    json_encode($data)
                )
            );
    }

    /**
     * @return array{formula: string, result: int|float}
     */
    function add(int $x, int $y): array
    {
        return [
            'formula' => "{$x} + {$y}",
            'result' => $x + $y,
        ];
    }
}
