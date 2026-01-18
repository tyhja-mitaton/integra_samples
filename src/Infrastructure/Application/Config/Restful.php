<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application\Config;

use Integra\Infrastructure\Http\Event\BeforeSend;
use Integra\Infrastructure\Application\Config;
use Integra\Infrastructure\Identity\Config\JWTConfig;
use Integra\Infrastructure\Logging\Target\DevConsole;
use Integra\Infrastructure\Logging\Target\StdOut;
use Integra\Infrastructure\Application\Database;
use Integra\Infrastructure\Application\Language;
use Integra\Infrastructure\Application\Routes;
use Integra\Infrastructure\Application\System;
use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Queue\QueueDelayed;
use yii\i18n\PhpMessageSource;
use yii\queue\amqp_interop\Queue;
use yii\redis\Cache;
use yii\redis\Connection;
use yii\web\IdentityInterface;
use yii\web\JsonParser;
use yii\web\JsonResponseFormatter;
use yii\web\Response;

class Restful implements Config
{
    private System $system;
    private Database $database;
    private Database $databaseUbet;
    private Database $databaseBackoffice;

    private Database $databasePromo;
    private Language $language;
    private Routes $routes;
    private IdentityInterface $identityClass;

    /**
     * @param System $system
     * @param Database $database
     * @param Database $databaseUbet
     * @param Database $databaseBackoffice
     * @param Language $language
     * @param Routes $routes
     * @param IdentityInterface $identityClass
     */
    public function __construct(
        System $system,
        Database $database,
        Database $databaseUbet,
        Language $language,
        Routes $routes,
        IdentityInterface $identityClass
    )
    {
        $this->database = $database;
        $this->databaseUbet = $databaseUbet;
        $this->language = $language;
        $this->system = $system;
        $this->routes = $routes;
        $this->identityClass = $identityClass;
    }

    public function value(): array
    {
        return [
            'id' => 'ru.devices',
            'basePath' => dirname(dirname(dirname(dirname(__DIR__)))),
            'language' => $this->language->value(),
            'controllerNamespace' => $this->system->controllerNamespace(),
            'bootstrap' => ['log'],
            'modules' => [],
            'components' => [
                'request' => [
                    'enableCsrfValidation' => false,
                    'enableCsrfCookie' => false,
                    'enableCookieValidation' => false,
                    'parsers' => [
                        'application/json' => JsonParser::class,
                    ],
                ],
                'log' => [
                    'traceLevel' => 0,
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
                'urlManager' => [
                    'enablePrettyUrl' => true,
                    'enableStrictParsing' => true,
                    'showScriptName' => false,
                    'rules' => $this->routes->value(),
                ],
                'response' => [
                    'class' => Response::class,
                    'on beforeSend' => new BeforeSend(),
                    'format' => Response::FORMAT_JSON,
                    'formatters' => [
                        Response::FORMAT_XML => [ // enforce JSON output for browser
                            'class' => JsonResponseFormatter::class,
                            'prettyPrint' => YII_DEBUG,
                            'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                        ],
                    ],
                ],
                'db' => $this->database->value(),
                'db_ubet' => $this->databaseUbet->value(),
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
                'user' => [
                    'identityClass' => $this->identityClass,
                    'enableSession' => 'false',
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
                'jwt' => (new JWTConfig())->value(),
            ],
        ];
    }
}
