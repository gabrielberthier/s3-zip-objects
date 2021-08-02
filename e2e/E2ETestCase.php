<?php

namespace E2E;

use Aws\S3\S3Client;
use PHPUnit\Framework\TestCase;
use S3DataTransfer\Credentials\S3ClientFactory;
use S3DataTransfer\Credentials\S3Credentials;
use S3DataTransfer\Credentials\S3Options;

abstract class E2ETestCase extends TestCase
{
    protected static S3Client $s3client;
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
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        self::$s3client->deleteObject([
            'Bucket' => self::$bucket,
            'Key' => 'test.txt',
        ]);
    }
}
