<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\RegisterCustomer\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

final class CustomFieldsDTO extends AbstractDTO
{
    public function __construct(
        public readonly ?string $personalAccountNumberForTerminal,
        public readonly bool    $promocoderestriction,
        public readonly int     $language,
        public readonly string  $partnerid,
        public readonly ?string $registrationLanding,
        public readonly bool    $sharesrestriction,
        public readonly bool    $PromoDepRestriction,
        public readonly bool    $PromoDostarRestriction,
        public readonly ?string $friendPromocode,
        public readonly string  $registrationDateAndTime,
        public readonly string  $registrationPlatform,
        public readonly ?int    $sourceOfRegistration,
        public readonly ?int    $registrationChannel,
        public readonly ?string $registrationSource,
        public readonly bool    $selfRestrictedList,
        public readonly bool    $chsiList,
        public readonly bool    $eRDList,
        public readonly bool    $afmList,
        public readonly bool    $statusBlock,
        public readonly bool    $statusBad,
        public readonly string  $cashbackStatus,
        public readonly bool    $verificationStatus,
        public readonly bool    $registrationIsFinished,
    ) {}

    protected function fields(): array
    {
        return [
            'personalAccountNumberForTerminal',
            'promocoderestriction',
            'language',
            'partnerid',
            'registrationLanding',
            'sharesrestriction',
            'PromoDepRestriction',
            'PromoDostarRestriction',
            'friendPromocode',
            'registrationDateAndTime',
            'registrationPlatform',
            'sourceOfRegistration',
            'registrationChannel',
            'registrationSource',
            'selfRestrictedList',
            'chsiList',
            'eRDList',
            'afmList',
            'statusBlock',
            'statusBad',
            'cashbackStatus',
            'verificationStatus',
            'registrationIsFinished',
        ];
    }
}
