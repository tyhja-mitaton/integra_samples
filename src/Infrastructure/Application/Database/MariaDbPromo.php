<?php

namespace Integra\Infrastructure\Application\Database;

use Integra\Infrastructure\Application\Database;
use Integra\Infrastructure\Environment\Env;

class MariaDbPromo implements Database
{
    public function value(): array
    {
        return [
            'class' => 'yii\db\Connection',
            'dsn' =>
                sprintf(
                    'mysql:host=%s;port=%s;dbname=%s',
                    (new Env('UB_MARIADB_HOST_PROMO'))->value(),
                    (new Env('UB_MARIADB_PORT_PROMO'))->value(),
                    (new Env('UB_MARIADB_DATABASE_PROMO'))->value(),
                ),
            'username' => (new Env('UB_MARIADB_USER_PROMO'))->value(),
            'password' => (new Env('UB_MARIADB_PASSWORD_PROMO'))->value(),
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 3600,
            'schemaCache' => 'cache',
        ];
    }
}