<?php

namespace S3DataTransfer\S3\Factories;

use GuzzleHttp\Client as HttpClient;
use S3DataTransfer\Clients\HttpAsyncClient;
use S3DataTransfer\Interfaces\Download\DownloaderFactoryInterface;
use S3DataTransfer\Interfaces\Download\StreamCollectorInterface;
use S3DataTransfer\Logger\LoggerFactory;
use S3DataTransfer\S3\Downloader\Async\HttpObjectDownloaderAsync;
use S3DataTransfer\Streams\Download\AsyncStreamResourceCollector;

class S3AsyncDownloaderFactory implements DownloaderFactoryInterface
{
    public function create(
        string $s3Key,
        string $s3Secret,
        string $s3Region,
        string $s3Version
    ): StreamCollectorInterface {
        $s3client = ClientProvider::getS3Client(
            $s3Key,
            $s3Secret,
            $s3Region,
            $s3Version
        );
        $asyncHttpClient = new HttpAsyncClient(new HttpClient());

        return new AsyncStreamResourceCollector(
            new HttpObjectDownloaderAsync($s3client, $asyncHttpClient),
            LoggerFactory::constructLogger()
        );
    }
}
