<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application\Database;

use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Application\Database;

class MariaDbBackoffice implements Database
{
    public function value(): array
    {
        return [
            'class' => 'yii\db\Connection',
            'dsn' =>
                sprintf(
                    'mysql:host=%s;port=%s;dbname=%s',
                    (new Env('UB_MARIADB_HOST_BACKOFFICE'))->value(),
                    (new Env('UB_MARIADB_PORT_BACKOFFICE'))->value(),
                    (new Env('UB_MARIADB_DATABASE_BACKOFFICE'))->value(),
                ),
            'username' => (new Env('UB_MARIADB_USER_BACKOFFICE'))->value(),
            'password' => (new Env('UB_MARIADB_PASSWORD_BACKOFFICE'))->value(),
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 3600,
            'schemaCache' => 'cache',
        ];
    }
}
