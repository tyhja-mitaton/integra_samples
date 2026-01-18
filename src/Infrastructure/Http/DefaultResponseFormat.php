<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Http;

use Integra\Infrastructure\Generic\Response;

class DefaultResponseFormat
{
    private Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function value(): array
    {
        return
            array_merge(
                [
                    'result' => [
                        'successful' => $this->response->isSuccessful(),
                        'code' => $this->response->code(),
                    ],
                ],
                $this->response->isSuccessful()
                    ? [
                    'payload' => $this->response->payload(),
                    'translated' => $this->response->translated()
                ]
                    : [
                    'error' => $this->response->payload(),
                    'translated' => $this->response->translated()
                ],
                $this->response->pagination()->isUsed()
                    ? [
                    'pagination' => [
                        'total' => $this->response->pagination()->total(),
                        'per_page' => $this->response->pagination()->perPage(),
                        'page' => $this->response->pagination()->page(),
                        'pages' => $this->response->pagination()->pages(),
                    ]
                ]
                    : []
            );
    }
}
