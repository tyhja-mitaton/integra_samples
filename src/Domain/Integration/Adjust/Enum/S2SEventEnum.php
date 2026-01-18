<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Adjust\Enum;

use Integra\Domain\Enum\EnumInterface;

/**
 * S2S-события Adjust.
 */
enum S2SEventEnum: string implements EnumInterface
{
    /** Регистрация пользователя */
    case REGISTRATION_ANDROID = 'wljcza';
    case REGISTRATION_IOS = '8ht37z';

    /** Верификация пользователя */
    case VERIFICATION_ANDROID = 'y061a9';
    case VERIFICATION_IOS = 'jzxkef';

    /** Первый депозит пользователя */
    case FTD_ANDROID = 'hptb9q';
    case FTD_IOS = '9io7n7';

    /** Повторный депозит пользователя */
    case RD_ANDROID = 'pns1zd';
    case RD_IOS = '3o1bte';

    /** Ставка пользователя */
    case CREDIT_BET_ANDROID = '3vpuls';
    case CREDIT_BET_IOS = 'f8p9o3';

    /** Расчет ставки пользователя */
    case DEBIT_BY_BATCH_ANDROID = 'bflcf8';
    case DEBIT_BY_BATCH_IOS = '2xdh6s';


    public function value(): string
    {
        return $this->value;
    }
}