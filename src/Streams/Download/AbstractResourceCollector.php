<?php

namespace S3DataTransfer\Streams\Download;

use Psr\Log\LoggerInterface;
use S3DataTransfer\Exceptions\InvalidParamsException;
use S3DataTransfer\Interfaces\Download\StreamCollectorInterface;
use S3DataTransfer\Interfaces\ObjectInterface;
use S3DataTransfer\Utils\S3FileVerifier;

abstract class AbstractResourceCollector implements StreamCollectorInterface
{
    public function __construct(
        protected LoggerInterface $loggerInterface,
        protected bool $checkObjectExist = false
    ) {
    }

    public function checkForObjectExistence(): void
    {
        $this->checkObjectExist = true;
    }

    protected function validateResourceObjects(string $bucket, ObjectInterface $obj)
    {
        if (!($obj instanceof ObjectInterface)) {
            throw new InvalidParamsException('Resources to download must be composed of ResourceObject instances only.');
        }

        if ($this->checkObjectExist) {
            S3FileVerifier::verifyFileExistence($bucket, $obj->path());
        }
    }
}
