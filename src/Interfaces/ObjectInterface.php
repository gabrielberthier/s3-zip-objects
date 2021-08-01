<?php

namespace S3DataTransfer\Interfaces;

interface ObjectInterface
{
    public function extension(): string;

    public function name(): ?string;

    public function path(): string;
}
