<?php

declare(strict_types=1);

namespace Integra\UserStory\ExternalApplication\Affise;

use Integra\Domain\QueryLog\SavedAction\Persistent;
use Integra\Infrastructure\Generic\Response;
use Integra\Infrastructure\Generic\Response\Ok;
use Integra\Models\QueryLogModel;
use DateTime;
use Yii;
use yii\db\Exception;

/**
 * Trait QueryLogTrait
 *
 * @package Integra\UserStory\ExternalApplication\Affise
 */
trait QueryLogTrait
{
    /**
     * @param string $service
     * @param string $method
     * @param bool $isFailed
     * @param $errorMessage
     *
     * @return Response
     *
     * @throws Exception
     */
    protected function createQueryLog(
        string $service,
        string $method,
        bool $isFailed = false,
        $errorMessage = null
    ): Response {
        $queryLogModel = new QueryLogModel();

        $data = [
            'service'       => $service,
            'method'        => $method,
            'is_failed'     => $isFailed,
            'error_message' => $errorMessage,
            'created_at'    => (new DateTime())->format('Y-m-d H:i:s')
        ];

        $result =
            (new Persistent(
                $queryLogModel,
                $data,
            ))->result();

        if (!$result->isSuccessful()) {
            Yii::error($result->error());
        }

        return new Ok();
    }
}
