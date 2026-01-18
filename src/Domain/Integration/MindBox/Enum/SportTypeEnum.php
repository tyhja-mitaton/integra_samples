<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

/**
 * enum value: id вида спорта в бд
 */
enum SportTypeEnum: string implements EnumInterface
{
    case FOOTBALL_BET = '1';
    case TENNIS_BET = '3';
    case BASKETBALL_BET = '4';
    case HOCKEY_BET = '10';
    case TABLE_TENNIS_BET = '25';
    case FIGHT_BET = '46';
    case CYBER_BET = '53';

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * название узла в MindBox для каждого вида спорта
     *
     * @return string
     */
    public function attributeName(): string
    {
        return match ($this) {
            self::FOOTBALL_BET => 'footballBetPercent',
            self::TENNIS_BET => 'tennisBetPercent',
            self::BASKETBALL_BET => 'basketballBetPercent',
            self::HOCKEY_BET => 'hockeyBetPercent',
            self::TABLE_TENNIS_BET => 'tableTennisBetPercent',
            self::FIGHT_BET => 'fightBetPercent',
            self::CYBER_BET => 'cyberBetPercent',
        };
    }
}
