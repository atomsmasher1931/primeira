<?php

declare(strict_types=1);

namespace App\Core\Ampq\Consumer;

use App\Core\Ampq\Event\MessageInterface;
use App\Core\Ampq\Exception\ConsumerDeserializationException;
use App\Core\Ampq\Exception\ConsumerValidationException;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

abstract readonly class AbstractConsumer implements ConsumerInterface
{
	protected SerializerInterface $serializer;
	protected ValidatorInterface $validator;

	/**
	 * @throws ConsumerDeserializationException
	 * @throws ConsumerValidationException
	 */
	abstract protected function deserializeMessage(string $message): MessageInterface;

	/**
	 * @throws ConsumerValidationException
	 */
	protected function validateMessage(MessageInterface $message): void
	{
		$errors = $this->validator->validate($message);
		if ($errors->count() > 0) {
			throw new ConsumerValidationException((string)$errors);
		}
	}

	abstract protected function processMessage(MessageInterface $message);

	/**
	 * @param AMQPMessage $msg
	 *
	 * @return bool|int
	 */
	public function execute(AMQPMessage $msg): bool|int
	{
		try {
			$message = $this->deserializeMessage($msg->getBody());
			$this->validateMessage($message);
			$this->processMessage($message);
			
		} catch (Throwable $exception) {
			return $this->reject($exception->getMessage());
		}

		return self::MSG_ACK;
	}

	protected function reject(string $error): int
	{
		echo "Неверное сообщение: $error";

		return self::MSG_REJECT;
	}
}
