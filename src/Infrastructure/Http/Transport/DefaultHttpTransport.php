<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Http\Transport;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Integra\Infrastructure\Generic\Result;
use Integra\Infrastructure\Generic\Result\Failed;
use Integra\Infrastructure\Generic\Result\Successful;
use Integra\Infrastructure\Http\ExtendedRequest;
use Integra\Infrastructure\Http\Request;
use Integra\Infrastructure\Http\Transport;
use Throwable;

class DefaultHttpTransport implements Transport
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function response(Request $request): Result
    {
        try {
            $request =
                $this->client->request(
                    $request->method()->value(),
                    $request->url()->value(),
                    array_merge(
                        [
                            RequestOptions::HTTP_ERRORS => true,
                            RequestOptions::HEADERS => $request->headers(),
                            RequestOptions::BODY => $request->body(),
                        ],
                        $request instanceof ExtendedRequest
                            ? $request->options()
                            : [],
                    )
                );
        } catch (Throwable $exception) {
            return
                new Failed(
                    array_merge(
                        [
                            'message' => $exception->getMessage(),
                            'code' => null,
                            'body' => null,
                        ],
                        $exception instanceof RequestException
                            ? [
                            'code' => $exception->getResponse()->getStatusCode(),
                            'body' => $exception->getResponse()->getBody()->getContents(),
                        ]
                            : [],
                    )
                );
        }

        return
            new Successful([
                'body' => $request->getBody()->getContents(),
                'headers' => $request->getHeaders(),
                'code' => $request->getStatusCode(),
            ]);
    }
}
