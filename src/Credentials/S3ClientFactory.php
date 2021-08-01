<?php

namespace S3DataTransfer\Credentials;

use Aws\S3\S3Client;

class S3ClientFactory
{
    public function getClient(S3Options $s3Options): S3Client
    {
        return new S3Client($s3Options->createConfiguration());
    }
}
