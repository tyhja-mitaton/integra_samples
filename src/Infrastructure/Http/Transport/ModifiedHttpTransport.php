<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Http\Transport;

use Throwable;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\ClientInterface;
use Integra\Infrastructure\Http\Request;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Generic\Result;
use GuzzleHttp\Exception\RequestException;
use Integra\Infrastructure\Http\ExtendedRequest;
use Integra\Infrastructure\Generic\Result\Failed;
use Integra\Infrastructure\Generic\Result\Successful;

/**
 * DefaultHttpTransport с измененным поведением обработки исключений.
 */
class ModifiedHttpTransport implements Transport
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
            if ($exception instanceof RequestException) {
                if ($exception->getResponse() !== null) {
                    return new Failed([
                        'message' => $exception->getMessage(),
                        'code' => $exception->getResponse()->getStatusCode(),
                        'body' => $exception->getResponse()->getBody()->getContents(),
                    ]);
                }
            }
            return new Failed([
                'message' => $exception->getMessage(),
                'code' => null,
                'body' => null,
            ]);
        }

        return
            new Successful([
                'body' => $request->getBody()->getContents(),
                'headers' => $request->getHeaders(),
                'code' => $request->getStatusCode(),
            ]);
    }
}
