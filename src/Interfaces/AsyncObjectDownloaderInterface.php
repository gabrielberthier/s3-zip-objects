<?php

namespace S3DataTransfer\Interfaces;

use S3DataTransfer\S3\Downloader\Async\AsyncObjectResponse;

interface AsyncObjectDownloaderInterface
{
    /**
     * Returns a temp filename associated with a promise interface.
     *
     * @param bool $useTempLocation
     *
     * @return <string, PromiseInterface>
     */
    public function downloadObjectAsync(string $bucket, string $key, $useTempLocation = true): AsyncObjectResponse;
}
