<?php

namespace Integra\Domain\Integration\Affise\Operation\Deposit\Request;

use Integra\Domain\Integration\Common\AbstractRequest;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Infrastructure\Http\Request\Method;
use Integra\Infrastructure\Http\Request\Method\Get;

class DepositRequest extends AbstractRequest implements RequestInterface
{
    /**
     * @inheritDoc
     */
    public function method(): Method
    {
        return new Get();
    }

    /**
     * @inheritDoc
     */
    public function getDTO(): DTOInterface
    {
        return $this->dto;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): IntegrationConfigInterface
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function headers(): array
    {
        return [];
    }
}