<?php

declare(strict_types=1);

namespace App\Billing\Domain\Entity;

use App\Billing\Domain\Enum\ContractStatusEnum;
use App\Core\Doctrine\Trait\EntityTimestampTrait;
use App\Person\Domain\Entity\Musician;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(
	name: 'contract',
	options: ['comment' => 'Договор между музыкантом и школой']
)]
#[ORM\UniqueConstraint(name: 'contract__number__ux', columns: ['number'])]
#[ORM\Index(name: 'contract__status__ix', columns: ['status'])]
#[ORM\Index(name: 'contract__tariff_id__ix', columns: ['tariff_id'])]
#[ORM\Index(name: 'contract__musician_id__ix', columns: ['musician_id'])]
#[ORM\HasLifecycleCallbacks]
final class Contract
{
	use EntityTimestampTrait;

	#[ORM\Id]
	#[ORM\Column(
		type: Types::GUID,
		options: ['comment' => 'Идентификатор']
	)]
	public readonly string $id;

	#[ORM\Column(
		type: Types::STRING,
		options: ['comment' => 'Номер договора']
	)]
	public readonly string $number;

	#[ORM\Column(
		type: Types::DATETIMETZ_IMMUTABLE,
		options: ['comment' => 'Дата начала действия договора']
	)]
	private DateTimeImmutable $startDate;

	#[ORM\Column(
		type: Types::DATETIMETZ_IMMUTABLE,
		options: ['comment' => 'Дата начала действия договора']
	)]
	private DateTimeImmutable $finishDate;

	#[ORM\Column(
		type: Types::SMALLINT,
		enumType: ContractStatusEnum::class,
		options: ['comment' => 'Статус договора']
	)]
	private ContractStatusEnum $status;

	#[ORM\ManyToOne(targetEntity: Tariff::class)]
	#[ORM\JoinColumn(
		name: 'tariff_id',
		referencedColumnName: 'id',
		options: ['comment' => 'Базовый тариф договора, счёт можем выставлять на другой тариф']
	)]
	private Tariff $tariff;

	#[ORM\ManyToOne(targetEntity: Musician::class, inversedBy: 'contracts')]
	#[ORM\JoinColumn(
		name: 'musician_id',
		referencedColumnName: 'id',
		options: ['comment' => 'Музыкант, с которым заключаем договор']
	)]
	private Musician $musician;

	/**
	 * @param string             $id
	 * @param string             $number
	 * @param DateTimeImmutable  $startDate
	 * @param DateTimeImmutable  $finishDate
	 * @param ContractStatusEnum $status
	 * @param Tariff             $tariff
	 * @param Musician           $musician
	 */
	private function __construct(
		string $id,
		string $number,
	) {
		$this->id = $id;
		$this->number = $number;
	}

	public static function create(
		string $id,
		string $number,
		DateTimeImmutable $startDate,
		DateTimeImmutable $finishDate,
		Tariff $tariff,
		Musician $musician
	): Contract {
		$contract = new Contract($id, $number);
		$contract->startDate = $startDate;
		$contract->finishDate = $finishDate;
		$contract->status = ContractStatusEnum::INACTIVE;
		$contract->tariff = $tariff;
		$contract->musician = $musician;

		return $contract;
	}

	public function getStartDate(): DateTimeImmutable
	{
		return $this->startDate;
	}

	public function getFinishDate(): DateTimeImmutable
	{
		return $this->finishDate;
	}

	public function getStatus(): ContractStatusEnum
	{
		return $this->status;
	}

	public function getTariff(): Tariff
	{
		return $this->tariff;
	}

	public function getMusician(): Musician
	{
		return $this->musician;
	}

	public static function generateNumber(int $year, int $serial, int $quantity)
	{
		$serialPrepared = preg_replace('/^(\d{1})$/', '0$1', (string)$serial);
		$quantityPrepared = preg_replace('/^(\d{1})$/', '0$1', (string)$quantity);

		return "{$year}/{$serialPrepared}-{$quantityPrepared}";
	}

	public function activate()
	{
		$this->status = ContractStatusEnum::ACTIVE;
		return $this;
	}

	public function changeTariff(Tariff $tariff)
	{
		$this->tariff = $tariff;
		return $this;
	}

	public function getTariffId(): string
	{
		return $this->tariff->id;
	}

	public function getTariffValue(): int
	{
		return $this->tariff->value;
	}

	public function getTariffTypeName(): string
	{
		return $this->tariff->getTypeName();
	}

	public function getTariffDegreeName(): string
	{
		return $this->tariff->getDegreeName();
	}
	
	public function getTariffStatusName(): string
	{
		return $this->tariff->getStatusName();
	}

	public function getMusicianId(): string
	{
		return $this->musician->getId();
	}

	public function getMusicianEmail(): string
	{
		return $this->musician->getEmail();
	}

	public function getMusicianPhone(): string
	{
		return $this->musician->getPhone();
	}


}
