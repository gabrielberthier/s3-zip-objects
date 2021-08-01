<?php

namespace S3DataTransfer\S3\Downloader\Async;

use GuzzleHttp\Promise\PromiseInterface;

final class AsyncObjectResponse
{
    public function __construct(private string $fileName, private PromiseInterface $promise)
    {
    }

    public function fileName(): string
    {
        return $this->fileName;
    }

    public function promise()
    {
        return $this->promise;
    }
}
