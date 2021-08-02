<?php

namespace S3DataTransfer\S3\Downloader\Factories;

use GuzzleHttp\Client as HttpClient;
use S3DataTransfer\Clients\HttpAsyncClient;
use S3DataTransfer\Credentials\S3ClientFactory;
use S3DataTransfer\Credentials\S3Credentials;
use S3DataTransfer\Credentials\S3Options;
use S3DataTransfer\Interfaces\DownloaderFactoryInterface;
use S3DataTransfer\Interfaces\StreamCollectorInterface;
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
        $s3Options = new S3Options(new S3Credentials($s3Key, $s3Secret), $s3Region, $s3Version);

        $factoryS3Client = new S3ClientFactory();
        $s3client = $factoryS3Client->getClient($s3Options);
        $asyncHttpClient = new HttpAsyncClient(new HttpClient());

        return new AsyncStreamResourceCollector(
            new HttpObjectDownloaderAsync($s3client, $asyncHttpClient),
            LoggerFactory::constructLogger()
        );
    }
}
