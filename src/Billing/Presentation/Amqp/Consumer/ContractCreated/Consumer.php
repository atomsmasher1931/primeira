<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Amqp\Consumer\ContractCreated;

use App\Billing\Presentation\Amqp\Consumer\ContractCreated\Input\Message;
use App\Core\Ampq\Consumer\AbstractConsumer;
use App\Core\Ampq\Event\MessageInterface;
use App\Core\Ampq\Event\NotifyPersonCommand;
use App\Core\Ampq\Exception\ConsumerDeserializationException;
use App\Core\Ampq\Producer\EventBus;
use NotifierBundle\Enum\PersonTypeEnum;
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
	 * @inheritDoc
	 */
	protected function deserializeMessage(string $message): MessageInterface
	{
		try {
			$messageObject = $this->serializer->deserialize($message, Message::class, 'json');
		} catch (Throwable $exception) {
			throw new ConsumerDeserializationException($exception);
		}

		return $messageObject;
	}

	protected function processMessage(MessageInterface $message): void
	{
		$cost = $message->tariffValue / 100;
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
	}
}
