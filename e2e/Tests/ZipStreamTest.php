<?php

namespace E2E\Tests;

use E2E\Tests\AbstractTestCase\E2ETestCase;
use GuzzleHttp\Client;
use function PHPUnit\Framework\assertNotNull;
use S3DataTransfer\Clients\HttpAsyncClient;
use S3DataTransfer\Logger\LoggerFactory;
use S3DataTransfer\Objects\ResourceObject;
use S3DataTransfer\S3\Downloader\Async\HttpObjectDownloaderAsync;
use S3DataTransfer\S3\Zip\S3StreamObjectsZipDownloader;
use S3DataTransfer\Streams\AsyncStreamResourceCollector;

/**
 * @internal
 * @coversNothing
 */
class ZipStreamTest extends E2ETestCase
{
    private S3StreamObjectsZipDownloader $sut;

    public function setUp(): void
    {
        $streamCollector = new AsyncStreamResourceCollector(
            new HttpObjectDownloaderAsync(self::$s3client, new HttpAsyncClient(new Client())),
            LoggerFactory::constructLogger()
        );
        $this->sut = new S3StreamObjectsZipDownloader($streamCollector);
    }

    public function testIfObjectsAreDownloaded()
    {
        $stream = $this->sut->zipObjects(self::$bucket, [new ResourceObject('test.txt', 'testa.txt')]);

        assertNotNull($stream);
        $this->assertIsResource($stream);
    }
}
