<?php

namespace S3DataTransfer\Streams\Upload;

use Psr\Log\LoggerInterface;
use S3DataTransfer\Interfaces\Objects\UploadableObjectInterface;
use S3DataTransfer\Interfaces\Upload\UploadCollectorInterface;
use Throwable;

abstract class AbstractUploaderCollector implements UploadCollectorInterface
{
    public function __construct(protected LoggerInterface $loggerInterface)
    {
    }

    public function handle(string $bucketName, UploadableObjectInterface ...$objects)
    {
        try {
            $this->uploadObjects($bucketName, ...$objects);
        } catch (Throwable $th) {
            $this->loggerInterface->warning($th->getMessage());
        }
    }
}
