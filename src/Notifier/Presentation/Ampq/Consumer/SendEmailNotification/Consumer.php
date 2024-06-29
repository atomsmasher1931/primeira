<?php

declare(strict_types=1);

namespace App\Notifier\Presentation\Ampq\Consumer\SendEmailNotification;

use App\Core\Ampq\Consumer\AbstractConsumer;
use App\Notifier\Application\Dto\CreateEmailNotificationDto;
use App\Notifier\Application\UseCase\CreateEmailNotificationUseCase;
use App\Notifier\Domain\Enum\PersonTypeEnum;
use App\Notifier\Presentation\Ampq\Consumer\SendEmailNotification\Input\NotifyPersonCommand;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

final readonly class Consumer extends AbstractConsumer implements ConsumerInterface
{
	public function __construct(
		protected SerializerInterface $serializer,
		protected ValidatorInterface $validator,
		private CreateEmailNotificationUseCase $createEmailNotificationUseCase,
	) {
		parent::__construct($serializer, $validator);
	}

	/**
	 * @param AMQPMessage $msg
	 *
	 * @return bool|int
	 */
	public function execute(AMQPMessage $msg)
	{
		try {
			/* @var NotifyPersonCommand $message */
			$message = $this->prepareMessage($msg->getBody(), NotifyPersonCommand::class);

			($this->createEmailNotificationUseCase)(
				new CreateEmailNotificationDto(
					$message->personId,
					PersonTypeEnum::tryFrom($message->personType),
					$message->email,
					$message->topic,
					$message->text,
				)
			);

		} catch (Throwable $exception) {
			return $this->reject($exception->getMessage());
		}

		return self::MSG_ACK;
	}
}
