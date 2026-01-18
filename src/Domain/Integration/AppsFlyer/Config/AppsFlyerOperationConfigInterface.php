<?php

namespace Integra\Domain\Integration\AppsFlyer\Config;

use Integra\Domain\Integration\Common\IntegrationConfigInterface;

interface AppsFlyerOperationConfigInterface extends IntegrationConfigInterface
{
    public function getAccessToken(): string;
}