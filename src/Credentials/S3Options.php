<?php

namespace S3DataTransfer\Credentials;

use JsonSerializable;

class S3Options implements JsonSerializable
{
    public function __construct(
        private S3Credentials $s3Credentials,
        private string $region,
        private string $version = 'latest|version'
    ) {
    }

    public function createConfiguration(): array
    {
        return [
            'credentials' => $this->s3Credentials->retrieveCredentials(),
            'region' => $this->region,
            'version' => $this->version,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->createConfiguration();
    }
}
