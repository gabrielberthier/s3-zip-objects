<?php

namespace S3DataTransfer\S3\Downloader\Async;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Psr\Http\Message\RequestInterface;
use S3DataTransfer\Exceptions\InvalidParamsException;
use S3DataTransfer\Interfaces\AsyncClientInterface;
use S3DataTransfer\Interfaces\AsyncObjectDownloaderInterface;

class HttpObjectDownloaderAsync implements AsyncObjectDownloaderInterface
{
    public function __construct(private S3Client $s3Client, private AsyncClientInterface $clientInterface)
    {
        $this->s3client->registerStreamWrapper();
    }

    public function downloadObjectAsync(string $bucket, string $key, $useTempLocation = false): AsyncObjectResponse
    {
        $this->prepareBucketHead($bucket);

        $request = $this->mountRequestS3Request($bucket, $key);

        $tmpfile = tempnam(sys_get_temp_dir(), crc32(time()));

        $request->getBody()->write($tmpfile);

        $promise = $this->clientInterface->sendAsyncRequest($request);

        return new AsyncObjectResponse($tmpfile, $promise);
    }

    private function mountRequestS3Request(string $bucket, string $path): RequestInterface
    {
        return $this->s3client->createPresignedRequest(
            $this->s3client->getCommand('GetObject', [
                'Key' => $path,
                'Bucket' => $bucket,
            ]),
            '+1 day'
        );
    }

    // @docs http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#headbucket
    private function prepareBucketHead(string $bucketName)
    {
        if (empty($bucketName)) {
            throw new InvalidParamsException('The parameter `bucket` is required and cannot be an empty string.');
        }

        try {
            $this->s3client->headBucket([
                'Bucket' => $bucketName,
            ]);
        } catch (S3Exception) {
            throw new InvalidParamsException("Bucket `{$bucketName}` does not exists and/or you have not permission to access it.");
        }
    }
}
