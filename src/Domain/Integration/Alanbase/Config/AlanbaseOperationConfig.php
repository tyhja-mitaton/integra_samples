<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Alanbase\Config;

use Integra\Infrastructure\Http\Request\Url;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseEventEnum;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseOperationTypeEnum;

/**
 * «Чистый» конфиг Alanbase-операции.
 */
final class AlanbaseOperationConfig implements AlanbaseOperationConfigInterface
{
    public function __construct(
        private readonly Url                                $url,
        private readonly AlanbaseOperationTypeEnum          $operationType,
        private readonly AlanbaseGoalEnum|AlanbaseEventEnum $operationName,
        private readonly int                                $timeoutSeconds,
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
     * @return AlanbaseOperationTypeEnum
     */
    public function getOperationType(): AlanbaseOperationTypeEnum
    {
        return $this->operationType;
    }

    /**
     * @return AlanbaseGoalEnum|AlanbaseEventEnum
     */
    public function getOperationName(): AlanbaseGoalEnum|AlanbaseEventEnum
    {
        return $this->operationName;
    }

    /**
     * @return int
     */
    public function getTimeoutSeconds(): int
    {
        return $this->timeoutSeconds;
    }
}
