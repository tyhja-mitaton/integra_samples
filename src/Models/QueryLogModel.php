<?php

declare(strict_types=1);

namespace Integra\Models;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $service
 * @property string $method
 * @property bool $is_failed
 * @property string $error_message
 * @property string $created_at
 */
class QueryLogModel extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'query_logs';
    }
}
