<?php

namespace Integra\Domain\Integration\AppsFlyer\Operation\Deposit\Handler;

use Integra\Domain\Enum\PlatformNameEnum;
use Integra\Domain\Integration\AppsFlyer\Config\AppsFlyerConfig;
use Integra\Domain\Integration\AppsFlyer\Operation\AbstractAppsFlyerOperationHandler;
use Integra\Domain\Integration\AppsFlyer\Operation\Deposit\Data\DepositAppsFlyerDataBuilder;
use Integra\Domain\Integration\AppsFlyer\Operation\Deposit\Request\DepositAppsFlyerRequest;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Infrastructure\Http\Transport;

class DepositAppsFlyerHandler extends AbstractAppsFlyerOperationHandler
{
    private DepositAppsFlyerDataBuilder $builder;

    public function __construct(protected readonly Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new DepositAppsFlyerDataBuilder();
    }

    /**
     * @inheritDoc
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            paymentId: $raw['paymentId'],
            device: $raw['deviceReg'],
        );
    }

    /**
     * @inheritDoc
     */
    protected function buildConfig(array $raw): IntegrationConfigInterface
    {
        $platform = PlatformNameEnum::tryFrom((string)$raw['deviceReg']);

        return AppsFlyerConfig::create(
            platform: $platform,
            timeoutSeconds: $raw['timeoutSeconds'] ?? null
        );
    }

    /**
     * @inheritDoc
     */
    protected function buildRequest(DTOInterface $dto, IntegrationConfigInterface $config): RequestInterface
    {
        return new DepositAppsFlyerRequest(
            dto: $dto,
            config: $config
        );
    }
}