<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace Integra\Infrastructure\Queue;

use Interop\Amqp\AmqpQueue;
use Interop\Amqp\AmqpTopic;
use Interop\Amqp\Impl\AmqpBind;

/**
 * Amqp Queue.
 *
 * @author Maksym Kotliar <kotlyar.maksim@gmail.com>
 * @since 2.0.2
 */
class QueueDelayed extends \yii\queue\amqp_interop\Queue
{
    protected function setupBroker()
    {
        if ($this->setupBrokerDone) {
            return;
        }

        $queue = $this->context->createQueue($this->queueName);
        $queue->addFlag(AmqpQueue::FLAG_DURABLE);
        $queue->setArguments(array_merge(
            ['x-max-priority' => $this->maxPriority],
            $this->queueOptionalArguments
        ));
        $this->context->declareQueue($queue);

        $topic = $this->context->createTopic($this->exchangeName);
        $topic->setType($this->exchangeType);
        $topic->addFlag(AmqpTopic::FLAG_DURABLE);
        $topic->setArgument('x-delayed-type', 'direct');

        $this->context->declareTopic($topic);

        $this->context->bind(new AmqpBind($queue, $topic, $this->routingKey));

        $this->setupBrokerDone = true;
    }
}
