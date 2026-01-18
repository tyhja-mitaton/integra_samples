<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application\Database;

use Integra\Infrastructure\Application\Database;
use Integra\Infrastructure\Environment\Env;

class Mysql implements Database
{
    public function value(): array
    {
        return [
            'class' => 'yii\db\Connection',
            'dsn' =>
                sprintf(
                    'mysql:host=%s;port=%s;dbname=%s',
                    (new Env('MYSQL_HOST'))->value(),
                    (new Env('MYSQL_PORT'))->value(),
                    (new Env('MTSQL_DATABASE'))->value(),
                ),
            'username' => (new Env('MYSQL_USER'))->value(),
            'password' => (new Env('MYSQL_PASSWORD'))->value(),
            'charset' => 'utf8mb4',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 3600,
            'schemaCache' => 'cache',
        ];
    }
}
