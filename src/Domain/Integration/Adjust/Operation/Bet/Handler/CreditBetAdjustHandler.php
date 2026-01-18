<?php

namespace Integra\Domain\Integration\Adjust\Operation\Bet\Handler;

use Integra\Domain\Enum\PlatformNameEnum;
use Integra\Domain\Integration\Adjust\AdjustConfig;
use Integra\Domain\Integration\Adjust\Enum\S2SEventEnum;
use Integra\Domain\Integration\Adjust\Operation\AbstractAdjustOperationHandler;
use Integra\Domain\Integration\Adjust\Operation\Bet\Data\CreditBetAdjustDataBuilder;
use Integra\Domain\Integration\Adjust\Operation\Bet\Request\BetAdjustRequest;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Infrastructure\Http\Transport;
use DomainException;

class CreditBetAdjustHandler extends AbstractAdjustOperationHandler
{
    private CreditBetAdjustDataBuilder $builder;

    public function __construct(protected readonly Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new CreditBetAdjustDataBuilder();
    }

    /**
     * @inheritDoc
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            userId: $raw['userId'],
            orderNumber: $raw['orderNumber'],
            partnerId: $raw['partnerId'],
            offerId: $raw['offerId'],
        );
    }

    /**
     * @inheritDoc
     */
    protected function buildConfig(array $raw): IntegrationConfigInterface
    {
        $platform = PlatformNameEnum::tryFrom((string)$raw['deviceReg']);

        $event = match ($platform) {
            PlatformNameEnum::ANDROID => S2SEventEnum::CREDIT_BET_ANDROID,
            PlatformNameEnum::IOS => S2SEventEnum::CREDIT_BET_IOS,
            default => throw new DomainException('Unsupported Adjust platform: ' . $platform->value()),
        };

        return AdjustConfig::create(
            eventToken: $event,
            platform: $platform,
            timeoutSeconds: $raw['timeoutSeconds'] ?? null
        );
    }

    /**
     * @inheritDoc
     */
    protected function buildRequest(DTOInterface $dto, IntegrationConfigInterface $config): RequestInterface
    {
        return new BetAdjustRequest(
            dto: $dto,
            config: $config
        );
    }
}