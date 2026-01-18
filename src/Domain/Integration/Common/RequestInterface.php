<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Common;

use Integra\Infrastructure\Http\ExtendedRequest;

interface RequestInterface  extends ExtendedRequest
{
    public function getDTO(): DTOInterface;
    public function getConfig(): IntegrationConfigInterface;
}
