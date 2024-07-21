<?php

declare(strict_types=1);

namespace App\Core\Ampq\Consumer;

use App\Core\Ampq\Event\MessageInterface;
use App\Core\Ampq\Exception\ConsumerDeserializationException;
use App\Core\Ampq\Exception\ConsumerValidationException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

abstract readonly class AbstractHandler
{
	protected SerializerInterface $serializer;
	protected ValidatorInterface $validator;

	/**
	 * @throws ConsumerDeserializationException
	 */
	abstract protected function prepare(MessageInterface $message): MessageInterface;

	/**
	 * @throws ConsumerValidationException
	 */
	protected function validate(MessageInterface $message): void
	{
		$errors = $this->validator->validate($message);
		if ($errors->count() > 0) {
			throw new ConsumerValidationException((string)$errors);
		}
	}

	abstract protected function process(MessageInterface $message);

	protected function ack(): int
	{
		return 1;
	}

	protected function reject(string $error): int
	{
		echo "Неверное сообщение: $error";

		return -1;
	}
}
