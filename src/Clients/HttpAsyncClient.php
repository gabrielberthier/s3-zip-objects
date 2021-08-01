<?php

namespace S3DataTransfer\Clients;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use S3DataTransfer\Interfaces\AsyncClientInterface;

class HttpAsyncClient implements AsyncClientInterface
{
    public function __construct(private HttpClient $client)
    {
    }

    public function sendAsyncRequest(RequestInterface $request): PromiseInterface
    {
        $tmpfile = (string) $request->getBody();

        return $this->client->getAsync((string) $request->getUri(), [
            'synchronous' => true,
            'sink' => fopen($tmpfile, 'w+'),
        ]);
    }
}
