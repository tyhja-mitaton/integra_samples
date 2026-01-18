<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\UpdateDepositStatus\Handler;

use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\MindBox\Enum\MindBoxOperationNameEnum;
use Integra\Domain\Integration\MindBox\MindBoxConfig;
use Integra\Domain\Integration\MindBox\Operation\AbstractMindBoxOperationHandler;
use Integra\Domain\Integration\MindBox\Operation\UpdateDepositStatus\Data\UpdateDepositStatusDataBuilder;
use Integra\Domain\Integration\MindBox\Operation\UpdateDepositStatus\Request\UpdateDepositStatusRequest;
use Integra\Infrastructure\Http\Transport;

class UpdateDepositStatusHandler extends AbstractMindBoxOperationHandler
{
    private UpdateDepositStatusDataBuilder $builder;

    public function __construct(
        Transport                    $transport
    )
    {
        parent::__construct($transport);
        $this->builder = new UpdateDepositStatusDataBuilder();
    }

    /**
     * @param array $raw
     * @return DTOInterface
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            paymentId: (int)$raw['payId'],
            statusId: (int)$raw['statusId'],
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
            MindBoxOperationNameEnum::UPDATE_DEPOSIT_STATUS,
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
        return new UpdateDepositStatusRequest($dto, $config);
    }
}