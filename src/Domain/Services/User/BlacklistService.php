<?php

declare(strict_types=1);

namespace Integra\Domain\Services\User;

use Yii;
use yii\db\Exception;
use yii\db\Connection;
use Integra\Models\Ubet\User;
use InvalidArgumentException;

/**
 * Сервис для проверки IIN в различных черных списках.
 */
class BlacklistService
{
    private Connection $db;

    private const SELFLOCK_LIST = 'iin_selflockList';
    private const COLLECTORS_LIST = 'iin_collectorsList';
    private const AFM_LIST = 'iin_afmList';
    private const ERD_LIST = 'iin_erdList';
    private const BLACKLIST_TABLES_LIST = [
        self::SELFLOCK_LIST,
        self::COLLECTORS_LIST,
        self::AFM_LIST,
        self::ERD_LIST,
    ];

    public function __construct()
    {
        $this->db = Yii::$app->db_ubet;
    }

    /**
     * Проверяет, есть ли IIN в указанной таблице чёрного списка.
     * todo переделать на использование моделей а не raw
     * @param string $iin
     * @param string $tableName
     * @return bool
     * @throws Exception
     */
    public function isBlacklisted(string $iin, string $tableName): bool
    {
        if (!in_array($tableName, self::BLACKLIST_TABLES_LIST, true)) {
            throw new InvalidArgumentException("Invalid blacklist table: {$tableName}");
        }

        $today = (new \DateTimeImmutable())->format('Y-m-d');
        $sql = "SELECT EXISTS(
            SELECT 1 FROM `{$tableName}`
            WHERE iin = :iin
              AND (dttm_till IS NULL OR dttm_till >= :date)
        )";

        return (bool)$this->db
            ->createCommand($sql, [':iin' => $iin, ':date' => $today])
            ->queryScalar();
    }

    /**
     * Проверяет, есть ли IIN в списке самоограниченных.
     * @param string $iin
     * @return bool
     * @throws Exception
     */
    public function isSelfRestricted(string $iin): bool
    {
        return $this->isBlacklisted($iin, self::SELFLOCK_LIST);
    }

    /**
     * Проверяет, есть ли IIN в списке частных судебных исполнителей.
     * @param string $iin
     * @return bool
     * @throws Exception
     */
    public function isCollectorRestricted(string $iin): bool
    {
        return $this->isBlacklisted($iin, self::COLLECTORS_LIST);
    }

    /**
     * Проверяет, есть ли IIN в списке агентства финансового мониторинга.
     * @param string $iin
     * @return bool
     * @throws Exception
     */
    public function isAfmRestricted(string $iin): bool
    {
        return $this->isBlacklisted($iin, self::AFM_LIST);
    }

    /**
     * Проверяет, есть ли IIN в единый реестр должников.
     * @param string $iin
     * @return bool
     * @throws Exception
     */
    public function isErdRestricted(string $iin): bool
    {
        return $this->isBlacklisted($iin, self::ERD_LIST);
    }

    /**
     * Проверяет, заблокирован ли пользователь.
     * @param User $user
     * @return bool
     */
    public function isBlockedStatus(User $user): bool
    {
        return $user->is_blocked == 1 && (!$user->blocked_till || $user->blocked_till >= date('Y-m-d'));
    }
}