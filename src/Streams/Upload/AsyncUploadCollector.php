<?php

namespace S3DataTransfer\Streams\Upload;

use S3DataTransfer\Interfaces\UploadableObjectInterface;

class AsyncUploadCollector
{
    public function uploadObjects(string $bucket, UploadableObjectInterface ...$object)
    {
    }
}
