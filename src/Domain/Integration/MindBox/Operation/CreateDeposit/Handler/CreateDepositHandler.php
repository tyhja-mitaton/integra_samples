<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\CreateDeposit\Handler;

use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\MindBox\Enum\MindBoxOperationNameEnum;
use Integra\Domain\Integration\MindBox\MindBoxConfig;
use Integra\Domain\Integration\MindBox\Operation\AbstractMindBoxOperationHandler;
use Integra\Domain\Integration\MindBox\Operation\CreateDeposit\Data\CreateDepositDataBuilder;
use Integra\Infrastructure\Http\Transport;
use Integra\Domain\Integration\MindBox\Operation\CreateDeposit\Request\CreateDepositRequest;

class CreateDepositHandler extends AbstractMindBoxOperationHandler
{
    private CreateDepositDataBuilder $builder;

    public function __construct(
        Transport                    $transport
    )
    {
        parent::__construct($transport);
        $this->builder = new CreateDepositDataBuilder();
    }

    /**
     * @param array $raw
     * @return DTOInterface
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            paymentId: (int)$raw['payId'],
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
            MindBoxOperationNameEnum::CREATE_DEPOSIT,
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
        return new CreateDepositRequest($dto, $config);
    }
}