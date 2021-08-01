<?php

namespace S3DataTransfer\Interfaces;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;

interface AsyncClientInterface
{
    public function sendAsyncRequest(RequestInterface $request): PromiseInterface;
}
