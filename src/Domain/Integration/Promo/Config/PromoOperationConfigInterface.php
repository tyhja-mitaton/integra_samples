<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Promo\Config;

use Integra\Domain\Integration\Common\IntegrationConfigInterface;

interface PromoOperationConfigInterface extends IntegrationConfigInterface
{
    public function getToken(): string;
}
