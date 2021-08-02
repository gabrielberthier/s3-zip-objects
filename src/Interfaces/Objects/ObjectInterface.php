<?php

namespace S3DataTransfer\Interfaces\Objects;

interface ObjectInterface
{
    public function extension(): string;

    public function name(): ?string;

    public function path(): string;
}
