<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Alanbase;

use Exception;
use DomainException;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Request\Url\FromString;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseEventEnum;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseOperationTypeEnum;
use Integra\Domain\Integration\Alanbase\Config\AlanbaseOperationConfig;
use Integra\Domain\Integration\Alanbase\Config\AlanbaseOperationConfigInterface;

/**
 * Создания конфига Alanbase из ENV.
 */
final class AlanbaseConfig
{
    private const DEFAULT_TIMEOUT_SECONDS = 10;

    /**
     * @param AlanbaseOperationTypeEnum $operationType
     * @param AlanbaseGoalEnum|AlanbaseEventEnum $operationName
     * @param int|null $timeoutSeconds
     * @return AlanbaseOperationConfigInterface
     * @throws Exception
     */
    public static function create(
        AlanbaseOperationTypeEnum          $operationType,
        AlanbaseGoalEnum|AlanbaseEventEnum $operationName,
        ?int                               $timeoutSeconds = null,
    ): AlanbaseOperationConfigInterface
    {
        $urlEnvKey = match ($operationType) {
            AlanbaseOperationTypeEnum::GOAL => 'UB_ALANBASE_GOALS_URL',
            AlanbaseOperationTypeEnum::EVENT => 'UB_ALANBASE_EVENTS_URL',
        };

        if (
            ($operationType === AlanbaseOperationTypeEnum::GOAL && !($operationName instanceof AlanbaseGoalEnum))
            || ($operationType === AlanbaseOperationTypeEnum::EVENT && !($operationName instanceof AlanbaseEventEnum))
        ) {
            throw new DomainException('Operation name enum does not match operation type');
        }

        return new AlanbaseOperationConfig(
            url: new FromString((new Env($urlEnvKey))->value()),
            operationType: $operationType,
            operationName: $operationName,
            timeoutSeconds: $timeoutSeconds ?? self::DEFAULT_TIMEOUT_SECONDS,
        );
    }
}
