<?php

namespace S3DataTransfer\Interfaces\Upload;

use Aws\Result;
use Psr\Http\Message\StreamInterface;

interface UploaderInterface
{
    public function uploadObject(string $bucketName, string $objectKey, string | StreamInterface $source): Result;
}
