<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Affise\Config;

use Integra\Domain\Integration\Common\IntegrationConfigInterface;

interface AffiseOperationConfigInterface extends IntegrationConfigInterface
{
    public function getToken(): string;
}
