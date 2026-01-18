<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Generic\Response;

use Integra\Models\Ubet\TranslateNamespaces;

class OptionalError
{
    public function __construct(
        private ?string $namespace = null,
        private ?string $alert = null,
    ) {
    }

    public function error(): array
    {
        $error = [];

        if (!empty($this->namespace)) {
            $error['namespace'] = $this->namespace;
        }

        if (!empty($this->alert)) {
            $error['alert'] = $this->alert;
        }

        $translateNamespaceModel = TranslateNamespaces::find()
            ->joinWith(['translates'])
            ->andWhere(['name' => $this->namespace])
            ->one();

        if(!empty($translateNamespaceModel->translates)) {
            foreach ($translateNamespaceModel->translates as $translate) {
                if ($translate->title == $this->alert) {
                    $error['localization'] = [
                        'ru' => $translate->rus,
                        'en' => $translate->en,
                        'kk' => $translate->kz,
                    ];

                    break;
                }
            }
        }else {
            $error['localization'] = [
                'ru' => $this->alert,
                'en' => $this->alert,
                'kk' => $this->alert,
            ];
        }

        return $error;
    }
}
