<?php

declare(strict_types=1);

namespace App\Person\Domain\Entity;

use App\Core\Doctrine\Trait\EntityTimestampTrait;
use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Infrastructure\Repository\MusicianRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Музыкант, перкуссионист, участник коллектива
 */
#[ORM\Entity(repositoryClass: MusicianRepository::class)]
#[ORM\Table(options: ['comment' => 'Участник коллектива'])]
#[ORM\HasLifecycleCallbacks]
class Musician
{
	use EntityTimestampTrait;

	#[ORM\Id]
	#[ORM\Column(
		type: Types::GUID,
		options: ['comment' => 'Идентификтор']
	)]
	public readonly string $id;

	#[ORM\Column(
		type: Types::STRING,
		length: 250,
		options: ['comment' => 'Фамилия']
	)]
	public readonly string $lastName;

	#[ORM\Column(
		type: Types::STRING,
		length: 250,
		options: ['comment' => 'Имя']
	)]
	public readonly string $firstName;

	#[ORM\Column(
		type: Types::STRING,
		length: 250,
		options: ['comment' => 'Отчество']
	)]
	public readonly string $patronymic;

	#[ORM\Column(
		type: Types::SMALLINT,
		enumType: PersonStatusEnum::class,
		options: ['comment' => 'Статус: активен (ходит), на паузе (есть надежда, что вернётся), уволен (надежды нет)']
	)]
	private PersonStatusEnum $status;

	#[ORM\Column(
		type: Types::SMALLINT,
		enumType: PersonDegreeEnum::class,
		options: ['comment' => 'Уровень: новичок, опытный']
	)]
	private PersonDegreeEnum $degree;

	#[ORM\Column(
		type: Types::STRING,
        length: 11,
		options: ['comment' => 'Главный телефон']
	)]
	private string $phone;

	#[ORM\Column(
		type: Types::STRING,
		length: 250,
		options: ['comment' => 'E-mail']
	)]
	private string $email;


	#[ORM\Column(
		type: Types::STRING,
		length: 200,
		options: ['comment' => 'Телеграмм-аккаунт']
	)]
	private string $telegram;

	#[ORM\Column(
		type: Types::STRING,
		length: 200,
		nullable: true,
		options: ['comment' => 'Инстаграмм-аккаунт']
	)]
	private ?string $instagram;

	#[ORM\Column(
		type: Types::STRING,
		length: 200,
		nullable: true,
		options: ['comment' => 'Фэйсбук-аккаунт']
	)]
	private ?string $facebook;

	#[ORM\Column(
		type: Types::STRING,
		length: 200,
		nullable: true,
		options: ['comment' => 'ВК-аккаунт']
	)]
	private ?string $VK;

	private function __construct(
		string $id,
		string $lastName,
		string $firstName,
		string $patronymic,
	) {

		$this->id = $id;
		$this->lastName = $lastName;
		$this->firstName = $firstName;
		$this->patronymic = $patronymic;
	}

	public static function create(
		string $id,
		string $lastName,
		string $firstName,
		string $patronymic,
		PersonStatusEnum $status,
		PersonDegreeEnum $degree,
		string $phone,
		string $email,
		string $telegram,
		?string $instagram = null,
		?string $facebook = null,
		?string $VK = null,
	): self {
		$musician = new Musician($id, $lastName, $firstName, $patronymic);

		$musician->status = $status;
		$musician->degree = $degree;
		$musician->phone = $phone;
		$musician->email = $email;
		$musician->telegram = $telegram;
		$musician->instagram = $instagram;
		$musician->facebook = $facebook;
		$musician->VK = $VK;

		return $musician;
	}
}
