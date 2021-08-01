<?php

namespace S3DataTransfer\Streams;

use Generator;
use Psr\Log\LoggerInterface;
use S3DataTransfer\Exceptions\InvalidParamsException;
use S3DataTransfer\Interfaces\ObjectDownloaderInterface;
use S3DataTransfer\Interfaces\ObjectInterface;
use S3DataTransfer\Interfaces\StreamResourceCollectorInterface;
use S3DataTransfer\Utils\S3FileVerifier;

class StreamResourceCollector implements StreamResourceCollectorInterface
{
    public function __construct(
        private ObjectDownloaderInterface $downloader,
        private LoggerInterface $loggerInterface,
        private bool $checkObjectExist = false
    ) {
    }

    public function checkForObjectExistence(): void
    {
        $this->checkObjectExist = true;
    }

    /**
     * Collects a stream of resources.
     *
     * @param ObjectInterface ...$resourceObjects
     *
     * @return Generator<string, resource>
     */
    public function streamCollect(string $bucketName, ObjectInterface ...$resourceObjects): Generator
    {
        $context = stream_context_create([
            's3' => ['seekable' => true],
        ]);

        foreach ($resourceObjects as $obj) {
            try {
                $this->validateResourceObjects($bucketName, $obj);

                $tmpfile = $this->downloader->downloadObject($bucketName, $obj->path());

                if ($stream = fopen($tmpfile, 'r', false, $context)) {
                    $fileName = $obj->name() ?? $tmpfile;
                    yield $fileName => $stream;
                }
            } catch (InvalidParamsException $e) {
                $this->loggerInterface->alert($e->getMessage());
            }
        }
    }

    private function validateResourceObjects(string $bucket, ObjectInterface $obj)
    {
        if (!($obj instanceof ObjectInterface)) {
            throw new InvalidParamsException('Resources to download must be composed of ResourceObject instances only.');
        }

        if ($this->checkObjectExist) {
            S3FileVerifier::verifyFileExistence($bucket, $obj->path());
        }
    }
}
