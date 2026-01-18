<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\RegisterCustomer\Data;

use Yii;
use DateTime;
use Exception;
use DomainException;
use yii\helpers\StringHelper;
use Integra\Models\Ubet\User;
use Integra\Models\Ubet\Channel;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Models\Ubet\UsersPaymentCode;
use Integra\Domain\Services\User\CashbackService;
use Integra\Domain\Services\User\BlacklistService;
use Integra\Domain\Integration\MindBox\DTO\IdsDTO;
use Integra\Domain\Services\User\BackofficePlayerMarksService;
use Integra\Domain\Integration\MindBox\Common\Data\SubscriptionFactory;
use Integra\Domain\Integration\MindBox\Operation\RegisterCustomer\DTO\CustomerDTO;
use Integra\Domain\Integration\MindBox\Operation\RegisterCustomer\DTO\CustomFieldsDTO;
use Integra\Domain\Integration\MindBox\Operation\RegisterCustomer\DTO\RegisterCustomerDTO;

final class RegisterCustomerDataBuilder
{
    public function __construct(
        private readonly BlacklistService             $blacklistService,
        private readonly CashbackService              $cashbackService,
        private readonly BackofficePlayerMarksService $playerMarksService,
    )
    {
    }

    /**
     * @param int $userId
     * @param string|null $landing
     * @return RegisterCustomerDTO
     * @throws Exception
     */
    public function build(int $userId, ?string $landing = null): RegisterCustomerDTO
    {
        $user = User::findOne($userId);

        if (empty($user)) {
            $message = sprintf(
                '[%s] User not found:: %s',
                StringHelper::basename(self::class),
                $userId
            );
            Yii::error($message, __METHOD__);
            throw new DomainException($message);
        }

        $registrationDateTime = new DateTime($user->dttm_reg, new Asia());
        $registrationDateTimeUtc = $registrationDateTime->setTimezone(new UTC());
        $executionDateTimeUtc = $registrationDateTimeUtc->format('Y-m-d H:i:s');

        $iin = $user->personal?->iin ?? '';

        if ($user->channel_id !== null) {
            $channel = Channel::findOne($user->channel_id);
            $regChannel = $user->channel_id;
            $regSource = $channel?->name;
        } else {
            $regChannel = null;
            $regSource = null;
        }

        $customFields = new CustomFieldsDTO(
            personalAccountNumberForTerminal: UsersPaymentCode::findOne(['user_id' => $userId])?->code,
            promocoderestriction: $user->promocode_restriction,
            language: (int)$user->language_id,
            partnerid: (string)$user->a_partner_id,
            registrationLanding: $landing,
            sharesrestriction: $this->playerMarksService->isSharesRestriction($userId),
            PromoDepRestriction: $user->real_bonus_is_active,
            PromoDostarRestriction: $user->invited_active,
            friendPromocode: $user->friend?->promocode?->public_code,
            registrationDateAndTime: $registrationDateTime->format('Y-m-d H:i:s'),
            registrationPlatform: $user->device_reg,
            sourceOfRegistration: $user->promocode_id,
            registrationChannel: $regChannel,
            registrationSource: $regSource,
            selfRestrictedList: $this->blacklistService->isSelfRestricted($iin),
            chsiList: $this->blacklistService->isCollectorRestricted($iin),
            eRDList: $this->blacklistService->isErdRestricted($iin),
            afmList: $this->blacklistService->isAfmRestricted($iin),
            statusBlock: $this->blacklistService->isBlockedStatus($user),
            statusBad: $this->playerMarksService->isProblem($userId),
            cashbackStatus: $this->cashbackService->getCashbackStatus($userId),
            verificationStatus: $user->is_approved,
            registrationIsFinished: true,
        );

        $customer = new CustomerDTO(
            mobilePhone: $user->phone,
            ids: new IdsDTO((string)$user->user_id),
            customFields: $customFields,
            subscriptions: (new SubscriptionFactory())->forUser($user),
        );

        return new RegisterCustomerDTO(
            executionDateTimeUtc: $executionDateTimeUtc,
            customer: $customer,
        );
    }
}
