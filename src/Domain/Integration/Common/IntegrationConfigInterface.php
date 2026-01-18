<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Common;

use Integra\Infrastructure\Http\Request\Url;

interface IntegrationConfigInterface
{
    /**
     * Таймаут в секундах.
     */
    public function getTimeoutSeconds(): int;

    /**
     * Если DTO передан — билдит URL с query-параметрами,
     * иначе — просто базовый URL.
     */
    public function getUrl(DTOInterface $dto = null): Url;
}
