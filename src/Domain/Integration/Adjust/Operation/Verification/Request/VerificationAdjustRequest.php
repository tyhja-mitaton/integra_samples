<?php

namespace Integra\Domain\Integration\Adjust\Operation\Verification\Request;

use Integra\Domain\Integration\Common\AbstractRequest;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Infrastructure\Http\Request\Method;
use Integra\Infrastructure\Http\Request\Method\Post;

class VerificationAdjustRequest extends AbstractRequest implements RequestInterface
{
    /**
     * @inheritDoc
     */
    public function method(): Method
    {
        return new Post;
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
        return [
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            'Authorization: Bearer ' . $this->config->getBearerToken(),
        ];
    }
}