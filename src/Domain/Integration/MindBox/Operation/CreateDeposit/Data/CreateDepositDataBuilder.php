<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateDeposit\Data;

use Integra\Domain\Integration\MindBox\Enum\PaymentTypeEnum;
use Integra\Domain\Integration\MindBox\Operation\CreateDeposit\DTO\CustomFieldsDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateDeposit\DTO\IdsDTO as IdsDTOOrder;
use Integra\Domain\Integration\MindBox\DTO\IdsDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateDeposit\DTO\CreateDepositDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateDeposit\DTO\CustomerDTO;
use Integra\Domain\Integration\MindBox\Operation\CreateDeposit\DTO\IdsDTOProduct;
use Integra\Domain\Integration\MindBox\Operation\CreateDeposit\DTO\OrderDTO;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Ubet\Payment;
use DateTime;
use Integra\Domain\Integration\MindBox\Operation\CreateDeposit\DTO\LineDTO;
use Integra\Models\Ubet\PaymentSystem;
use Integra\Domain\Integration\MindBox\Operation\CreateDeposit\DTO\ProductDTO;

final class CreateDepositDataBuilder
{
    const ORDER_TYPE = 'Deposit';

    public function build(int $paymentId): CreateDepositDTO
    {
        $payment = Payment::findOne($paymentId);

        $paymentBeginDateTime = $payment->dttm_begin != null ? (new DateTime($payment->dttm_begin, new Asia())) : null;
        if (!empty($paymentBeginDateTime)){
            $paymentBeginDateTime->setTimezone(new UTC());
            $paymentBeginDateTimeUTC = $paymentBeginDateTime->format('Y-m-d H:i:s');
        } else {
            $paymentBeginDateTimeUTC = null;
        }
        $system = PaymentSystem::findOne($payment->system_id);

        $customer = new CustomerDTO(
            ids: new IdsDTO($payment->user_id)
        );
        $customFields = new CustomFieldsDTO(
            eventPlatform: null,
            eventDateAndTime: $paymentBeginDateTimeUTC,
            operationType: PaymentTypeEnum::tryFrom($payment->type)->operationType(),
            orderType: self::ORDER_TYPE,
            depositAmount: $payment->amount,
            replenishmentMethod: $system?->name,
        );
        $order = new OrderDTO(
            ids: new IdsDTOOrder($payment->pay_id),
            customFields: $customFields,
            lines: [
                new LineDTO(
                    basePricePerItem: $payment->type == 'IN' ? $payment->amount :0,
                    quantity: 1,
                    product: new ProductDTO(
                        ids: new IdsDTOProduct(ubet: 'deposit'),
                    )
                )
            ]
        );

        return new CreateDepositDTO(
            executionDateTimeUtc: $paymentBeginDateTimeUTC,
            customer: $customer,
            order: $order
        );
    }
}