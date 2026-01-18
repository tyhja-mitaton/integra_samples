<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Transport;

use Integra\Infrastructure\Generic\Result;
use Integra\Infrastructure\Http\Request;
use Integra\Infrastructure\Http\Transport;
use yii\caching\CacheInterface;

class CachedTransport implements Transport
{
    private Transport $transport;
    private CacheInterface $cache;
    private int $duration;

    public function __construct(Transport $transport, CacheInterface $cache, int $cacheTimeInSeconds = 30)
    {
        $this->transport = $transport;
        $this->cache = $cache;
        $this->duration = $cacheTimeInSeconds;
    }

    public function response(Request $request): Result
    {
        return
            $this->cache->getOrSet(
                'cached_transport' . sha1(serialize($request)),
                function () use ($request) {
                    return $this->transport->response($request);
                },
                $this->duration
            );
    }
}
