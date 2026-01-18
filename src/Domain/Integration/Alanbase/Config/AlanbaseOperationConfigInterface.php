<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Alanbase\Config;

use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseEventEnum;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseOperationTypeEnum;

interface AlanbaseOperationConfigInterface extends IntegrationConfigInterface
{
    public function getOperationType(): AlanbaseOperationTypeEnum;

    public function getOperationName(): AlanbaseGoalEnum|AlanbaseEventEnum;
}
