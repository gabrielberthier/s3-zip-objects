<?php

namespace S3DataTransfer\Interfaces\Download;

interface ObjectDownloaderInterface
{
    public function downloadObject(string $bucket, string $key, $useTempLocation = true): string;
}
