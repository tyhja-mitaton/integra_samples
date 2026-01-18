<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Error;

use Throwable;
use Integra\Infrastructure\Http\DefaultResponseFormat;
use Integra\Infrastructure\Response\FromThrowable\WithDebug;
use Integra\Infrastructure\Response\FromThrowable\WithEmptyPayload;

class JsonExceptionHandler
{
    public function set(): void
    {
        set_exception_handler(
            function (Throwable $throwable) {

                header('Content-Type: application/json');
                http_response_code($throwable->statusCode ?? 500);

                echo
                json_encode(
                    (new DefaultResponseFormat(
                        YII_DEBUG
                            ? new WithDebug($throwable)
                            : new WithEmptyPayload($throwable)
                    ))
                        ->value(),
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | (YII_DEBUG ? JSON_PRETTY_PRINT : 0)
                );
                exit();
            }
        );
    }
}
