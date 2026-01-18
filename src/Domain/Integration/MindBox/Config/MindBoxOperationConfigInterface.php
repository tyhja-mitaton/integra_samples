<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Config;

use Integra\Domain\Enum\PlatformNameEnum;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\MindBox\Enum\MindBoxOperationNameEnum;

/**
 * Контракт для конфига MindBox-операций
 */
interface MindBoxOperationConfigInterface extends IntegrationConfigInterface
{
    public function getOperationName(): MindBoxOperationNameEnum;
//    public function getPlatform(): PlatformNameEnum;
    public function getSecretKey(): string;
}
