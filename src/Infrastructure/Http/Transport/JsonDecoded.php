<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Http\Transport;

use Integra\Infrastructure\Generic\Result;
use Integra\Infrastructure\Generic\Result\Failed;
use Integra\Infrastructure\Generic\Result\Successful;
use Integra\Infrastructure\Http\Request;
use Integra\Infrastructure\Http\Transport;

class JsonDecoded implements Transport
{
    private Transport $transport;

    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
    }

    public function response(Request $request): Result
    {
        $result = $this->transport->response($request);

        if (!$result->isSuccessful()) {
            return $result;
        }

        $body = $result->value()['body'];

        if (empty($body)) {
            return new Successful([]);
        }

        $response = json_decode($body, true);

        if(!is_array($response)){
            return new Failed(['The json decoded result is not an array']);
        }

        return new Successful($response);
    }
}
