<?php

namespace S3DataTransfer\Interfaces;

use Psr\Http\Message\StreamInterface;

interface UploadableObjectInterface
{
    public function key(): string;

    public function source(): string | StreamInterface;
}
