<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Http\Transport;

use Yii;
use yii\helpers\StringHelper;
use Integra\Infrastructure\Http\Request;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Generic\Result;
use Integra\Domain\Integration\TempLocalDebug;
use Integra\Infrastructure\Http\ExtendedRequest;

/**
 * Декоратор, оборачивающий любой Transport и при неуспехе логирует тело запроса (options) и тело ответа (error).
 */
class LoggingTransport implements Transport
{
    public function __construct(
        private readonly Transport $inner,
    )
    {
    }

    public function response(Request $request): Result
    {
        $result = $this->inner->response($request);

        if (!$result->isSuccessful()) {
            $messageRequest = sprintf(
                '[%s] Request: %s %s %s',
                StringHelper::basename(static::class),
                $request->method()->value(),
                $request->url()->value(),
                json_encode(
                    $request instanceof ExtendedRequest
                        ? $request->options()
                        : [],
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ));
            Yii::error($messageRequest, __METHOD__);

            $messageResponse = sprintf(
                '[%s] Response error: %s',
                StringHelper::basename(static::class),
                json_encode(
                    $result->error(),
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                ));
            Yii::error($messageResponse, __METHOD__);
        }

        return $result;
    }
}