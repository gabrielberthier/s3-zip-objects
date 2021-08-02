<?php

namespace S3DataTransfer\Streams\Download;

use Generator;
use GuzzleHttp\Promise\Utils;
use Psr\Log\LoggerInterface;
use S3DataTransfer\Exceptions\InvalidParamsException;
use S3DataTransfer\Interfaces\Download\AsyncObjectDownloaderInterface;
use S3DataTransfer\Interfaces\ObjectInterface;

class AsyncStreamResourceCollector extends AbstractResourceCollector
{
    public function __construct(
        private AsyncObjectDownloaderInterface $downloader,
        protected LoggerInterface $loggerInterface,
        protected bool $checkObjectExist = false
    ) {
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
}
