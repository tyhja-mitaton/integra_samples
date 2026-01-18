<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Adjust\Config;

use Integra\Infrastructure\Http\Request\Url;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Adjust\Enum\S2SEventEnum;

final class AdjustOperationConfig implements AdjustOperationConfigInterface
{
    public function __construct(
        private readonly Url          $url,
        private readonly int          $timeoutSeconds,
        private readonly string       $appToken,
        private readonly string       $bearerToken,
        private readonly S2SEventEnum $eventToken,
        private readonly string       $environment
    )
    {
    }

    /**
     * @param DTOInterface|null $dto
     * @return Url
     */
    public function getUrl(DTOInterface $dto = null): Url
    {
        return $this->url;
    }


    /**
     * @return string
     */
    public function getBearerToken(): string
    {
        return $this->bearerToken;
    }

    /**
     * @return string
     */
    public function getAppToken(): string
    {
        return $this->appToken;
    }

    /**
     * @return S2SEventEnum
     */
    public function getS2SEventToken(): S2SEventEnum
    {
        return $this->eventToken;
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @return int
     */
    public function getTimeoutSeconds(): int
    {
        return $this->timeoutSeconds;
    }
}