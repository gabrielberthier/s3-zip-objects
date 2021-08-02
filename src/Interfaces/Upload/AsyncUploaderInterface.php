<?php

namespace S3DataTransfer\Interfaces\Upload;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\StreamInterface;

interface AsyncUploaderInterface
{
    public function uploadObject(string $bucketName, string $objectKey, string | StreamInterface $source): PromiseInterface;
}
