<?php

namespace S3DataTransfer\Interfaces\Upload;

use S3DataTransfer\Interfaces\Objects\UploadableObjectInterface;

interface UploadCollectorInterface
{
    public function uploadObjects(string $bucket, UploadableObjectInterface ...$objects): array;
}
