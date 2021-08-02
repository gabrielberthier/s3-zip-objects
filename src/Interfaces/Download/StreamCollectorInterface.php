<?php

namespace S3DataTransfer\Interfaces\Download;

use Generator;
use S3DataTransfer\Interfaces\Objects\ObjectInterface;
use S3DataTransfer\Objects\ResourceObject;

interface StreamCollectorInterface
{
    /**
     * Collects a stream of resources.
     *
     * @param ObjectInterface ...$resourceObjects
     *
     * @return Generator<string, resource>
     */
    public function streamCollect(string $bucketName, ResourceObject ...$resourceObjects): Generator;
}
