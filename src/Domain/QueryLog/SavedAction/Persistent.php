<?php

declare(strict_types=1);

namespace Integra\Domain\QueryLog\SavedAction;

use Integra\Infrastructure\Generic\Result;
use Integra\Infrastructure\Generic\Result\Failed;
use Integra\Infrastructure\Generic\Result\Successful;
use Integra\Models\QueryLogModel;
use yii\db\Exception;

/**
 * Class Persistent
 *
 * @package Integra\Domain\QueryLog\SavedAction
 */
class Persistent
{
    /**
     * @param QueryLogModel $queryLogModel
     * @param array $validationValue
     */
    public function __construct(private QueryLogModel $queryLogModel, private array $validationValue) {}

    /**
     * @throws Exception
     */
    public function result(): Result
    {
        if (array_key_exists('service', $this->validationValue)) {
            $this->queryLogModel->service = $this->validationValue['service'];
        }

        if (array_key_exists('method', $this->validationValue)) {
            $this->queryLogModel->method = $this->validationValue['method'];
        }

        if (array_key_exists('is_failed', $this->validationValue)) {
            $this->queryLogModel->is_failed = $this->validationValue['is_failed'];
        }

        if (array_key_exists('created_at', $this->validationValue)) {
            $this->queryLogModel->created_at = $this->validationValue['created_at'];
        }

        $this->queryLogModel->save();

        if ($this->queryLogModel->hasErrors()) {
            return new Failed($this->queryLogModel->getErrorSummary(true));
        }

        return new Successful([$this->queryLogModel]);
    }
}
