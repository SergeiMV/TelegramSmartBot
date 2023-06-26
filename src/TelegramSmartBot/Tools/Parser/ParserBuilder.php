<?php

namespace TelegramSmartBot\Tools\Parser;

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use GuzzleTor\Middleware as TorMiddleware;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use Xparse\Parser\Parser;


class ParserBuilder
{
    private HandlerStack $stack;

    private array $config = [];


    public function __construct(LoggerInterface $logger) {
        $this->stack = HandlerStack::create(new CurlHandler());
        $this->stack->push(Middleware::mapRequest(function (RequestInterface $request) use ($logger) {
            $logger->notice($request->getMethod() . ' ' . $request->getUri());
            return $request;
        }));
    }


    public function getParser() : Parser
    {
        $this->config['handler'] = $this->stack;

        return new Parser(new Client($this->config));;
    }


    public function setTorMiddleware()
    {
        $this->stack->push(TorMiddleware::tor());
    }


    public function setRequestDelay(int $delay)
    {
        $this->config[RequestOptions::DELAY] = $delay;
    }


    public function setDebug(bool $isDebug)
    {
        $this->config[RequestOptions::DEBUG] = $isDebug;
    }


    public function setAllowRedirect(bool $isAllowed)
    {
        $this->config[RequestOptions::ALLOW_REDIRECTS] = $isAllowed;
    }


    public function resetConfig()
    {
        $this->config = [];
    }

}