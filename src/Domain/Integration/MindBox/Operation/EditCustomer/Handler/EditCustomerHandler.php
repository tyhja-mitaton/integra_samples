<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\EditCustomer\Handler;

use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Enum\PlatformNameEnum;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\MindBox\Enum\MindBoxOperationNameEnum;
use Integra\Domain\Integration\MindBox\MindBoxConfig;
use Integra\Domain\Integration\MindBox\Operation\AbstractMindBoxOperationHandler;
use Integra\Domain\Integration\MindBox\Operation\EditCustomer\Data\EditCustomerDataBuilder;
use Integra\Domain\Integration\MindBox\Operation\EditCustomer\Request\EditCustomerRequest;
use Integra\Domain\Services\User\BackofficePlayerMarksService;
use Integra\Domain\Services\User\BetInfoService;
use Integra\Domain\Services\User\BlacklistService;
use Integra\Domain\Services\User\CashbackService;
use Integra\Infrastructure\Http\Transport;

class EditCustomerHandler extends AbstractMindBoxOperationHandler
{
    private EditCustomerDataBuilder $builder;

    public function __construct(
        Transport                    $transport,
        BlacklistService             $blacklistService,
        CashbackService              $cashbackService,
        BackofficePlayerMarksService $playerMarksService,
        BetInfoService $betInfoService,
    )
    {
        parent::__construct($transport);

        $this->builder = new EditCustomerDataBuilder(
            $blacklistService,
            $cashbackService,
            $playerMarksService,
            $betInfoService
        );
    }

    /**
     * @param array $raw
     * @return DTOInterface
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            userId: (int)$raw['userId'],
            landing: $raw['landing'] ?? null
        );
    }

    /**
     * @param array $raw
     * @return IntegrationConfigInterface
     * @throws \Exception
     */
    protected function buildConfig(array $raw): IntegrationConfigInterface
    {
        $platform = PlatformNameEnum::tryFrom((string)($raw['deviceReg'])) ?? null;
        $timeout = isset($raw['timeoutSeconds']) ? (int)$raw['timeoutSeconds'] : null;

        return MindBoxConfig::create(
            MindBoxOperationNameEnum::EDIT_USER,
            $platform,
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
        return new EditCustomerRequest($dto, $config);
    }
}