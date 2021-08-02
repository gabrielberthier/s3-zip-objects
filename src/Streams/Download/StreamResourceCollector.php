<?php

namespace S3DataTransfer\Streams\Download;

use Generator;
use Psr\Log\LoggerInterface;
use S3DataTransfer\Exceptions\InvalidParamsException;
use S3DataTransfer\Interfaces\Download\ObjectDownloaderInterface;
use S3DataTransfer\Interfaces\Objects\ObjectInterface;

class StreamResourceCollector extends AbstractResourceCollector
{
    public function __construct(
        private ObjectDownloaderInterface $downloader,
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
}
