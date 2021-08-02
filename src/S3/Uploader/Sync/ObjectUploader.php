<?php

namespace S3DataTransfer\S3\Uploader\Sync;

use Aws\Result;
use Aws\S3\S3Client;
use Psr\Http\Message\StreamInterface;
use S3DataTransfer\Interfaces\Upload\UploaderInterface;

class ObjectUploader implements UploaderInterface
{
    public function __construct(private S3Client $s3client)
    {
    }

    public function uploadObject(string $bucketName, string $objectKey, string | StreamInterface $source): Result
    {
        $options = [
            'Bucket' => $bucketName,
            'Key' => $objectKey,
        ];
        $storeMethod = 'SourceFile';
        if ($source instanceof StreamInterface) {
            $storeMethod = 'Body';
        }
        //basename($path)
        $options[$storeMethod] = $source;

        return $this->s3client->putObject($options);
    }
}
