<?php

namespace Tests\Mocks;

use Aws\MockHandler as Mocker;
use Aws\Result;
use Aws\S3\S3Client;

class MockHandler
{
    public function createMock(S3Client $s3Client)
    {
        /**
         * Create a MockHandler to list all the files in an Amazon S3 bucket.
         *
         * This code expects that you have AWS credentials set up per:
         * https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/guide_credentials.html
         */
        // Create a mock handler
        $mock = new Mocker();
        // Enqueue a mock result to the handler
        $mock->append(new Result(['foo' => 'bar']));
        // Create a "ListObjects" command
        $command = $s3Client->getCommand('ListObjects');
        // Associate the mock handler with the command
        $command->getHandlerList()->setHandler($mock);
        // Executing the command will use the mock handler, which will return the
        // mocked result object
        $result = $s3Client->execute($command);
    }
}
