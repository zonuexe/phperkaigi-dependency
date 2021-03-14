<?php

declare(strict_types=1);

namespace zonuexe\PHPerKaigi2021\Step3\HttpHandler;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class NotFound implements RequestHandlerInterface
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
        ob_start(); ?>
<!DOCTYPE html>
<title>Not Found</title>
<h1>API Not Found</h1>
<?php
        $html = ob_get_clean();

        return $this->response_factory->createResponse(404)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8')
            ->withBody(
                $this->stream_factory->createStream($html)
            );
    }
}
