<?php

declare(strict_types=1);

namespace FiscalDataOperatorBundle\Entity;

use App\Core\Doctrine\Trait\EntityTimestampTrait;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(
	name: 'receipt',
	options: ['comment' => 'Чек из ОФД по факту оплаты счёта на услуги']
)]
#[ORM\Index(name: 'receipt__receipt_hash__ix', columns: ['receipt_hash'])]
#[ORM\HasLifecycleCallbacks]
class Receipt
{
	use EntityTimestampTrait;

	#[ORM\Id]
	#[ORM\Column(
		type: Types::GUID,
		options: ['comment' => 'Идентификатор']
	)]
	private string $id;

	#[ORM\Column(
		type: Types::DATETIMETZ_IMMUTABLE,
		options: ['comment' => 'Дата выпуска чека']
	)]
	private DateTimeImmutable $issueDate;

	#[ORM\Column(
		type: Types::STRING,
		options: ['comments' => 'Хэш чека, по которому планируем его получать']
	)]
	private string $receiptHash;

	#[ORM\Column(
		type: Types::DATETIMETZ_IMMUTABLE,
		options: ['comment' => 'Дата оплаты счёта']
	)]
	private DateTimeImmutable $paymentDate;

	#[ORM\Column(
		type: Types::STRING,
		options: ['comments' => 'Формулировка в счёте и чеке']
	)]
	private string $receiptMessage;

	#[ORM\Column(
		type: Types::STRING,
	)]
	private string $payerFullName;

	#[ORM\Column(
		type: Types::INTEGER,
		precision: 8,
		scale: 2,
		options: ['comment' => 'Сумма чека']
	)]
	private int $value;

	private function __construct()
	{
	}

	public static function create(
		string $id,
		 DateTimeImmutable $issueDate,
		 DateTimeImmutable $paymentDate,
		 string $receiptHash,
		 string $payerFullName,
		 string $receiptMessage,
		 int $value,
	): self
	{
		$receipt = new self();

		$receipt->id = $id;
		$receipt->issueDate = $issueDate;
		$receipt->receiptHash = $receiptHash;
		$receipt->paymentDate = $paymentDate;
		$receipt->receiptMessage = $receiptMessage;
		$receipt->payerFullName = $payerFullName;
		$receipt->value = $value;

		return $receipt;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getIssueDate(): DateTimeImmutable
	{
		return $this->issueDate;
	}

	public function getReceiptHash(): string
	{
		return $this->receiptHash;
	}

	public function getPaymentDate(): DateTimeImmutable
	{
		return $this->paymentDate;
	}

	public function getReceiptMessage(): string
	{
		return $this->receiptMessage;
	}

	public function getPayerFullName(): string
	{
		return $this->payerFullName;
	}

	public function getValue(): int
	{
		return $this->value;
	}


}
