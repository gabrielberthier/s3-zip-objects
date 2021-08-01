<?php

namespace S3DataTransfer\S3;

use GuzzleHttp\Client as HttpClient;
use LoggerFactory;
use S3DataTransfer\Clients\HttpClientAdapter;
use S3DataTransfer\Credentials\S3ClientFactory;
use S3DataTransfer\Credentials\S3Credentials;
use S3DataTransfer\Credentials\S3Options;
use S3DataTransfer\S3\Downloader\HttpObjectDownloader;
use S3DataTransfer\Streams\StreamResourceCollector;

class S3DownloaderFactory
{
    public static function create(
        string $s3Key,
        string $s3Secret,
        string $s3Region,
        string $s3Version
    ) {
        $s3Options = new S3Options(new S3Credentials($s3Key, $s3Secret), $s3Region, $s3Version);

        // //Listing all S3 Bucket
        // $buckets = $s3Options->createS3Client()->listBuckets();

        // foreach ($buckets['Buckets'] as $bucket) {
        //     echo $bucket['Name']."\n";
        // }

        // exit;

        $factoryS3Client = new S3ClientFactory();
        $s3client = $factoryS3Client->getClient($s3Options);
        $httpClient = new HttpClientAdapter(new HttpClient());

        return new StreamResourceCollector(
            new HttpObjectDownloader($s3client, $httpClient),
            LoggerFactory::constructLogger()
        );
    }
}
