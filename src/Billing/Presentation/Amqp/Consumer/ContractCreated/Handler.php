<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Amqp\Consumer\ContractCreated;

use App\Billing\Presentation\Amqp\Consumer\ContractCreated\Input\Message;
use App\Core\Ampq\Consumer\AbstractHandler;
use App\Core\Ampq\Event\ContractCreatedEvent;
use App\Core\Ampq\Event\MessageInterface;
use App\Core\Ampq\Event\NotifyPersonCommand;
use App\Core\Ampq\Exception\ConsumerDeserializationException;
use App\Core\Ampq\MessageBus\MessageBus;
use NotifierBundle\Enum\PersonTypeEnum;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

#[AsMessageHandler]
final readonly class Handler extends AbstractHandler
{
	public function __construct(
		protected SerializerInterface $serializer,
		protected ValidatorInterface $validator,
		private MessageBus $messageBus,
	) {
	}

	public function __invoke(ContractCreatedEvent $event): void
	{
		try {
			$message = $this->prepare($event);
			$this->validate($message);
			$this->process($message);

		} catch (Throwable $exception) {
			$this->reject($exception->getMessage());
		}

		$this->ack();
	}

	/**
	 * @inheritDoc
	 */
	protected function prepare(MessageInterface $message): MessageInterface
	{
		try {
			return new Message(
				$message->id,
				$message->number,
				$message->startDate->format('Y-m-d H:i:s'),
				$message->finishDate->format('Y-m-d H:i:s'),
				$message->status->value,
				$message->tariffId,
				$message->tariffValue,
				$message->tariffType,
				$message->tariffDegree,
				$message->tariffStatus,
				$message->musicianId,
				$message->musicianEmail,
				$message->musicianPhone,
			);
		} catch (Throwable $exception) {
			throw new ConsumerDeserializationException($exception);
		}
	}

	protected function process(MessageInterface $message): void
	{
		$cost = $message->tariffValue / 100;
		$this->messageBus->dispatch(
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
