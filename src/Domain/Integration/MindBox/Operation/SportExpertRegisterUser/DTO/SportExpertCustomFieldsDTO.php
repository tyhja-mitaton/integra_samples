<?php

declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\SportExpertRegisterUser\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

final class SportExpertCustomFieldsDTO extends AbstractDTO
{
    public function __construct(
        public readonly ?string $personalAccountNumberForTerminal,
        public readonly bool    $promocodeRestriction,
        public readonly int  $language,
        public readonly string  $partnerId,
        public readonly ?string $registrationLanding,
        public readonly bool    $sharesRestriction,
        public readonly bool    $promoDepRestriction,
        public readonly bool    $promoDostarRestriction,
        public readonly ?string $friendPromocode,
        public readonly string  $registrationDateAndTime,
        public readonly string  $registrationPlatform,
        public readonly mixed   $sourceOfRegistration,
        public readonly bool    $selfRestrictedList,
        public readonly bool    $chsiList,
        public readonly bool    $erdList,
        public readonly bool    $afmList,
        public readonly bool    $statusBlock,
        public readonly bool    $statusBad,
        public readonly string  $cashbackStatus,
        public readonly bool    $verificationStatus,
        public readonly bool    $registrationIsFinished,
    )
    {
    }

    protected function fields(): array
    {
        return [
            'personalAccountNumberForTerminal',
            'promocodeRestriction',
            'language',
            'partnerId',
            'registrationLanding',
            'sharesRestriction',
            'promoDepRestriction',
            'promoDostarRestriction',
            'friendPromocode',
            'registrationDateAndTime',
            'registrationPlatform',
            'sourceOfRegistration',
            'selfRestrictedList',
            'chsiList',
            'erdList',
            'afmList',
            'statusBlock',
            'statusBad',
            'cashbackStatus',
            'verificationStatus',
            'registrationIsFinished',
        ];
    }
}