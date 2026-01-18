<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Affise\Operation\Register\Request;

use Integra\Infrastructure\Http\Request\Method\Get;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\AbstractRequest;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;

/**
 * Request для отправки registration в Affise.
 */
final class RegisterRequest extends AbstractRequest implements RequestInterface
{
    public function method(): Get
    {
        return new Get();
    }

    public function getDTO(): DTOInterface
    {
        return $this->dto;
    }

    public function getConfig(): IntegrationConfigInterface
    {
        return $this->config;
    }

    public function headers(): array
    {
        return [];
    }
}