<?php

namespace S3DataTransfer\Objects;

use Psr\Http\Message\StreamInterface;
use S3DataTransfer\Interfaces\Objects\UploadableObjectInterface;

final class UploadableObject implements UploadableObjectInterface
{
    public function __construct(private string $key, private string | StreamInterface $source)
    {
    }

    public function key(): string
    {
        return $this->key;
    }

    public function source(): string | StreamInterface
    {
        return $this->source;
    }
}
