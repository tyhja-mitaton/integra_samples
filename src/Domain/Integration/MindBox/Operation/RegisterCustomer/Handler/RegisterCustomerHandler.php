<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\RegisterCustomer\Handler;

use Exception;
use Integra\Domain\Enum\PlatformNameEnum;
use Integra\Infrastructure\Generic\Result;
use Integra\Infrastructure\Http\Transport;
use Integra\Domain\Services\User\CashbackService;
use Integra\Domain\Services\User\BlacklistService;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\MindBox\MindBoxConfig;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Services\User\BackofficePlayerMarksService;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\MindBox\Enum\MindBoxOperationNameEnum;
use Integra\Domain\Integration\MindBox\Operation\AbstractMindBoxOperationHandler;
use Integra\Domain\Integration\MindBox\Operation\RegisterCustomer\Request\RegisterCustomerRequest;
use Integra\Domain\Integration\MindBox\Operation\RegisterCustomer\Data\RegisterCustomerDataBuilder;

final class RegisterCustomerHandler extends AbstractMindBoxOperationHandler
{
    private RegisterCustomerDataBuilder $builder;

    public function __construct(
        Transport                    $transport,
        BlacklistService             $blacklistService,
        CashbackService              $cashbackService,
        BackofficePlayerMarksService $playerMarksService,
    )
    {
        parent::__construct($transport);

        $this->builder = new RegisterCustomerDataBuilder(
            $blacklistService,
            $cashbackService,
            $playerMarksService,
        );
    }

    /**
     * @param array $raw
     * @return DTOInterface
     * @throws Exception
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            userId: (int)$raw['userId'],
            landing: $raw['landing'] ?? null,
        );
    }

    /**
     * @param array $raw
     * @return IntegrationConfigInterface
     * @throws Exception
     */
    protected function buildConfig(array $raw): IntegrationConfigInterface
    {
        return MindBoxConfig::create(
            MindBoxOperationNameEnum::REGISTER_USER,
            PlatformNameEnum::tryFrom((string)($raw['deviceReg'])) ?? null,
            isset($raw['timeoutSeconds']) ? (int)$raw['timeoutSeconds'] : null,
        );
    }

    /**
     * @param DTOInterface $dto
     * @param IntegrationConfigInterface $config
     * @return RequestInterface
     */
    protected function buildRequest(
        DTOInterface               $dto,
        IntegrationConfigInterface $config
    ): RequestInterface
    {
        return new RegisterCustomerRequest($dto, $config);
    }

    /**
     * todo удалить комментарий. Можно убрать, но оставляю метод явно, чтоб было видно, что можно переопределить
     * @param Result $result
     * @param array $raw
     * @return Result
     */
    protected function returnResult(Result $result, array $raw): Result
    {
        return parent::returnResult($result, $raw);
    }
}
