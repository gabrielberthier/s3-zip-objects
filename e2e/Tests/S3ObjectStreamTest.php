<?php

namespace E2E\Tests;

use E2E\Tests\AbstractTestCase\E2ETestCase;
use GuzzleHttp\Client;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;
use S3DataTransfer\Clients\HttpClientAdapter;
use S3DataTransfer\Interfaces\Download\StreamCollectorInterface;
use S3DataTransfer\Logger\LoggerFactory;
use S3DataTransfer\Objects\ResourceObject;
use S3DataTransfer\S3\Downloader\Sync\HttpObjectDownloader;
use S3DataTransfer\Streams\Download\StreamResourceCollector;

  /**
   * @internal
   * @coversNothing
   */
  class S3ObjectStreamTest extends E2ETestCase
  {
      protected StreamCollectorInterface $streamResourceCollector;

      public function setUp(): void
      {
          $this->streamResourceCollector = new StreamResourceCollector(
              new HttpObjectDownloader(
                  self::$s3client,
                  new HttpClientAdapter(new Client())
              ),
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

      public function testIfNameProvidedIsReturnInResource()
      {
          $resource = new ResourceObject('test.txt', 'testa.txt');
          $stream = $this->streamResourceCollector->streamCollect(self::$bucket, $resource);
          foreach ($stream as $name => $value) {
              assertSame($name, $resource->name());
          }
      }
  }
