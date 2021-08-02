<?php

namespace S3DataTransfer\Interfaces\Objects;

use Psr\Http\Message\StreamInterface;

interface UploadableObjectInterface
{
    public function key(): string;

    public function source(): string | StreamInterface;
}
