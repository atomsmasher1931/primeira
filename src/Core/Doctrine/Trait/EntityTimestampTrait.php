<?php

declare(strict_types=1);

namespace App\Core\Doctrine\Trait;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use DateTimeImmutable;

/**
 * Трейт меток даты сущности. Сейчас: время создания и изменения
 */
trait EntityTimestampTrait
{
	#[ORM\Column(
		type: Types::DATETIMETZ_IMMUTABLE,
		options: ['comment' => 'Дата создания']
	)]
	private DateTimeImmutable $createdAt;

	#[ORM\Column(
		type: Types::DATETIMETZ_IMMUTABLE,
		options: ['comment' => 'Дата обновления']
	)]
	private DateTimeImmutable $updatedAt;

	public function getCreatedAt(): DateTimeImmutable {
		return $this->createdAt;
	}

	#[ORM\PrePersist]
	public function setCreatedAt(): void {
		$this->createdAt = new DateTimeImmutable();
	}

	public function getUpdatedAt(): DateTimeImmutable {
		return $this->updatedAt;
	}

	#[ORM\PrePersist]
	#[ORM\PreUpdate]
	public function setUpdatedAt(): void {
		$this->updatedAt = new DateTimeImmutable();
	}
}
