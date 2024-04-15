<?php

declare(strict_types=1);

namespace App\Person\Domain\Entity;

use App\Billing\Domain\Entity\Contract;
use App\Core\Doctrine\Trait\EntityTimestampTrait;
use App\Person\Domain\Enum\PersonDegreeEnum;
use App\Person\Domain\Enum\PersonStatusEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(
	name: 'musician',
	options: ['comment' => 'Музыкант, перкуссионист, участник коллектива']
)]
#[ORM\UniqueConstraint(name: 'musician__phone__ux', columns: ['phone'])]
#[ORM\UniqueConstraint(name: 'musician__email__ux', columns: ['email'])]
#[ORM\UniqueConstraint(name: 'musician__telegram__ux', columns: ['telegram'])]
#[ORM\UniqueConstraint(name: 'musician__instagram__ux', columns: ['instagram'])]
#[ORM\UniqueConstraint(name: 'musician__facebook__ux', columns: ['facebook'])]
#[ORM\UniqueConstraint(name: 'musician__vk__ux', columns: ['vk'])]
#[ORM\Index(name: 'musician__status__ix', columns: ['status'])]
#[ORM\Index(name: 'musician__degree__ix', columns: ['degree'])]
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

	#[ORM\OneToMany(mappedBy: 'musician', targetEntity: Contract::class)]
	private Collection $contracts;

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

		$this->contracts = new ArrayCollection();
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

		$musician->changeStatus($status);
		$musician->changeDegree($degree);

		$musician->phone = $phone;
		$musician->email = $email;
		$musician->telegram = $telegram;
		$musician->instagram = $instagram;
		$musician->facebook = $facebook;
		$musician->VK = $VK;

		return $musician;
	}

	public function changeStatus(PersonStatusEnum $newStatus): void
	{
		$this->status = $newStatus;
	}

	public function changeDegree(PersonDegreeEnum $newDegree): void
	{
		$this->degree = $newDegree;
	}

	public function getName(): string
	{
		return "{$this->lastName} {$this->firstName} {$this->patronymic}";
	}

	public function getStatus(): PersonStatusEnum
	{
		return $this->status;
	}

	public function getDegree(): PersonDegreeEnum
	{
		return $this->degree;
	}

	public function getPhone(): string
	{
		return $this->phone;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getTelegram(): string
	{
		return $this->telegram;
	}

	public function getInstagram(): ?string
	{
		return $this->instagram;
	}

	public function getFacebook(): ?string
	{
		return $this->facebook;
	}

	public function getVK(): ?string
	{
		return $this->VK;
	}

	/**
	 * @param Contract[] $contract
	 */
	public function addContracts(array $contracts): void
	{
		foreach ($contracts as $contract) {
			$this->addContract($contract);
		}
	}

	public function addContract(Contract $contract): void
	{
		if (!$this->contracts->contains($contract)) {
			$this->contracts->add($contract);
		}
	}

	/**
	 * @return Contract[]
	 */
	public function getContracts(): array
	{
		return $this->contracts->toArray();
	}

	public function toArray(): array
	{
		return [
			'id' => $this->id,
			'name' => $this->getName(),
			'status' => $this->status,
			'degree' => $this->degree,
			'phone' => $this->phone,
			'email' => $this->email,
			'telegram' => $this->telegram,
			'instagram' => $this->instagram,
			'facebook' => $this->facebook,
			'VK' => $this->VK,
			'contracts' => array_map(
				static fn(Contract $contract) => $contract->toArray(),
				$this->contracts->toArray()
			),
		];
	}
}
