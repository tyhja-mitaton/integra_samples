<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Promo\Config;

use Integra\Infrastructure\Http\Request\Url;
use Integra\Domain\Integration\Common\DTOInterface;

/**
 * Конфиг для Promo операций.
 */
final class PromoOperationConfig implements PromoOperationConfigInterface
{
    public function __construct(
        private readonly Url $url,
        private readonly string $token,
        private readonly int $timeoutSeconds
    ) {}

    public function getUrl(DTOInterface $dto = null): Url
    {
        return $this->url;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getTimeoutSeconds(): int
    {
        return $this->timeoutSeconds;
    }
}