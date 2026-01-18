<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateDeposit\Request;

use Integra\Domain\Integration\Common\AbstractRequest;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Infrastructure\Http\Request\Method;
use Integra\Infrastructure\Http\Request\Method\Post;

class CreateDepositRequest extends AbstractRequest implements RequestInterface
{

    /**
     * @return Method
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
     * @return string[]
     */
    public function headers(): array
    {
        return [
            'Content-Type' => 'application/json; charset=utf-8',
            'Accept' => 'application/json',
            'Authorization' => 'Mindbox secretKey="' . $this->config->getSecretKey() . '"'
        ];
    }
}