<?php

declare(strict_types=1);

namespace App\Billing\Domain\Entity;

use App\Billing\Domain\Enum\ContractStatusEnum;
use App\Billing\Domain\Repository\ContractRepositoryInterface;
use App\Core\Doctrine\Trait\EntityTimestampTrait;
use App\Person\Domain\Entity\Musician;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractRepositoryInterface::class)]
#[ORM\Table(
	name: 'contract',
	options: ['comment' => 'Договор между музыкантом и школой']
)]
#[ORM\UniqueConstraint(name: 'contract__number__ux', columns: ['number'])]
#[ORM\Index(name: 'contract__status__ix', columns: ['status'])]
#[ORM\Index(name: 'contract__tariff_id__ix', columns: ['tariff_id'])]
#[ORM\Index(name: 'contract__musician_id__ix', columns: ['musician_id'])]
#[ORM\HasLifecycleCallbacks]
class Contract
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
}
