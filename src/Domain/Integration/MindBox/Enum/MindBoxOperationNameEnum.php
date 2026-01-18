<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Enum;

use Integra\Domain\Enum\EnumInterface;

/**
 * Список операций MindBox.
 */
enum MindBoxOperationNameEnum: string implements EnumInterface
{
    /**
     * Событие регистрации пользователя.
     */
    case REGISTER_USER = 'RegisterCustomer';

    /**
     * Регистрация пользователя в "Национальный\Спорт-Эксперт"
     */
    case SPORT_EXPERT_REGISTER_USER = 'RegistraciyaKlienta';

    /**
     * Авторизация пользователя с web сайта.
     */
    case AUTH_FROM_WEB = 'AuthorizationOnWebSite';

    /**
     * Авторизация пользователя с android\ios приложения.
     */
    case AUTH_FROM_MOBILE = 'AuthorizationOnMobileApp';

    /**
     * Редактирование данных пользователя
     */
    case EDIT_USER = 'EditCustomer';

    /**
     * Создание депозита
     */
    case CREATE_DEPOSIT = 'CreateDeposit';

    /**
     * Создание ставки
     */
    case CREATE_BET = 'CreateBet';

    /**
     * Изменение ставки
     */
    case CHANGE_BET = 'ChangeBet';

    /**
     * Изменение статуса ставки
     */
    case CHANGE_BET_STATUS = 'ChangeBetStatus';

    /**
     * Обновление статуса депозита
     */
    case UPDATE_DEPOSIT_STATUS = 'UpdateDepositStatus';

    /**
     * Создание бонуса
     */
    case CREATE_BONUS = 'CreateBonus';

    /**
     * Обновление статуса бонуса
     */
    case UPDATE_BONUS_STATUS = 'UpdateBonusStatus';

    public function value(): string
    {
        return $this->value;
    }
}
