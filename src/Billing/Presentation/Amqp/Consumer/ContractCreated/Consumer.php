<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Amqp\Consumer\ContractCreated;

use App\Billing\Presentation\Amqp\Consumer\ContractCreated\Input\Message;
use App\Core\Ampq\Consumer\AbstractConsumer;
use App\Core\Ampq\Event\NotifyPersonCommand;
use App\Core\Ampq\Producer\EventBus;
use App\Notifier\Domain\Enum\PersonTypeEnum;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

final readonly class Consumer extends AbstractConsumer
{
	public function __construct(
		protected SerializerInterface $serializer,
		protected ValidatorInterface $validator,
		private EventBus $eventBus,
	) {
	}

	/**
	 * @param AMQPMessage $msg
	 *
	 * @return bool|int
	 */
	public function execute(AMQPMessage $msg): bool|int
	{
		try {
			/** @var Message $message */
			$message = $this->prepareMessage($msg->getBody(), Message::class);

			$cost = $message->tariffValue/100;
			$this->eventBus->publishNotifyPersonCommand(
				new NotifyPersonCommand(
					$message->musicianId,
					PersonTypeEnum::MUSICIAN,
					$message->musicianEmail,
					"Samba de primeira. С вами заключён договор №{$message->number}",
					<<<TEXT
					Поздравляем, вы заключили договор на занятие бразильской перкуссией со школой барабанов Samba de Primeira.
					Номер договора: {$message->number}
					Даты: {$message->startDate} - {$message->finishDate}
					Тариф: {$message->tariffType} для {$message->tariffDegree} на сумму {$cost}
					Наша школа рада сотрудничеству. Приходите на занятия и будьте счастливы
TEXT
				)
			);

		} catch (Throwable $exception) {
			return $this->reject($exception->getMessage());
		}

		return self::MSG_ACK;
	}

}
