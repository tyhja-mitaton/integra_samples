<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Adjust\Operation\Register\Handler;

use Exception;
use DomainException;
use Integra\Domain\Enum\PlatformNameEnum;
use Integra\Infrastructure\Http\Transport;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Adjust\AdjustConfig;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\Adjust\Enum\S2SEventEnum;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Adjust\Operation\AbstractAdjustOperationHandler;
use Integra\Domain\Integration\Adjust\Operation\Register\Data\RegisterAdjustDataBuilder;
use Integra\Domain\Integration\Adjust\Operation\Register\Request\RegisterAdjustRequest;

final class RegisterAdjustHandler extends AbstractAdjustOperationHandler
{
    private RegisterAdjustDataBuilder $builder;

    public function __construct(protected readonly Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new RegisterAdjustDataBuilder();
    }

    /**
     * @param array $raw
     * @return DTOInterface
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            userId: $raw['userId'],
            adjustAdid: $raw['adjustAdid'],
            gpsAdid: $raw['gpsAdid'],
            idfa: $raw['idfa'],
            idfv: $raw['idfv'],
            partnerId: $raw['partnerId'],
            offerId: $raw['offerId'],
        );
    }

    /**
     * @param array $raw
     * @return IntegrationConfigInterface
     * @throws Exception
     */
    protected function buildConfig(array $raw): IntegrationConfigInterface
    {
        $platform = PlatformNameEnum::tryFrom((string)$raw['deviceReg']);

        $event = match ($platform) {
            PlatformNameEnum::ANDROID => S2SEventEnum::REGISTRATION_ANDROID,
            PlatformNameEnum::IOS => S2SEventEnum::REGISTRATION_IOS,
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
        return new RegisterAdjustRequest(
            dto: $dto,
            config: $config
        );
    }
}
