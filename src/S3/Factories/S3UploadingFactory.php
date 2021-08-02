<?php

namespace S3DataTransfer\S3\Factories;

use S3DataTransfer\Interfaces\Upload\UploadCollectorInterface;
use S3DataTransfer\Interfaces\Upload\UploaderFactoryInterface;
use S3DataTransfer\Logger\LoggerFactory;
use S3DataTransfer\S3\Uploader\Sync\ObjectUploader;
use S3DataTransfer\Streams\Upload\UploadCollector;

class S3UploadingFactory implements UploaderFactoryInterface
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
        $uploaderInterface = new ObjectUploader($s3client);

        return new UploadCollector($uploaderInterface, LoggerFactory::constructLogger());
    }
}
