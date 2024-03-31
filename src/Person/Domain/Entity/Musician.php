<?php

declare(strict_types=1);

namespace App\Person\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Музыкант, перкуссионист, участник коллектива
 */
#[ORM\Entity]
#[ORM\Table(options: ['comment' => 'Участник коллектива'])]
class Musician
{
	#[ORM\Id]
	#[ORM\Column(
		type: Types::GUID,
		options: ['comment' => 'Идентификтор']
	)]
	public readonly string $id;

	public readonly string $lastName;
	public readonly string $firstName;
	public readonly string $patronymic;
	private string $email;

	#[ORM\Column(
		type: Types::INTEGER,
        length: 11,
		options: ['comment' => 'Телефон']
	)]
	private string $phone;
	private string $telegram;
	private string $instagram;
	private string $facebook;
	private string $VK;

	private int $status;

	private int $degree;
}
