<?php

namespace S3DataTransfer\Objects;

use S3DataTransfer\Exceptions\InvalidParamsException;
use S3DataTransfer\Interfaces\Objects\ObjectInterface;

final class ResourceObject implements ObjectInterface
{
    public function __construct(private string $path, private ?string $name = '')
    {
        if (empty($path)) {
            throw new InvalidParamsException('The `path` cannot be an empty string.');
        }
    }

    public function path(): string
    {
        return $this->path;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function extension(): string
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }
}
