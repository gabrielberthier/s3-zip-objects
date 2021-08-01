<?php

namespace S3DataTransfer\S3;

use S3DataTransfer\Interfaces\ObjectInterface;
use S3DataTransfer\Interfaces\StreamResourceCollectorInterface;
use ZipStream\Option\Archive as ArchiveOptions;
use ZipStream\ZipStream;

class S3StreamObjectsZipDownloader
{
    protected $opt;
    protected $stream;

    public function __construct(private StreamResourceCollectorInterface $streamResourceCollector)
    {
        // https://github.com/maennchen/ZipStream-PHP/wiki/Available-options
        $this->opt = new ArchiveOptions();
        $this->opt->setContentType('application/zip');
        $this->opt->setSendHttpHeaders(false);

        $this->stream = fopen('php://memory', 'r+');
        $this->opt->setOutputStream($this->stream);
    }

    /**
     * Returns a zip with all objects downloaded from S3.
     *
     * @param ObjectInterface[] $resourceObjects
     */
    public function zipObjects(
        string $bucketName,
        array $resourceObjects,
        string $zipname = 'resources.zip'
    ) {
        $zip = new ZipStream($zipname, $this->opt);
        $resources = $this->streamResourceCollector->streamCollect($bucketName, ...$resourceObjects);

        foreach ($resources as $objkey => $obj) {
            if (!is_resource($obj)) {
                continue;
            }

            $zip->addFileFromStream($objkey, $obj);
        }

        $zip->finish();

        rewind($this->stream);

        return $this->stream;
    }
}
