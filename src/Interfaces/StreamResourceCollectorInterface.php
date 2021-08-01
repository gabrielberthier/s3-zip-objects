<?php

namespace S3DataTransfer\Interfaces;

use S3DataTransfer\Objects\ResourceObject;

interface StreamResourceCollectorInterface
{
    public function streamCollect(string $bucketName, ResourceObject $resourceObjects);
}
