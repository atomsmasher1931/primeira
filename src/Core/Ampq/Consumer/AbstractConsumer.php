<?php

declare(strict_types=1);

namespace App\Core\Ampq\Consumer;

use App\Core\Ampq\Exception\ConsumerDeserializationException;
use App\Core\Ampq\Exception\ConsumerValidationException;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

abstract readonly class AbstractConsumer implements ConsumerInterface
{
	public function __construct(
		protected SerializerInterface $serializer,
		protected ValidatorInterface $validator,
	) {
	}

	/**
	 * Deserializes data into the given type.
	 *
	 * @template TObject of object
	 * @template TType of string|class-string<TObject>
	 *
	 * @param TType $type
	 *
	 * @psalm-return (TType is class-string<TObject> ? TObject : mixed)
	 *
	 * @phpstan-return ($type is class-string<TObject> ? TObject : mixed)
	 *
	 * @throws ConsumerDeserializationException
	 * @throws ConsumerValidationException
	 */
	protected function prepareMessage(string $message, string $fqcn): mixed
	{
		try {
			$messageObject = $this->serializer->deserialize($message, $fqcn, 'json');
		} catch (Throwable $exception) {
			throw new ConsumerDeserializationException($exception);
		}

		$errors = $this->validator->validate($messageObject);
		if ($errors->count() > 0) {
			throw new ConsumerValidationException((string)$errors);
		}

		return $messageObject;
	}

	protected function reject(string $error): int
	{
		echo "Неверное сообщение: $error";

		return self::MSG_REJECT;
	}
}
