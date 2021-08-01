<?php

namespace S3DataTransfer\Interfaces;

use S3DataTransfer\Objects\ResourceObject;

interface StreamResourceCollectorInterface
{
    /**
     * Collects a stream of resources.
     *
     * @param ObjectInterface ...$resourceObjects
     *
     * @return Generator<string, resource>
     */
    public function streamCollect(string $bucketName, ResourceObject ...$resourceObjects);
}
