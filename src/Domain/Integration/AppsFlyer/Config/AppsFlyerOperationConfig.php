<?php

namespace Integra\Domain\Integration\AppsFlyer\Config;

use Integra\Domain\Integration\AppsFlyer\Config\AppsFlyerOperationConfigInterface;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Infrastructure\Http\Request\Url;

class AppsFlyerOperationConfig implements AppsFlyerOperationConfigInterface
{
    public function __construct(
        private readonly Url          $url,
        private readonly int          $timeoutSeconds,
        private readonly string       $accessToken,
    )
    {
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @inheritDoc
     */
    public function getTimeoutSeconds(): int
    {
        return $this->timeoutSeconds;
    }

    /**
     * @inheritDoc
     */
    public function getUrl(DTOInterface $dto = null): Url
    {
        return $this->url;
    }
}