<?php

namespace S3DataTransfer\S3\Factories;

use S3DataTransfer\Interfaces\Upload\UploadCollectorInterface;
use S3DataTransfer\Logger\LoggerFactory;
use S3DataTransfer\S3\Uploader\Async\AsyncObjectUploader;
use S3DataTransfer\Streams\Upload\AsyncUploadCollector;

class S3AsyncUploadingFactory
{
    public function create(
        string $s3Key,
        string $s3Secret,
        string $s3Region,
        string $s3Version
    ): UploadCollectorInterface {
        $s3client = ClientProvider::getS3Client(
            $s3Key,
            $s3Secret,
            $s3Region,
            $s3Version
        );
        $uploaderInterface = new AsyncObjectUploader($s3client);

        return new AsyncUploadCollector($uploaderInterface, LoggerFactory::constructLogger());
    }
}
