<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Console\Command;

use App\Billing\Domain\Entity\Tariff;
use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use DateTimeImmutable;
use DateInterval;
use Throwable;

final class AddTariffsCommand extends Command
{
	public function __construct(
		private readonly EntityManagerInterface $entityManager,
		private readonly UnitOfWorkInterface $unitOfWork,
	) {
		parent::__construct();
	}

	protected function configure()
	{
		$this->setName('billing:tariff:add')
			->setHidden()
			->setDescription('Добавить тарифы в систему')
			->addOption('forced', 'f', InputOption::VALUE_NONE, 'Очистка тарифов перед созданием')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$output->write("<info>Команда создания базовых тарифов. Начало действия тарифов 01.07.2024</info>\n");

		$tariffData = $this->getData();
		$i = 0;
		$progressBar = new ProgressBar($output, count($tariffData));
		try {
			if ($input->getOption('forced')) {
				$output->write("<info>Очистка...</info>\n");
				$this->removeTariffs();
			}
			$output->write("<info>Создание...</info>\n");
			$progressBar->start();
			foreach ($tariffData as $item) {
				$this->unitOfWork->persist(Tariff::create(...$item));
				$i++;
				sleep(1);
				$progressBar->advance();
			}
			$this->unitOfWork->flush();

		} catch (UnitOfWorkException $exception) {
			$output->write(
				"\n<error>Ошибка сохранения тарифов, может уже запускал команду?\n{$exception->getMessage()}</error>\n"
			);
			$progressBar->finish();
			return self::FAILURE;

		} catch (Throwable $exception) {
			$output->write(
				"\n<error>Ошибка создания тарифов, скорее всего данные.\n{$exception->getMessage()}</error>\n"
			);
			$progressBar->finish();
			return self::FAILURE;
		}

		$output->write("\n<info>Создание завершено, создано {$i}.</info>\n");
		$progressBar->finish();
		return self::SUCCESS;
	}

	private function removeTariffs(): void
	{
		$queryBuilder = $this->entityManager->createQueryBuilder();

		$queryBuilder->delete(Tariff::class, 'tariff')
			->where($queryBuilder->expr()->in('tariff.id', ':tariffId'))
			->setParameter(
				':tariffId',
				[
					'fa069516-a30a-4bc5-a967-3e4f735faa9a',
					'5e16b8c5-adb7-47ee-9048-81153ffe39e4',
					'785e8a3f-d06d-4b3b-8cdc-d650e01223f2',
					'233843ba-0e84-43ce-abc2-d7930e0f3733',
					'fa11d588-91ef-42c3-9691-3ce8d4b6dc89',
					'aff8456d-6dd0-42fc-a068-86c1caa0212f',
					'fe20e655-a174-48c8-9464-2c15d78f7cf3',
				]
			)->getQuery()
			->execute()
		;
	}

	private function getData(): array
	{
		$startData = new DateTimeImmutable('2024-07-01 00:00:00');
		$finishData = $startData->add(new DateInterval('P11M'));

		return [
			[
				'fa069516-a30a-4bc5-a967-3e4f735faa9a',
				MusicianDegreeTariffEnum::FOR_EXPERIENCED,
				TariffTypeEnum::FREE,
				0,
				$startData,
				$finishData,
				TariffStatusEnum::ACTIVE,
			],
			[
				'5e16b8c5-adb7-47ee-9048-81153ffe39e4',
				MusicianDegreeTariffEnum::FOR_EXPERIENCED,
				TariffTypeEnum::CHILDISH,
				200000,
				$startData,
				$finishData,
				TariffStatusEnum::ACTIVE,
			],
			[
				'785e8a3f-d06d-4b3b-8cdc-d650e01223f2',
				MusicianDegreeTariffEnum::FOR_EXPERIENCED,
				TariffTypeEnum::STUDENT,
				400000,
				$startData,
				$finishData,
				TariffStatusEnum::ACTIVE,
			],
			[
				'233843ba-0e84-43ce-abc2-d7930e0f3733',
				MusicianDegreeTariffEnum::FOR_EXPERIENCED,
				TariffTypeEnum::ADULT,
				560000,
				$startData,
				$finishData,
				TariffStatusEnum::ACTIVE,
			],
			[
				'fa11d588-91ef-42c3-9691-3ce8d4b6dc89',
				MusicianDegreeTariffEnum::FOR_NEWBIE,
				TariffTypeEnum::SINGLE,
				120000,
				$startData,
				$finishData,
				TariffStatusEnum::ACTIVE,
			],
			[
				'aff8456d-6dd0-42fc-a068-86c1caa0212f',
				MusicianDegreeTariffEnum::FOR_NEWBIE,
				TariffTypeEnum::WEEKLY,
				400000,
				$startData,
				$finishData,
				TariffStatusEnum::ACTIVE,
			],
			[
				'fe20e655-a174-48c8-9464-2c15d78f7cf3',
				MusicianDegreeTariffEnum::FOR_NEWBIE,
				TariffTypeEnum::MONTHLY,
				750000,
				$startData,
				$finishData,
				TariffStatusEnum::ACTIVE,
			],
		];
	}
}
