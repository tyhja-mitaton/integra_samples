<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Storage\S3;

use Exception;
use Integra\Infrastructure\Generic\Result;
use Integra\Infrastructure\Generic\Result\Failed;
use Integra\Infrastructure\Generic\Result\Successful;
use Yii;

class S3DeletedFile
{
    private S3Interface $s3;
    private string $targetPath;

    public function __construct(S3Interface $s3, string $targetPath)
    {
        $this->s3 = $s3;
        $this->targetPath = $targetPath;
    }

    public function result(): Result
    {
        try {
            $this->s3->client()->deleteObject(
                [
                    'Bucket' => $this->s3->bucket(),
                    'Key' => $this->targetPath,
                ]
            );
        } catch (Exception $exception) {
            Yii::error('S3: ' . $exception->getMessage());

            return new Failed(['File delete error to S3']);
        }

        return new Successful([]);
    }
}
