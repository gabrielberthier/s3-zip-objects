<?php

namespace S3DataTransfer\Streams\Upload;

use S3DataTransfer\Interfaces\Objects\UploadableObjectInterface;
use S3DataTransfer\Interfaces\Upload\UploadCollectorInterface;
use S3DataTransfer\Interfaces\Upload\UploaderInterface;

class UploadCollector implements UploadCollectorInterface
{
    public function __construct(private UploaderInterface $uploader)
    {
    }

    public function uploadObjects(string $bucket, UploadableObjectInterface ...$objects): array
    {
        $results = [];
        foreach ($objects as $object) {
            $results[] = $this->uploader->uploadObject($bucket, $object->key(), $object->source());
        }

        return $results;
    }
}
