<?php

use Aws\S3\S3Client;
use GuzzleHttp\Client;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;
use PHPUnit\Framework\TestCase;
use S3DataTransfer\Clients\HttpClientAdapter;
use S3DataTransfer\Credentials\S3ClientFactory;
use S3DataTransfer\Credentials\S3Credentials;
use S3DataTransfer\Credentials\S3Options;
use S3DataTransfer\Interfaces\StreamResourceCollectorInterface;
use S3DataTransfer\Logger\LoggerFactory;
use S3DataTransfer\Objects\ResourceObject;
use S3DataTransfer\S3\Downloader\Sync\HttpObjectDownloader;
use S3DataTransfer\Streams\StreamResourceCollector;

require __DIR__.'/../vendor/autoload.php';

  /**
   * @internal
   * @coversNothing
   */
  class S3ObjectStreamTest extends TestCase
  {
      protected static S3Client $s3client;
      protected StreamResourceCollectorInterface $streamResourceCollector;
      protected static string $bucket = 'artchier-markers';

      public static function setUpBeforeClass(): void
      {
          global $argv, $argc;
          $array = [];
          if ($argc >= 5) {
              $array = array_slice($argv, 2);
          } else {
              if (($open = fopen(__DIR__.'/../credentials.txt', 'r')) !== false) {
                  while (($data = fgetcsv($open, 1000, ';')) !== false) {
                      $array = $data;
                  }
                  fclose($open);
              }
          }

          list($key, $secret, $region, $version, $bucketName) = $array;

          $s3Options = new S3Options(
              new S3Credentials(
                  $key,
                  $secret
              ),
              $region,
              $version
          );

          self::$bucket = $bucketName;

          $factoryS3Client = new S3ClientFactory();
          self::$s3client = $factoryS3Client->getClient($s3Options);

          self::$s3client->putObject([
              'Bucket' => self::$bucket,
              'Key' => 'test.txt',
              'Body' => 'Hello, world!',
              'ACL' => 'public-read',
          ]);
      }

      public static function tearDownAfterClass(): void
      {
          self::$s3client->deleteObject([
              'Bucket' => self::$bucket,
              'Key' => 'test.txt',
          ]);
      }

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
  }
