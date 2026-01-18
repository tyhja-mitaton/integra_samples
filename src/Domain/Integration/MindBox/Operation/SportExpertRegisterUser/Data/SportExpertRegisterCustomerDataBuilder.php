<?php

declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\SportExpertRegisterUser\Data;

use Yii;
use DateTime;
use Exception;
use DomainException;
use Integra\Models\Ubet\User;
use Integra\Models\Ubet\UsersPersonal;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Models\Ubet\UsersPaymentCode;
use Integra\Domain\Services\User\CashbackService;
use Integra\Domain\Services\User\BlacklistService;
use Integra\Domain\Integration\MindBox\DTO\IdsDTO;
use Integra\Domain\Services\User\BackofficePlayerMarksService;
use Integra\Domain\Integration\MindBox\Common\Data\SubscriptionFactory;
use Integra\Domain\Integration\MindBox\Operation\SportExpertRegisterUser\DTO\SportExpertCustomerDTO;
use Integra\Domain\Integration\MindBox\Operation\SportExpertRegisterUser\DTO\SportExpertCustomFieldsDTO;
use Integra\Domain\Integration\MindBox\Operation\SportExpertRegisterUser\DTO\SportExpertRegisterCustomerDTO;

final class SportExpertRegisterCustomerDataBuilder
{
    public function __construct(
        private readonly BlacklistService             $blacklistService,
        private readonly CashbackService              $cashbackService,
        private readonly BackofficePlayerMarksService $playerMarkService,
    )
    {
    }

    /**
     * @param int $userId
     * @param string|null $landing
     * @return SportExpertRegisterCustomerDTO
     * @throws Exception
     */
    public function build(int $userId, ?string $landing): SportExpertRegisterCustomerDTO
    {
        $user = User::findOne(['user_id' => $userId]);
        if (!$user) {
            $msg = sprintf('[%s] User not found: %s', self::class, $userId);
            Yii::error($msg, __METHOD__);
            throw new DomainException($msg);
        }

        $registrationDateTime = new DateTime($user->dttm_reg, new Asia());
        $registrationDateTimeUtc = $registrationDateTime->setTimezone(new UTC());
        $executionDateTimeUtc = $registrationDateTimeUtc->format('Y-m-d H:i:s');

        $iin = $user->personal?->iin ?? '';

        $custom = new SportExpertCustomFieldsDTO(
            personalAccountNumberForTerminal: UsersPaymentCode::findOne(['user_id' => $userId])?->code,
            promocodeRestriction: $user->promocode_restriction,
            language: (int)$user->language_id,
            partnerId: (string)$user->a_partner_id,
            registrationLanding: $landing,
            sharesRestriction: $this->playerMarkService->isSharesRestriction($userId),
            promoDepRestriction: $user->real_bonus_is_active,
            promoDostarRestriction: $user->invited_active,
            friendPromocode: (string)$user->friend?->promocode?->public_code,
            registrationDateAndTime: $registrationDateTime->format('Y-m-d H:i:s'),
            registrationPlatform: $user->device_reg,
            sourceOfRegistration: $user->promocode?->public_code ?? '',
            selfRestrictedList: $this->blacklistService->isSelfRestricted($iin),
            chsiList: $this->blacklistService->isCollectorRestricted($iin),
            erdList: $this->blacklistService->isErdRestricted($iin),
            afmList: $this->blacklistService->isAfmRestricted($iin),
            statusBlock: $this->blacklistService->isBlockedStatus($user),
            statusBad: $this->playerMarkService->isProblem($userId),
            cashbackStatus: $this->cashbackService->getCashbackStatus($userId),
            verificationStatus: $user->is_approved,
            registrationIsFinished: true
        );

        $customer = new SportExpertCustomerDTO(
            mobilePhone: $user->phone,
            ids: new IdsDTO((string)$user->user_id),
            customFields: $custom,
            subscriptions: (new SubscriptionFactory())->forUser($user),
        );

        return new SportExpertRegisterCustomerDTO(
            executionDateTimeUtc: $executionDateTimeUtc,
            customer: $customer,
        );
    }
}