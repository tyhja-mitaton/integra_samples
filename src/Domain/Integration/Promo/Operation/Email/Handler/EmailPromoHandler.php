<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Promo\Operation\Email\Handler;

use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\Promo\Operation\AbstractPromoOperationHandler;
use Integra\Domain\Integration\Promo\Operation\Email\Data\EmailDataBuilder;
use Integra\Domain\Integration\Promo\Operation\Email\Request\EmailPromoRequest;
use Integra\Domain\Integration\Promo\PromoConfig;
use Integra\Infrastructure\Http\Transport;

class EmailPromoHandler extends AbstractPromoOperationHandler
{
    private EmailDataBuilder $builder;

    public function __construct(Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new EmailDataBuilder();
    }

    /**
     * @param array $raw
     * @return DTOInterface
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            (int)$raw['userId'],
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
        return new EmailPromoRequest(
            dto: $dto,
            config: $config
        );
    }
}