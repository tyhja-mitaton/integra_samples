<?php

namespace Integra\Domain\Integration\MindBox\Operation\UpdateDepositStatus\Data;

use Integra\Domain\Integration\MindBox\Enum\DepositStatusEnum;
use Integra\Domain\Integration\MindBox\Operation\UpdateDepositStatus\DTO\IdsDTO;
use Integra\Domain\Integration\MindBox\Operation\UpdateDepositStatus\DTO\UpdateDepositStatusDTO;
use Integra\Domain\Integration\MindBox\Operation\UpdateDepositStatus\DTO\OrderDTO;
use yii\helpers\StringHelper;
use Yii;
use DomainException;

final class UpdateDepositStatusDataBuilder
{
    public function build(int $paymentId, int $statusId): UpdateDepositStatusDTO
    {
        $orderLinesStatus = DepositStatusEnum::tryFrom($statusId)?->mindboxName();
        if(is_null($orderLinesStatus)) {
            $message = sprintf(
                '[%s] Status ID not found:: %s',
                StringHelper::basename(self::class),
                $statusId
            );
            Yii::error($message, __METHOD__);
            throw new DomainException($message);
        }
        return new UpdateDepositStatusDTO(
            orderLinesStatus: $orderLinesStatus,
            order: new OrderDTO(
                ids: new IdsDTO(
                    depositId: $paymentId
                )
            )
        );
    }
}