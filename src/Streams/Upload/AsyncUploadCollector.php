<?php

namespace S3DataTransfer\Streams\Upload;

use GuzzleHttp\Promise\Utils;
use S3DataTransfer\Interfaces\Objects\UploadableObjectInterface;
use S3DataTransfer\Interfaces\Upload\AsyncUploaderInterface;
use S3DataTransfer\Interfaces\Upload\UploadCollectorInterface;

class AsyncUploadCollector implements UploadCollectorInterface
{
    public function __construct(private AsyncUploaderInterface $asyncUploaderInterface)
    {
    }

    public function uploadObjects(string $bucket, UploadableObjectInterface ...$objects): array
    {
        $promises = [];
        foreach ($objects as $object) {
            $promises[] = $this->asyncUploaderInterface->uploadObject($bucket, $object->key(), $object->source());
        }

        return Utils::unwrap($promises);
    }
}
