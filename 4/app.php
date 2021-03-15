<?php

namespace zonuexe\PHPerKaigi2021;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PsrProposal\Clock\ClockInterface;
use zonuexe\PHPerKaigi2021\Step4\HttpHandler\Add;
use zonuexe\PHPerKaigi2021\Step4\HttpHandler\NotFound;
use zonuexe\PHPerKaigi2021\Step4\HttpHandler\Now;

require_once __DIR__ . '/../vendor/autoload.php';

$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$creator = new \Nyholm\Psr7Server\ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);

$serverRequest = $creator->fromGlobals();
$clock = new \Bag2\Clock\StandardClock();

$dispatcher = new class($psr17Factory, $psr17Factory, $clock) implements RequestHandlerInterface {
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

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $uri = parse_url((string)$request->getUri(), PHP_URL_PATH);

        if ($uri === '/now') {
            $handler = new Now($this->response_factory, $this->stream_factory, $this->clock);
        } elseif (preg_match('@add/(?<x>[\d+])/(?<y>[\d+])@u', $uri, $matches)) {
            $handler = new Add($this->response_factory, $this->stream_factory);
        } else {
            $handler = new NotFound($this->response_factory, $this->stream_factory);
        }

        return $handler->handle($request);
    }
};

$response = $dispatcher->handle($serverRequest);
(new \Laminas\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);
