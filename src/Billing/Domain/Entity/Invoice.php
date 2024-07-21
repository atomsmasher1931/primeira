<?php

declare(strict_types=1);

namespace App\Billing\Domain\Entity;

use App\Billing\Domain\Enum\InvoiceStatusEnum;
use App\Core\Doctrine\Trait\EntityTimestampTrait;
use App\Core\Utils\MonthNameEnum;
use App\Person\Domain\Entity\Musician;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity]
#[ORM\Table(
	name: 'invoice',
	options: ['comment' => 'Ежемесячный счёт музыканту на услуги']
)]
#[ORM\UniqueConstraint(name: 'invoice__number__ux', columns: ['number'])]
#[ORM\UniqueConstraint(name: 'invoice__acquiring_number__ux', columns: ['acquiring_number'])]
#[ORM\Index(name: 'invoice__status__ix', columns: ['status'])]
#[ORM\Index(name: 'invoice__contract_id__ix', columns: ['tariff_id'])]
#[ORM\Index(name: 'invoice__tariff_id__ix', columns: ['tariff_id'])]
#[ORM\Index(name: 'invoice__musician_id__ix', columns: ['musician_id'])]
#[ORM\HasLifecycleCallbacks]
class Invoice
{
	use EntityTimestampTrait;

	#[ORM\Id]
	#[ORM\Column(
		type: Types::GUID,
		options: ['comment' => 'Идентификатор']
	)]
	private string $id;

	#[ORM\Column(
		type: Types::STRING,
		options: ['comments' => 'Статус счёта']
	)]
	private string $number;

	#[ORM\Column(
		type: Types::DATETIMETZ_IMMUTABLE,
		options: ['comment' => 'Дата оформления счёта']
	)]
	private DateTimeImmutable $issueDate;

	#[ORM\Column(
		type: Types::DATETIMETZ_IMMUTABLE,
		options: ['comment' => 'Дата протухания счёта']
	)]
	private DateTimeImmutable $expiredDate;

	#[ORM\Column(
		type: Types::DATETIMETZ_IMMUTABLE,
		nullable: true,
		options: ['comment' => 'Дата отправки счёта эквайеру']
	)]
	private ?DateTimeImmutable $sentToAcquireDate;

	#[ORM\Column(
		type: Types::DATETIMETZ_IMMUTABLE,
		nullable: true,
		options: ['comment' => 'Дата получения счёта эквайером']
	)]
	private ?DateTimeImmutable $receivedByAcquireDate;

	#[ORM\Column(
		type: Types::DATETIMETZ_IMMUTABLE,
		nullable: true,
		options: ['comment' => 'Дата оплаты счёта']
	)]
	private ?DateTimeImmutable $paidDate;

	#[ORM\Column(
		type: Types::STRING,
		nullable: true,
	)]
	private ?string $acquiringNumber;

	#[ORM\ManyToOne(targetEntity: Contract::class)]
	#[ORM\JoinColumn(
		name: 'contract_id',
		referencedColumnName: 'id',
		options: ['comment' => 'Договор, по которому выставлен счёт']
	)]
	private Contract $contract;

	#[ORM\ManyToOne(targetEntity: Tariff::class)]
	#[ORM\JoinColumn(
		name: 'tariff_id',
		referencedColumnName: 'id',
		options: ['comment' => 'Тариф, на который выставлен счёт']
	)]
	private Tariff $tariff;

	#[ORM\ManyToOne(targetEntity: Musician::class)]
	#[ORM\JoinColumn(
		name: 'musician_id',
		referencedColumnName: 'id',
		options: ['comment' => 'Музыкант, которому выставлен счёт']
	)]
	private Musician $musician;

	#[ORM\Column(
		type: Types::SMALLINT,
		enumType: InvoiceStatusEnum::class,
		options: ['comments' => 'Статус счёта']
	)]
	private InvoiceStatusEnum $status;

	public static function create(
		string $id,
		string $number,
		Contract $contract,
		Tariff $tariff,
		Musician $musician,
	): Invoice {
		$invoice = new self();

		$invoice->id = $id;
		$invoice->number = $number;
		$invoice->contract = $contract;
		$invoice->tariff = $tariff;
		$invoice->musician = $musician;

		$invoice->issued();

		return $invoice;
	}

	public function issued(): void
	{
		$this->status = InvoiceStatusEnum::ISSUED;
		$this->issueDate = new DateTimeImmutable();
		$this->expiredDate = new DateTimeImmutable('last day of this month');
	}

	public function paid(DateTimeImmutable $paidDate): void
	{
		$this->status = InvoiceStatusEnum::PAID;
		$this->paidDate = $paidDate;
	}

	public function sentToAcquire(): void
	{
		$this->status = InvoiceStatusEnum::SENT_TO_ACQUIRE;
		$this->sentToAcquireDate = new DateTimeImmutable();
	}

	public function receivedByAcquire(string $acquiringNumber): void
	{
		$this->status = InvoiceStatusEnum::RECEIVED_BY_ACQUIRE;
		$this->acquiringNumber = $acquiringNumber;
		$this->receivedByAcquireDate = new DateTimeImmutable();
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getNumber(): string
	{
		return $this->number;
	}

	public function getAcquiringNumber(): string
	{
		return $this->acquiringNumber;
	}

	public function getStatus(): InvoiceStatusEnum
	{
		return $this->status;
	}

	public function getIssueDate(): DateTimeImmutable
	{
		return $this->issueDate;
	}

	public function getExpiredDate(): DateTimeImmutable
	{
		return $this->expiredDate;
	}

	public function getPaidDate(): ?DateTimeImmutable
	{
		return $this->paidDate;
	}

	public function getSentToAcquireDate(): DateTimeImmutable
	{
		return $this->sentToAcquireDate;
	}

	public function getReceivedByAcquireDate(): DateTimeImmutable
	{
		return $this->receivedByAcquireDate;
	}

	public function getPayerFullName(): string
	{
		return "{$this->musician->getLastName()} {$this->musician->getFirstName()}, {$this->musician->getPatronymic()}";
	}

	public function getPayerPhone(): string
	{
		return $this->musician->getPhone();
	}

	public function getPayerEmail(): string
	{
		return $this->musician->getEmail();
	}

	public function getInvoiceMessage(): string
	{
		$month = MonthNameEnum::getByNumber((int)$this->issueDate->format('n'));
		$year = $this->issueDate->format('Y');

		return "Услуги по организации площадки для занятия перкуссией за {$month} {$year} в соответствии с договором №{$this->contract->number}";
	}

	public function getSum(): int
	{
		return $this->tariff->value;
	}
}
