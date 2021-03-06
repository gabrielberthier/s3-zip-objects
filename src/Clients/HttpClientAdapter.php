<?php

namespace S3DataTransfer\Clients;

use GuzzleHttp\Client as HttpClient;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpClientAdapter implements ClientInterface
{
    public function __construct(private HttpClient $client)
    {
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $tmpfile = (string) $request->getBody();

        return $this->client->request('GET', (string) $request->getUri(), [
            'synchronous' => true,
            'sink' => fopen($tmpfile, 'w+'),
        ]);
    }
}
