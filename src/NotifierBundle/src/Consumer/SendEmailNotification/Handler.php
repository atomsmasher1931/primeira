<?php

declare(strict_types=1);

namespace NotifierBundle\Consumer\SendEmailNotification;

use App\Core\Ampq\Consumer\AbstractHandler;
use App\Core\Ampq\Event\MessageInterface;
use App\Core\Ampq\Event\NotifyPersonCommand;
use App\Core\Ampq\Exception\ConsumerDeserializationException;
use NotifierBundle\Consumer\SendEmailNotification\Input\Message;
use NotifierBundle\UseCase\CreateEmailNotificationUseCase;
use NotifierBundle\UseCase\Dto\CreateEmailNotificationDto;
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
		private CreateEmailNotificationUseCase $createEmailNotificationUseCase,
	) {
	}

	public function __invoke(NotifyPersonCommand $command): void
	{
		try {
			$message = $this->prepare($command);
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
				$message->personId,
				$message->personType->value,
				$message->email,
				$message->topic,
				$message->text,
			);
		} catch (Throwable $exception) {
			throw new ConsumerDeserializationException($exception);
		}
	}

	protected function process(MessageInterface $message): void
	{
		($this->createEmailNotificationUseCase)(
			new CreateEmailNotificationDto(
				$message->personId,
				PersonTypeEnum::tryFrom($message->personType),
				$message->email,
				$message->topic,
				$message->text,
			)
		);
	}
}
