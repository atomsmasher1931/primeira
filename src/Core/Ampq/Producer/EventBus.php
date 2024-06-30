<?php

declare(strict_types=1);

namespace App\Core\Ampq\Producer;

use App\Core\Ampq\Event\ContractCreatedEvent;
use App\Core\Ampq\Event\NotifyPersonCommand;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class EventBus
{
	public const CONTRACT_CREATED = 'contract_created';
	public const NOTIFY_PERSON = 'notify_person';

	/** @var ProducerInterface[] */
	private array $producers;

	public function __construct(private readonly SerializerInterface $serializer)
	{
		$this->producers = [];
	}

	public function registerProducer(string $producerName, ProducerInterface $producer): void
	{
		$this->producers[$producerName] = $producer;
	}

	public function publishContractCreated(ContractCreatedEvent $event): bool
	{
		if (isset($this->producers[self::CONTRACT_CREATED])) {
			$this->producers[self::CONTRACT_CREATED]
				->publish($this->serializer->serialize($event, 'json'));

			return true;
		}

		return false;
	}

	public function publishNotifyPersonCommand(NotifyPersonCommand $command): bool
	{
		if (isset($this->producers[self::NOTIFY_PERSON])) {
			$this->producers[self::NOTIFY_PERSON]
				->publish($this->serializer->serialize($command, 'json'));

			return true;
		}

		return false;
	}
}
