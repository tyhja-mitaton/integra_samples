<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application\Database;

use Integra\Infrastructure\Application\Database;
use Integra\Infrastructure\Environment\Env;

class MariaDb implements Database
{
    public function value(): array
    {
        return [
            'class' => 'yii\db\Connection',
            'dsn' =>
                sprintf(
                    'mysql:host=%s;port=%s;dbname=%s',
                    (new Env('UB_MARIADB_HOST_INTEGRA'))->value(),
                    (new Env('UB_MARIADB_PORT_INTEGRA'))->value(),
                    (new Env('UB_MARIADB_DATABASE_INTEGRA'))->value(),
                ),
            'username' => (new Env('UB_MARIADB_USER_INTEGRA'))->value(),
            'password' => (new Env('UB_MARIADB_PASSWORD_INTEGRA'))->value(),
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 3600,
            'schemaCache' => 'cache',
        ];
    }
}
