<?php

namespace S3DataTransfer\Streams;

use Generator;
use GuzzleHttp\Promise\Utils;
use Psr\Log\LoggerInterface;
use S3DataTransfer\Exceptions\InvalidParamsException;
use S3DataTransfer\Interfaces\AsyncObjectDownloaderInterface;
use S3DataTransfer\Interfaces\ObjectInterface;
use S3DataTransfer\Interfaces\StreamResourceCollectorInterface;
use S3DataTransfer\Utils\S3FileVerifier;

class AsyncStreamResourceCollector implements StreamResourceCollectorInterface
{
    public function __construct(
        private AsyncObjectDownloaderInterface $downloader,
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

        $files = [];
        $promises = [];
        foreach ($resourceObjects as $obj) {
            try {
                $this->validateResourceObjects($bucketName, $obj);
                $response = $this->downloader->downloadObjectAsync($bucketName, $obj->path());
                $fileName = $obj->name() ?? $response->fileName();
                $files[$fileName] = $response->fileName();
                $promises[] = $response->promise();
            } catch (InvalidParamsException $e) {
                $this->loggerInterface->alert($e->getMessage());
            }
        }

        Utils::unwrap($promises);

        foreach ($files as $fileName => $path) {
            if ($stream = fopen($path, 'r', false, $context)) {
                yield $fileName => $stream;
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
