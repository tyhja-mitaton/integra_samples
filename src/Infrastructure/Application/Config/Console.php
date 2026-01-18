<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application\Config;

use Integra\Controller\Console\AppTokenController;
use Integra\Infrastructure\Application\Config;
use Integra\Infrastructure\Application\Database;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Logging\Target\DevConsole;
use Integra\Infrastructure\Logging\Target\StdOut;
use Integra\Infrastructure\Queue\QueueDelayed;
use Yii;
use yii\console\controllers\MigrateController;
use yii\i18n\PhpMessageSource;
use yii\queue\amqp_interop\Queue;
use yii\redis\Cache;
use yii\redis\Connection;

class Console implements Config
{
    private Database $database;
    private Database $databaseUbet;
    private Database $databaseBackoffice;
    private Database $databasePromo;
    private Database $databaseFeed;

    public function __construct(Database $database, Database $databaseUbet, Database $databaseBackoffice, Database $databasePromo, Database $databaseFeed)
    {
        $this->database = $database;
        $this->databaseUbet = $databaseUbet;
        $this->databaseBackoffice = $databaseBackoffice;
        $this->databasePromo = $databasePromo;
        $this->databaseFeed = $databaseFeed;
    }

    public function value(): array
    {
        return [
            'id' => 'kz.ubet.nl.console',
            'basePath' => dirname(dirname(dirname(dirname(__DIR__)))),
            'language' => 'en',
            'bootstrap' => [
                'log',
                'queue_api',
                'queue_ws',
                'queue_message',
                'queue_payments',
                'queue_triggers',
                'queue_triggers_integra',
                'queue_triggers_affise',
                'queue_triggers_alanbase',
                'queue_triggers_mindbox',
                'queue_triggers_promo',
                'queue_triggers_adjust',
            ],
            'controllerMap' => [
                'migrate' => [
                    'class' => MigrateController::class,
                    'migrationPath' => [
                        __DIR__ . '/../../../../migrations',
                    ],
                    'migrationTable' => '_migration',
                ],
                'appToken' => [
                    'class' => AppTokenController::class,
                ],
            ],
            'components' => [
                'log' => [
                    'traceLevel' => YII_DEBUG ? 3 : 0,
                    'targets' => [
                        [
                            'class' => DevConsole::class,
                            'levels' => ['error', 'info', 'warning'],
                            'logVars' => [],
                            'categories' => ['application'],
                        ],
                        [
                            'class' => StdOut::class,
                            'levels' => ['trace'],
                            'logVars' => [],
                            'categories' => ['application'],
                        ],
                    ],
                ],
                'db' => $this->database->value(),
                'db_ubet' => $this->databaseUbet->value(),
                'db_backoffice' => $this->databaseBackoffice->value(),
                'db_promo' => $this->databasePromo->value(),
                'db_feed' => $this->databaseFeed->value(),
                'i18n' => [
                    'translations' => [
                        '*' => [
                            'class' => PhpMessageSource::class,
                            'sourceLanguage' => 'en',
                            'fileMap' => [
                                'Integra' => 'Integra.php',
                            ],
                        ],
                    ],
                ],
                'cache' => [
                    'class' => Cache::class,
                    'keyPrefix' => 'Integra',
                ],
                'redis' => [
                    'class' => Connection::class,
                    'hostname' => (new Env('UB_REDIS_HOST'))->value(),
                    'port' => (new Env('UB_REDIS_PORT'))->value(),
                    'database' => (new Env('UB_REDIS_INDEX'))->value(),
                    'password' => (new Env('UB_REDIS_PASSWORD'))->value(),
                    'username' => (new Env('UB_REDIS_USER'))->value(),
                ],
                'queue_api' => [
                    'class' => Queue::class,
                    'driver' => Queue::ENQUEUE_AMQP_EXT,
                    'host' => (new Env('UB_RABBITMQ_HOSTNAME'))->value(),
                    'port' => (new Env('UB_RABBITMQ_NODE_PORT'))->value(),
                    'user' => (new Env('UB_RABBITMQ_USER'))->value(),
                    'password' => (new Env('UB_RABBITMQ_PASSWORD'))->value(),
                    'queueName' => 'queue_api',
                    'exchangeName' => 'exchange_api',
                    'on afterError' => function (\yii\queue\ExecEvent $event) {
                        Yii::error('queue->console->afterError ' . json_encode($event->error->getMessage()));
                    },
                ],
                'queue_ws' => [
                    'class' => Queue::class,
                    'driver' => Queue::ENQUEUE_AMQP_EXT,
                    'host' => (new Env('UB_RABBITMQ_HOSTNAME'))->value(),
                    'port' => (new Env('UB_RABBITMQ_NODE_PORT'))->value(),
                    'user' => (new Env('UB_RABBITMQ_USER'))->value(),
                    'password' => (new Env('UB_RABBITMQ_PASSWORD'))->value(),
                    'queueName' => 'queue_ws',
                    'exchangeName' => 'exchange_ws',
                    'on afterError' => function (\yii\queue\ExecEvent $event) {
                        Yii::error('queue->console->afterError ' . json_encode($event->error->getMessage()));
                    },
                ],
                'queue_message' => [
                    'class' => Queue::class,
                    'driver' => Queue::ENQUEUE_AMQP_EXT,
                    'host' => (new Env('UB_RABBITMQ_HOSTNAME'))->value(),
                    'port' => (new Env('UB_RABBITMQ_NODE_PORT'))->value(),
                    'user' => (new Env('UB_RABBITMQ_USER'))->value(),
                    'password' => (new Env('UB_RABBITMQ_PASSWORD'))->value(),
                    'queueName' => 'queue_message',
                    'exchangeName' => 'exchange_message',
                    'on afterError' => function (\yii\queue\ExecEvent $event) {
                        Yii::error('queue->console->afterError ' . json_encode($event->error->getMessage()));
                    },
                ],
                'queue_payments' => [
                    'class' => Queue::class,
                    'driver' => Queue::ENQUEUE_AMQP_EXT,
                    'host' => (new Env('UB_RABBITMQ_HOSTNAME'))->value(),
                    'port' => (new Env('UB_RABBITMQ_NODE_PORT'))->value(),
                    'user' => (new Env('UB_RABBITMQ_USER'))->value(),
                    'password' => (new Env('UB_RABBITMQ_PASSWORD'))->value(),
                    'queueName' => 'queue_payments',
                    'exchangeName' => 'exchange_payments',
                    'on afterError' => function (\yii\queue\ExecEvent $event) {
                        Yii::error('queue->console->afterError ' . json_encode($event->error->getMessage()));
                    },
                ],
                'queue_triggers' => [
                    'class' => QueueDelayed::class,
                    'driver' => QueueDelayed::ENQUEUE_AMQP_EXT,
                    'host' => (new Env('UB_RABBITMQ_HOSTNAME'))->value(),
                    'port' => (new Env('UB_RABBITMQ_NODE_PORT'))->value(),
                    'user' => (new Env('UB_RABBITMQ_USER'))->value(),
                    'password' => (new Env('UB_RABBITMQ_PASSWORD'))->value(),
                    'queueName' => 'queue_triggers',
                    'exchangeName' => 'exchange_triggers',
                    'exchangeType' => 'x-delayed-message',
                    'on afterError' => function (\yii\queue\ExecEvent $event) {
                        Yii::error('queue->console->afterError ' . json_encode($event->error->getMessage()));
                    },
                ],
                'queue_triggers_integra' => [
                    'class' => QueueDelayed::class,
                    'driver' => QueueDelayed::ENQUEUE_AMQP_EXT,
                    'host' => (new Env('UB_RABBITMQ_HOSTNAME'))->value(),
                    'port' => (new Env('UB_RABBITMQ_NODE_PORT'))->value(),
                    'user' => (new Env('UB_RABBITMQ_USER'))->value(),
                    'password' => (new Env('UB_RABBITMQ_PASSWORD'))->value(),
                    'queueName' => 'queue_triggers_integra',
                    'exchangeName' => 'exchange_triggers_integra',
                    'exchangeType' => 'x-delayed-message',
                    'on afterError' => function (\yii\queue\ExecEvent $event) {
                        Yii::error('queue->console->afterError ' . json_encode($event->error->getMessage()));
                    },
                ],
                'queue_triggers_affise' => [
                    'class' => QueueDelayed::class,
                    'driver' => QueueDelayed::ENQUEUE_AMQP_EXT,
                    'host' => (new Env('UB_RABBITMQ_HOSTNAME'))->value(),
                    'port' => (new Env('UB_RABBITMQ_NODE_PORT'))->value(),
                    'user' => (new Env('UB_RABBITMQ_USER'))->value(),
                    'password' => (new Env('UB_RABBITMQ_PASSWORD'))->value(),
                    'queueName' => 'queue_triggers_affise',
                    'exchangeName' => 'exchange_triggers_affise',
                    'exchangeType' => 'x-delayed-message',
                ],
                'queue_triggers_alanbase' => [
                    'class' => QueueDelayed::class,
                    'driver' => QueueDelayed::ENQUEUE_AMQP_EXT,
                    'host' => (new Env('UB_RABBITMQ_HOSTNAME'))->value(),
                    'port' => (new Env('UB_RABBITMQ_NODE_PORT'))->value(),
                    'user' => (new Env('UB_RABBITMQ_USER'))->value(),
                    'password' => (new Env('UB_RABBITMQ_PASSWORD'))->value(),
                    'queueName' => 'queue_triggers_alanbase',
                    'exchangeName' => 'exchange_triggers_alanbase',
                    'exchangeType' => 'x-delayed-message',
                ],
                'queue_triggers_mindbox' => [
                    'class' => QueueDelayed::class,
                    'driver' => QueueDelayed::ENQUEUE_AMQP_EXT,
                    'host' => (new Env('UB_RABBITMQ_HOSTNAME'))->value(),
                    'port' => (new Env('UB_RABBITMQ_NODE_PORT'))->value(),
                    'user' => (new Env('UB_RABBITMQ_USER'))->value(),
                    'password' => (new Env('UB_RABBITMQ_PASSWORD'))->value(),
                    'queueName' => 'queue_triggers_mindbox',
                    'exchangeName' => 'exchange_triggers_mindbox',
                    'exchangeType' => 'x-delayed-message',
                ],
                'queue_triggers_promo' => [
                    'class' => QueueDelayed::class,
                    'driver' => QueueDelayed::ENQUEUE_AMQP_EXT,
                    'host' => (new Env('UB_RABBITMQ_HOSTNAME'))->value(),
                    'port' => (new Env('UB_RABBITMQ_NODE_PORT'))->value(),
                    'user' => (new Env('UB_RABBITMQ_USER'))->value(),
                    'password' => (new Env('UB_RABBITMQ_PASSWORD'))->value(),
                    'queueName' => 'queue_triggers_promo',
                    'exchangeName' => 'exchange_triggers_promo',
                    'exchangeType' => 'x-delayed-message',
                ],
                'queue_triggers_adjust' => [
                    'class' => QueueDelayed::class,
                    'driver' => QueueDelayed::ENQUEUE_AMQP_EXT,
                    'host' => (new Env('UB_RABBITMQ_HOSTNAME'))->value(),
                    'port' => (new Env('UB_RABBITMQ_NODE_PORT'))->value(),
                    'user' => (new Env('UB_RABBITMQ_USER'))->value(),
                    'password' => (new Env('UB_RABBITMQ_PASSWORD'))->value(),
                    'queueName' => 'queue_triggers_adjust',
                    'exchangeName' => 'exchange_triggers_adjust',
                    'exchangeType' => 'x-delayed-message',
                ],
            ],
        ];
    }
}
