<?php

namespace S3DataTransfer\Interfaces\Download;

/**
 * Creates a downloader.
 */
interface DownloaderFactoryInterface
{
    public function create(
        string $s3Key,
        string $s3Secret,
        string $s3Region,
        string $s3Version
    ): StreamCollectorInterface;
}
