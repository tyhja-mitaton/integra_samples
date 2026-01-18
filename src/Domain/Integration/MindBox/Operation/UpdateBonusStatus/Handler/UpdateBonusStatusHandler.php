<?php

namespace Integra\Domain\Integration\MindBox\Operation\UpdateBonusStatus\Handler;

use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\MindBox\Enum\MindBoxOperationNameEnum;
use Integra\Domain\Integration\MindBox\MindBoxConfig;
use Integra\Domain\Integration\MindBox\Operation\AbstractMindBoxOperationHandler;
use Integra\Domain\Integration\MindBox\Operation\UpdateBonusStatus\Data\UpdateBonusStatusDataBuilder;
use Integra\Domain\Integration\MindBox\Operation\UpdateBonusStatus\Request\UpdateBonusStatusRequest;
use Integra\Infrastructure\Http\Transport;

class UpdateBonusStatusHandler extends AbstractMindBoxOperationHandler
{
    private UpdateBonusStatusDataBuilder $builder;

    public function __construct(
        Transport                    $transport
    )
    {
        parent::__construct($transport);
        $this->builder = new UpdateBonusStatusDataBuilder();
    }

    /**
     * @param array $raw
     * @return DTOInterface
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            bonusId: (int)$raw['bonusId'],
            statusId: (int)$raw['statusId'],
            executedDateTimeUtc: $raw['executedDateTimeUtc']
        );
    }

    /**
     * @param array $raw
     * @return IntegrationConfigInterface
     * @throws \Exception
     */
    protected function buildConfig(array $raw): IntegrationConfigInterface
    {
        $timeout = isset($raw['timeoutSeconds']) ? (int)$raw['timeoutSeconds'] : null;

        return MindBoxConfig::create(
            MindBoxOperationNameEnum::UPDATE_BONUS_STATUS,
            null,
            $timeout,
        );
    }

    /**
     * @param DTOInterface $dto
     * @param IntegrationConfigInterface $config
     * @return RequestInterface
     */
    protected function buildRequest(DTOInterface $dto, IntegrationConfigInterface $config): RequestInterface
    {
        return new UpdateBonusStatusRequest($dto, $config);
    }
}