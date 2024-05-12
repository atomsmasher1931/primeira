<?php

declare(strict_types=1);

namespace App\Billing\Domain\Entity;

use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use App\Core\Doctrine\Trait\EntityTimestampTrait;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(
	name: 'tariff',
	options: ['comment' => 'Тариф']
)]
#[ORM\Index(name: 'musician__musician_degree_tariff__ix', columns: ['musician_degree_tariff'])]
#[ORM\Index(name: 'musician__type__ix', columns: ['type'])]
#[ORM\HasLifecycleCallbacks]
class Tariff
{
	use EntityTimestampTrait;

	#[ORM\Id]
	#[ORM\Column(
		type: Types::GUID,
		options: ['comment' => 'Идентификатор']
	)]
	public readonly string $id;

	#[ORM\Column(
		type: Types::SMALLINT,
		name: 'musician_degree_tariff',
		enumType: MusicianDegreeTariffEnum::class,
		options: ['comment' => 'Уровень музыканта, для которого работает тариф']
	)]
	public readonly MusicianDegreeTariffEnum $musicianDegree;

	#[ORM\Column(
		type: Types::SMALLINT,
		enumType: TariffTypeEnum::class,
		options: ['comment' => 'Тип тарифа']
	)]
	public readonly TariffTypeEnum $type;

	#[ORM\Column(
		type: Types::INTEGER,
		precision: 8,
		scale: 2,
		options: ['comment' => 'Номинал тарифа, сколько денег возьмём в копейках']
	)]
	public readonly int $value;

	#[ORM\Column(
		type: Types::DATETIMETZ_IMMUTABLE,
		options: ['comment' => 'Дата начала действия тарифа']
	)]
	private DateTimeImmutable $startDate;

	#[ORM\Column(
		type: Types::DATETIMETZ_IMMUTABLE,
		options: ['comment' => 'Дата начала действия тарифа']
	)]
	private DateTimeImmutable $finishDate;

	#[ORM\Column(
		type: Types::SMALLINT,
		enumType: TariffStatusEnum::class,
		options: ['comment' => 'Статус тарифа']
	)]
	private TariffStatusEnum $status;

	private function __construct(
		string $id,
		MusicianDegreeTariffEnum $musicianDegree,
		TariffTypeEnum $type,
		int $value,
		DateTimeImmutable $startDate,
		DateTimeImmutable $finishDate,
		TariffStatusEnum $status,
	) {
		$this->id = $id;
		$this->musicianDegree = $musicianDegree;
		$this->type = $type;
		$this->value = $value;
		$this->startDate = $startDate;
		$this->finishDate = $finishDate;
		$this->status = $status;
	}

	public static function create(
		string $id,
		MusicianDegreeTariffEnum $musicianDegree,
		TariffTypeEnum $type,
		int $value,
		DateTimeImmutable $startDate,
		DateTimeImmutable $finishDate,
		TariffStatusEnum $status,
	): self {
		$tariff = new Tariff(
			$id,
			$musicianDegree,
			$type,
			$value,
			$startDate,
			$finishDate,
			$status
		);

		return $tariff;
	}

	public function update(
		DateTimeImmutable $startDate,
		DateTimeImmutable $finishDate,
		TariffStatusEnum $status,
	): void {
		$this->changeStartDate($startDate);
		$this->changeFinishDate($finishDate);
		$this->changeStatus($status);
	}

	public function changeStatus(TariffStatusEnum $status): void
	{
		//TODO реализовать выброс события и по событию отключение всех договор тарифа
		$this->status = $status;
	}

	public function getStartDate(): DateTimeImmutable
	{
		return $this->startDate;
	}

	public function changeFinishDate(DateTimeImmutable $finishDate): void
	{
		$this->finishDate = $finishDate;
	}

	public function getFinishDate(): DateTimeImmutable
	{
		return $this->finishDate;
	}

	public function changeStartDate(DateTimeImmutable $startDate): void
	{
		$this->startDate = $startDate;
	}


	public function getStatus(): TariffStatusEnum
	{
		return $this->status;
	}

	public function toArray(): array
	{
		return [
			'id' => $this->id,
			'musicianDegree' => $this->musicianDegree,
			'type' => $this->type,
			'value' => $this->value,
			'startDate' => $this->startDate->format('d.m.Y H:i:s'),
			'finishDate' => $this->finishDate->format('d.m.Y H:i:s'),
			'status' => $this->status,
		];
	}
}
