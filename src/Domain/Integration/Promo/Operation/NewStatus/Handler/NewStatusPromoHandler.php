<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Promo\Operation\NewStatus\Handler;

use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\Promo\Operation\AbstractPromoOperationHandler;
use Integra\Domain\Integration\Promo\Operation\NewStatus\Data\NewStatusDataBuilder;
use Integra\Domain\Integration\Promo\Operation\NewStatus\Request\NewStatusPromoRequest;
use Integra\Domain\Integration\Promo\PromoConfig;
use Integra\Infrastructure\Http\Transport;

class NewStatusPromoHandler extends AbstractPromoOperationHandler
{
    private NewStatusDataBuilder $builder;

    public function __construct(Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new NewStatusDataBuilder();
    }

    /**
     * @param array $raw
     * @return DTOInterface
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            (int)$raw['userId'],
            (int)$raw['statusId'],
        );
    }

    /**
     * @param array $raw
     * @return IntegrationConfigInterface
     * @throws \Exception
     */
    protected function buildConfig(array $raw): IntegrationConfigInterface
    {
        return PromoConfig::create(
            isset($raw['timeoutSeconds']) ? (int)$raw['timeoutSeconds'] : null,
        );
    }

    /**
     * @param DTOInterface $dto
     * @param IntegrationConfigInterface $config
     * @return RequestInterface
     */
    protected function buildRequest(DTOInterface $dto, IntegrationConfigInterface $config): RequestInterface
    {
        return new NewStatusPromoRequest(
            dto: $dto,
            config: $config
        );
    }
}