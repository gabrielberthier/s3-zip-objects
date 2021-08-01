<?php

namespace S3DataTransfer\Clients;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\Utils;
use Psr\Http\Message\RequestInterface;

class HttpAsyncClient
{
    public function __construct(private HttpClient $client)
    {
    }

    /**
     * @param RequestInterface[] $requests
     */
    public function sendAsyncRequest(array $requests): PromiseInterface
    {
        $promises = [];

        foreach ($requests as $request) {
            $tmpfile = (string) $request->getBody();

            $promises[$tmpfile] = $this->client->getAsync((string) $request->getUri(), [
                'synchronous' => true,
                'sink' => fopen($tmpfile, 'w+'),
            ]);
        }

        return Utils::all($promises);
    }
}
