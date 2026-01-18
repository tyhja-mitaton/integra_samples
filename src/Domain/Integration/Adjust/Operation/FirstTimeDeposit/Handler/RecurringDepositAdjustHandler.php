<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Adjust\Operation\FirstTimeDeposit\Handler;

use Integra\Domain\Enum\PlatformNameEnum;
use Integra\Domain\Integration\Adjust\AdjustConfig;
use Integra\Domain\Integration\Adjust\Enum\S2SEventEnum;
use Integra\Domain\Integration\Adjust\Operation\AbstractAdjustOperationHandler;
use Integra\Domain\Integration\Adjust\Operation\FirstTimeDeposit\Data\RecurringDepositAdjustDataBuilder;
use Integra\Domain\Integration\Adjust\Operation\FirstTimeDeposit\Request\FirstTimeDepositAdjustRequest;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Infrastructure\Http\Transport;
use DomainException;

class RecurringDepositAdjustHandler extends AbstractAdjustOperationHandler
{
    private RecurringDepositAdjustDataBuilder $builder;

    public function __construct(protected readonly Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new RecurringDepositAdjustDataBuilder();
    }

    /**
     * @param array $raw
     * @return DTOInterface
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            userId: $raw['userId'],
            paymentId: $raw['paymentId'],
            partnerId: $raw['partnerId'],
            offerId: $raw['offerId'],
        );
    }

    /**
     * @param array $raw
     * @return IntegrationConfigInterface
     * @throws \Exception
     */
    protected function buildConfig(array $raw): IntegrationConfigInterface
    {
        $platform = PlatformNameEnum::tryFrom((string)$raw['deviceReg']);

        $event = match ($platform) {
            PlatformNameEnum::ANDROID => S2SEventEnum::RD_ANDROID,
            PlatformNameEnum::IOS => S2SEventEnum::RD_IOS,
            default => throw new DomainException('Unsupported Adjust platform: ' . $platform->value()),
        };

        return AdjustConfig::create(
            eventToken: $event,
            platform: $platform,
            timeoutSeconds: $raw['timeoutSeconds'] ?? null
        );
    }

    /**
     * @param DTOInterface $dto
     * @param IntegrationConfigInterface $config
     * @return RequestInterface
     */
    protected function buildRequest(DTOInterface $dto, IntegrationConfigInterface $config): RequestInterface
    {
        return new FirstTimeDepositAdjustRequest(
            dto: $dto,
            config: $config
        );
    }
}