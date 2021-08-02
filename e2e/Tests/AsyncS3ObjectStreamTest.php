<?php

namespace E2E\Tests;

use E2E\Tests\AbstractTestCase\E2ETestCase;
use GuzzleHttp\Client;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;
use S3DataTransfer\Clients\HttpAsyncClient;
use S3DataTransfer\Interfaces\StreamResourceCollectorInterface;
use S3DataTransfer\Logger\LoggerFactory;
use S3DataTransfer\Objects\ResourceObject;
use S3DataTransfer\S3\Downloader\Async\HttpObjectDownloaderAsync;
use S3DataTransfer\Streams\AsyncStreamResourceCollector;

/**
   * @internal
   * @coversNothing
   */
  class AsyncS3ObjectStreamTest extends E2ETestCase
  {
      protected StreamResourceCollectorInterface $streamResourceCollector;

      public function setUp(): void
      {
          $this->streamResourceCollector = new AsyncStreamResourceCollector(
              new HttpObjectDownloaderAsync(self::$s3client, new HttpAsyncClient(new Client())),
              LoggerFactory::constructLogger()
          );
      }

      public function testIfObjectsAreDownloaded()
      {
          $stream = $this->streamResourceCollector->streamCollect(self::$bucket, new ResourceObject('test.txt', 'testa.txt'));
          foreach ($stream as $s) {
              if ($s) {
                  $content = fgets($s);
                  assertSame($content, 'Hello, world!');
                  fclose($s);
              }

              assertNotEmpty($stream);
              assertNotNull($stream);
          }
      }
  }
