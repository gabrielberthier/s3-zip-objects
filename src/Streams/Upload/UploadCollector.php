<?php

namespace S3DataTransfer\Streams\Upload;

use Psr\Log\LoggerInterface;
use S3DataTransfer\Interfaces\Objects\UploadableObjectInterface;
use S3DataTransfer\Interfaces\Upload\UploaderInterface;

class UploadCollector extends AbstractUploaderCollector
{
    public function __construct(
        private UploaderInterface $uploader,
        protected LoggerInterface $loggerInterface
    ) {
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
