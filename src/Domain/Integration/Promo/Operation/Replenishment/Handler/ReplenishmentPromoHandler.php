<?php

namespace Integra\Domain\Integration\Promo\Operation\Replenishment\Handler;

use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\Promo\Operation\AbstractPromoOperationHandler;
use Integra\Domain\Integration\Promo\Operation\Replenishment\Data\ReplenishmentDataBuilder;
use Integra\Domain\Integration\Promo\Operation\Replenishment\Request\ReplenishmentPromoRequest;
use Integra\Domain\Integration\Promo\PromoConfig;
use Integra\Infrastructure\Http\Transport;

class ReplenishmentPromoHandler extends AbstractPromoOperationHandler
{
    private ReplenishmentDataBuilder $builder;

    public function __construct(Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new ReplenishmentDataBuilder();
    }

    /**
     * @inheritDoc
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            (int)$raw['payId'],
        );
    }

    /**
     * @inheritDoc
     */
    protected function buildConfig(array $raw): IntegrationConfigInterface
    {
        return PromoConfig::create(
            isset($raw['timeoutSeconds']) ? (int)$raw['timeoutSeconds'] : null,
        );
    }

    /**
     * @inheritDoc
     */
    protected function buildRequest(DTOInterface $dto, IntegrationConfigInterface $config): RequestInterface
    {
        return new ReplenishmentPromoRequest(
            dto: $dto,
            config: $config
        );
    }
}