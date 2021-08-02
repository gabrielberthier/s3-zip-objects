<?php

namespace E2E\Tests;

use E2E\Tests\AbstractTestCase\E2ETestCase;
use GuzzleHttp\Psr7\Stream;
use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\assertNotNull;
use S3DataTransfer\Logger\LoggerFactory;
use S3DataTransfer\Objects\UploadableObject;
use S3DataTransfer\S3\Uploader\Async\AsyncObjectUploader;
use S3DataTransfer\Streams\Upload\AbstractUploaderCollector;
use S3DataTransfer\Streams\Upload\AsyncUploadCollector;

  /**
   * @internal
   * @coversNothing
   */
  class UploadingTest extends E2ETestCase
  {
      protected AbstractUploaderCollector $sut;

      public function setUp(): void
      {
          $uploaderInterface = new AsyncObjectUploader(self::$s3client);
          $this->sut = new AsyncUploadCollector($uploaderInterface, LoggerFactory::constructLogger());
      }

      public function tearDown(): void
      {
          self::$s3client->deleteObject([
              'Bucket' => self::$bucket,
              'Key' => 'test-upload.txt',
          ]);
      }

      public function testIfCanUpload()
      {
          $fp = fopen('php://memory', 'r+');

          fwrite($fp, 'Testable content');

          rewind($fp);

          $results = $this->sut->uploadObjects(self::$bucket, new UploadableObject('test-upload.txt', new Stream($fp)));

          fclose($fp);

          assertNotNull($results);
          assertGreaterThan(0, count($results));
      }
  }
