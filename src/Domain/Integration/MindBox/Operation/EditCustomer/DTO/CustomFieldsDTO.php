<?php

namespace Integra\Domain\Integration\MindBox\Operation\EditCustomer\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class CustomFieldsDTO extends AbstractDTO
{
    public function __construct(
        public readonly ?string $personalAccountNumberForTerminal,
        public readonly string  $registrationDateAndTime,
        public readonly ?string $registrationPlatform,
        public readonly mixed   $sourceOfRegistration,
        public readonly bool    $registrationIsFinished,
        public readonly bool    $promocodeRestriction,
        public readonly int     $language,
        public readonly ?int    $partnerId,
        public readonly ?string $registrationLanding,
        public readonly bool    $promoDepRestriction,
        public readonly bool    $promoDostarRestriction,
        public readonly ?string $friendPromocode,
        public readonly ?float  $currentBalance,
        public readonly bool    $sharesRestriction,
        public readonly ?string $myTeam,
        public readonly ?float  $commonBetSumm,
        public readonly ?float  $eSportBetPercent,
        public readonly ?string $verificationDateAndTime,
        public readonly ?string $firstDepositDateAndTime,
        public readonly bool    $verificationStatus,
        public readonly bool    $isEmailConfirmed,
        public ?bool            $selfRestrictedList = null,
        public ?bool            $chsiList = null,
        public ?bool            $eRDList = null,
        public ?bool            $afmList = null,
        public ?bool            $statusBlock = null,
        public ?bool            $statusBad = null,
        public ?string          $cashbackStatus = null,
        public ?float           $footballBetPercent = null,
        public ?float           $tennisBetPercent = null,
        public ?float           $basketballBetPercent = null,
        public ?float           $hockeyBetPercent = null,
        public ?float           $tableTennisBetPercent = null,
        public ?float           $fightBetPercent = null,
        public ?float           $cyberBetPercent = null,
    )
    {
    }

    /**
     * @return string[]
     */
    protected function fields(): array
    {
        return [
            'personalAccountNumberForTerminal',
            'promocodeRestriction' => 'promocoderestriction',
            'language',
            'partnerId' => 'partnerid',
            'registrationLanding',
            'sharesRestriction' => 'sharesrestriction',
            'myTeam' => 'MyTeam',
            'commonBetSumm' => 'commonbetsumm',
            'eSportBetPercent',
            'verificationDateAndTime',
            'firstDepositDateAndTime',
            'verificationStatus',
            'isEmailConfirmed' => 'IsemailConfirmed',
            'selfRestrictedList',
            'chsiList',
            'eRDList',
            'afmList',
            'statusBlock',
            'statusBad',
            'cashbackStatus',
            'promoDepRestriction' => 'PromoDepRestriction',
            'promoDostarRestriction' => 'PromoDostarRestriction',
            'friendPromocode',
            'currentBalance' => 'currentbalance',
            'registrationDateAndTime',
            'registrationPlatform',
            'sourceOfRegistration',
            'registrationIsFinished',
            'footballBetPercent',
            'tennisBetPercent',
            'basketballBetPercent',
            'hockeyBetPercent',
            'tableTennisBetPercent',
            'fightBetPercent',
            'cyberBetPercent',
        ];
    }
}