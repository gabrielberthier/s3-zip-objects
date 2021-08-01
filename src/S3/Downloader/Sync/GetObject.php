<?php

namespace S3DataTransfer\S3\Downloader\Sync;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use S3DataTransfer\Interfaces\ObjectDownloaderInterface;

class GetObject implements ObjectDownloaderInterface
{
    public function __construct(private S3Client $s3Client)
    {
    }

    public function downloadObject(string $bucket, string $key, $useTempLocation = false): string
    {
        try {
            $fileName = $useTempLocation ? $this->getTempName() : $key;
            // Save object to a file.
            $result = $this->s3Client->getObject([
                'Bucket' => $bucket,
                'Key' => $key,
                'SaveAs' => $fileName,
            ]);

            return $fileName;
        } catch (S3Exception $e) {
            echo $e->getMessage()."\n";
        }
    }

    private function getTempName(): string
    {
        return tempnam(sys_get_temp_dir(), crc32(time()));
    }
}
