<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application\Config;

use Integra\Infrastructure\Application\Config;
use Integra\Infrastructure\Logging\Target\DevConsole;
use Integra\Infrastructure\Logging\Target\StdOut;
use Integra\Infrastructure\Application\Database;
use Integra\Infrastructure\Application\Language;
use Integra\Infrastructure\Application\Routes;
use Integra\Infrastructure\Application\System;
use yii\i18n\PhpMessageSource;
use yii\web\HtmlResponseFormatter;
use yii\web\JsonParser;
use yii\web\Response;
use yii\web\User;

class Html implements Config
{
    private System $system;
    private Database $database;
    private Language $language;
    private Routes $routes;

    public function __construct(System $system, Database $database, Language $language, Routes $routes)
    {
        $this->database = $database;
        $this->language = $language;
        $this->system = $system;
        $this->routes = $routes;
    }

    public function value(): array
    {
        return [
            'id' => 'ru.Integra.site',
            'basePath' => dirname(dirname(dirname(dirname(__DIR__)))),
            'language' => $this->language->value(),
            'controllerNamespace' => $this->system->controllerNamespace(),
            'bootstrap' => ['log'],
            'modules' => [],
            'components' => [
                'request' => [
                    'enableCookieValidation' => true,
                    'parsers' => [
                        'application/json' => JsonParser::class,
                    ],
                ],
                'log' => [
                    'traceLevel' => 0,
                    'targets' => [
                        [
                            'class' => DevConsole::class,
                            'levels' => ['error'],
                            'logVars' => [],
                            'categories' => ['application'],
                        ],
                        [
                            'class' => StdOut::class,
                            'levels' => ['info', 'warning', 'trace'],
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
                    'format' => Response::FORMAT_HTML,
                    'formatters' => [
                        Response::FORMAT_XML => [ // enforce HTML output for browser
                            'class' => HtmlResponseFormatter::class,
                        ],
                    ],
                ],
                'db' => $this->database->value(),
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
                    'identityClass' => User::class,
                    'enableSession' => 'true',
                ],
            ],
        ];
    }
}
