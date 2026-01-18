<?php

namespace Integra\Domain\Integration\MindBox\Operation\EditCustomer\Data;

use DateTime;
use DomainException;
use Integra\Domain\Integration\MindBox\Common\Data\SubscriptionFactory;
use Integra\Domain\Integration\MindBox\DTO\IdsDTO;
use Integra\Domain\Integration\MindBox\Enum\SportTypeEnum;
use Integra\Domain\Integration\MindBox\Operation\EditCustomer\DTO\CustomerDTO;
use Integra\Domain\Integration\MindBox\Operation\EditCustomer\DTO\CustomFieldsDTO;
use Integra\Domain\Integration\MindBox\Operation\EditCustomer\DTO\EditCustomerDTO;
use Integra\Domain\Services\User\BackofficePlayerMarksService;
use Integra\Domain\Services\User\BetInfoService;
use Integra\Domain\Services\User\BlacklistService;
use Integra\Domain\Services\User\CashbackService;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Promo\UserTeam;
use Integra\Models\Ubet\User;
use Integra\Models\Ubet\UserApproveHistory;
use Integra\Models\Ubet\UsersPaymentCode;
use Yii;
use yii\helpers\StringHelper;

final class EditCustomerDataBuilder
{

    public function __construct(
        private readonly BlacklistService             $blacklistService,
        private readonly CashbackService              $cashbackService,
        private readonly BackofficePlayerMarksService $playerMarkService,
        private readonly BetInfoService $betInfoService,
    )
    {
    }

    public function build(int $userId, ?string $landing): EditCustomerDTO
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

        $userTeam = UserTeam::findOne(['user_id' => $user->user_id]);

        $regDateTime = new DateTime($user->dttm_reg, new Asia());
        $regDateTime->setTimezone(new UTC());
        $regDateTimeUTC = $regDateTime->format('Y-m-d H:i:s');
        $executionDateTimeUtc = (new DateTime('now', new UTC()))->format('Y-m-d H:i:s');

        $verificationDateAndTime = null;
        if($user->is_approved) {
            if($user->dttm_approve != null) {
                $verificationDateAndTime = $user->dttm_approve;
            } else {
                $approve_history = UserApproveHistory::find()->where(['user_id' => $user->user_id])->orderBy('id DESC')->one();
                if ($approve_history && $approve_history->close_dttm != null) {
                    $verificationDateAndTime = $approve_history->close_dttm;
                }
            }
        }
        $verificationDateAndTime = $verificationDateAndTime != null ? (new DateTime($verificationDateAndTime, new Asia())) : null;
        $verificationDateAndTimeUTC = $verificationDateAndTime?->setTimezone(new UTC())->format('Y-m-d H:i:s');
        $firstDepositDateAndTime = $user->first_deposit_dttm != null ? (new DateTime($user->first_deposit_dttm, new Asia())) : null;
        $firstDepositDateAndTimeUTC = $firstDepositDateAndTime?->setTimezone(new UTC())->format('Y-m-d H:i:s');

        $iin = $user->personal?->iin ?? '';

        $customFields = new CustomFieldsDTO(
            personalAccountNumberForTerminal: UsersPaymentCode::findOne(['user_id' => $userId])?->code,
            registrationDateAndTime: $regDateTimeUTC,
            registrationPlatform: $user->device_reg,
            sourceOfRegistration: $user->promocode?->public_code,
            registrationIsFinished: true,
            promocodeRestriction: $user->promocode_restriction,
            language: $user->language_id,
            partnerId: $user->a_partner_id,
            registrationLanding: $landing,
            promoDepRestriction: $user->real_bonus_is_active,
            promoDostarRestriction: $user->invited_active,
            friendPromocode: $user->invited_code,
            currentBalance: $user->money_real,
            sharesRestriction: $this->playerMarkService->isSharesRestriction($userId),
            myTeam : $userTeam?->team?->name,
            commonBetSumm: $this->betInfoService->getCommonBetSum($userId),
            eSportBetPercent: $this->betInfoService->getESportPercent($userId),
            verificationDateAndTime: $verificationDateAndTimeUTC,
            firstDepositDateAndTime: $firstDepositDateAndTimeUTC,
            verificationStatus: $user->is_approved,
            isEmailConfirmed: $user->email_active,
            selfRestrictedList: $this->blacklistService->isSelfRestricted($iin),
            chsiList: $this->blacklistService->isCollectorRestricted($iin),
            eRDList: $this->blacklistService->isErdRestricted($iin),
            afmList: $this->blacklistService->isAfmRestricted($iin),
            statusBlock: $this->blacklistService->isBlockedStatus($user),
            statusBad: $this->playerMarkService->isProblem($userId),
            cashbackStatus: $this->cashbackService->getCashbackStatus($userId),
        );

        foreach (SportTypeEnum::cases() as $sportTypeAttribute) {
            $customFields->{$sportTypeAttribute->attributeName()} = $this->betInfoService->getBetPercent((int)$sportTypeAttribute->value(), $user->user_id);
        }

        $customer = new CustomerDTO(
            mobilePhone: $user->phone,
            email: $user->email,
            timeZone: 'Asia/Aqtau',
            ids: new IdsDTO((string)$user->user_id),
            customFields: $customFields,
            subscriptions: (new SubscriptionFactory())->forUser($user),
            birthDate: $user->personal?->birthday,
            sex: $user->personal?->sex,
            lastName: $user->personal?->surname,
            firstName: $user->personal?->firstname,
            middleName: $user->personal?->lastname,
        );


        return new EditCustomerDTO(
            executionDateTimeUtc: $executionDateTimeUtc,
            customer: $customer,
        );
    }
}