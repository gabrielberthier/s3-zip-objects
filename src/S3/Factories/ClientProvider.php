<?php

namespace S3DataTransfer\S3\Factories;

use S3DataTransfer\Credentials\S3ClientFactory;
use S3DataTransfer\Credentials\S3Credentials;
use S3DataTransfer\Credentials\S3Options;

final class ClientProvider
{
    public static function getS3Client(
        string $s3Key,
        string $s3Secret,
        string $s3Region,
        string $s3Version
    ) {
        $s3Options = new S3Options(new S3Credentials($s3Key, $s3Secret), $s3Region, $s3Version);

        $factoryS3Client = new S3ClientFactory();

        return $factoryS3Client->getClient($s3Options);
    }
}
