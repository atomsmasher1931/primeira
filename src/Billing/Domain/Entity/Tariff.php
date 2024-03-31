<?php

declare(strict_types=1);

namespace App\Billing\Domain\Entity;

use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use App\Core\Doctrine\Trait\EntityTimestampTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;


/**
 * Тариф
 */
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
		enumType: MusicianDegreeTariffEnum::class,
		options: ['comment' => 'Уровень музыканта, для которого работает тариф']
	)]
	public readonly MusicianDegreeTariffEnum $musicianDegreeTariff;

	#[ORM\Column(
		type: Types::SMALLINT,
		enumType: TariffTypeEnum::class,
		options: ['comment' => 'Тип тарифа']
	)]
	public readonly TariffTypeEnum $type;

	#[ORM\Column(
		type: Types::DECIMAL,
		precision: 8,
		scale: 2,
		options: ['comment' => 'Номинал тарифа, сколько денег возьмём']
	)]
	public readonly float $value;

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
		float $value,
		DateTimeImmutable $startDate,
		DateTimeImmutable $finishDate,
		TariffStatusEnum $status,
	) {
		$this->id = $id;
		$this->musicianDegreeTariff = $musicianDegree;
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
		float $value,
		DateTimeImmutable $startDate,
		DateTimeImmutable $finishDate,
		TariffStatusEnum $status,
	) {
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

	public function getStartDate(): DateTimeImmutable
	{
		return $this->startDate;
	}

	public function getFinishDate(): DateTimeImmutable
	{
		return $this->finishDate;
	}

	public function getStatus(): TariffStatusEnum
	{
		return $this->status;
	}
}
