<?php

namespace S3DataTransfer\S3\Uploader\Async;

use Aws\S3\S3Client;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\StreamInterface;
use S3DataTransfer\Interfaces\Upload\AsyncUploaderInterface;

class AsyncObjectUploader implements AsyncUploaderInterface
{
    public function __construct(private S3Client $s3client)
    {
    }

    public function uploadObject(string $bucketName, string $objectKey, string | StreamInterface $source): PromiseInterface
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

        return $this->s3client->putObjectAsync($options);
    }
}
