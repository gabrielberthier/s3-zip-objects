<?php

namespace S3DataTransfer\Streams\Upload;

use GuzzleHttp\Promise\Utils;
use Psr\Log\LoggerInterface;
use S3DataTransfer\Interfaces\Objects\UploadableObjectInterface;
use S3DataTransfer\Interfaces\Upload\AsyncUploaderInterface;

class AsyncUploadCollector extends AbstractUploaderCollector
{
    public function __construct(private AsyncUploaderInterface $asyncUploaderInterface, protected LoggerInterface $loggerInterface)
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
