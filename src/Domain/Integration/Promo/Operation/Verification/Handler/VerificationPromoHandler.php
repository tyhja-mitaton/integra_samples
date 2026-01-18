<?php

namespace Integra\Domain\Integration\Promo\Operation\Verification\Handler;

use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\Promo\Operation\AbstractPromoOperationHandler;
use Integra\Domain\Integration\Promo\Operation\Verification\Data\VerificationDataBuilder;
use Integra\Domain\Integration\Promo\Operation\Verification\Request\VerificationPromoRequest;
use Integra\Domain\Integration\Promo\PromoConfig;
use Integra\Infrastructure\Http\Transport;

class VerificationPromoHandler extends AbstractPromoOperationHandler
{
    private VerificationDataBuilder $builder;

    public function __construct(Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new VerificationDataBuilder();
    }

    /**
     * @inheritDoc
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            (int)$raw['userId'],
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
        return new VerificationPromoRequest(
            dto: $dto,
            config: $config
        );
    }
}