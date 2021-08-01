<?php

namespace S3DataTransfer\Interfaces;

interface ObjectDownloaderInterface
{
    public function downloadObject(string $bucket, string $key, $useTempLocation = false): string;
}
