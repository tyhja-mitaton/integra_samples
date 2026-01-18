<?php

namespace Integra\Infrastructure\Application\Database;

use Integra\Infrastructure\Application\Database;
use Integra\Infrastructure\Environment\Env;

class MariaDbFeed implements Database
{

    public function value(): array
    {
        return [
            'class' => 'yii\db\Connection',
            'dsn' =>
                sprintf(
                    'mysql:host=%s;port=%s;dbname=%s',
                    (new Env('UB_MARIADB_HOST_FEED'))->value(),
                    (new Env('UB_MARIADB_PORT_FEED'))->value(),
                    (new Env('UB_MARIADB_DATABASE_FEED'))->value(),
                ),
            'username' => (new Env('UB_MARIADB_USER_FEED'))->value(),
            'password' => (new Env('UB_MARIADB_PASSWORD_FEED'))->value(),
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 3600,
            'schemaCache' => 'cache',
        ];
    }
}