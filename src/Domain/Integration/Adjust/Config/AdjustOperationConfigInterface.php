<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Adjust\Config;

use Integra\Domain\Integration\Adjust\Enum\S2SEventEnum;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;

interface AdjustOperationConfigInterface extends IntegrationConfigInterface
{
    public function getBearerToken(): string;

    public function getAppToken(): string;

    public function getS2SEventToken(): S2SEventEnum;

    public function getEnvironment(): string;
}
