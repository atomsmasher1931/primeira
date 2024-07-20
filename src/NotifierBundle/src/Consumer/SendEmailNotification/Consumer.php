<?php

declare(strict_types=1);

namespace NotifierBundle\Consumer\SendEmailNotification;

use App\Core\Ampq\Consumer\AbstractConsumer;
use App\Core\Ampq\Event\MessageInterface;
use App\Core\Ampq\Exception\ConsumerDeserializationException;
use NotifierBundle\Consumer\SendEmailNotification\Input\Message;
use NotifierBundle\UseCase\CreateEmailNotificationUseCase;
use NotifierBundle\UseCase\Dto\CreateEmailNotificationDto;
use NotifierBundle\Enum\PersonTypeEnum;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

final readonly class Consumer extends AbstractConsumer
{
	public function __construct(
		protected SerializerInterface $serializer,
		protected ValidatorInterface $validator,
		private CreateEmailNotificationUseCase $createEmailNotificationUseCase,
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
