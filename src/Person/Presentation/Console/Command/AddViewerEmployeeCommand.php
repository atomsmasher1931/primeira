<?php

declare(strict_types=1);

namespace App\Person\Presentation\Console\Command;

use App\Billing\Domain\Entity\Tariff;
use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use App\Core\PasswordHasher\PasswordHasher;
use App\Core\UnitOfWork\UnitOfWorkException;
use App\Core\UnitOfWork\UnitOfWorkInterface;
use App\Person\Domain\Entity\Employee;
use App\Person\Domain\Enum\PersonStatusEnum;
use App\Person\Domain\Enum\RoleEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use DateTimeImmutable;
use DateInterval;
use Throwable;

final class AddViewerEmployeeCommand extends Command
{
	private const EMPLOYEE_ID = '4a1714ea-a315-4404-a867-ebd8ca97adb7';

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
		private readonly UnitOfWorkInterface $unitOfWork,
		private readonly PasswordHasher $passwordHasher,
	) {
		parent::__construct();
	}

	protected function configure():void
	{
		$this->setName('person:employee:viewer:add')
			->setHidden()
			->setDescription('Добавить работника с доступом VIEWER')
			->addOption('forced', 'f', InputOption::VALUE_NONE, 'Очистка предыдущего работника')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$output->write("<info>Команда добавления работника с доступом VIEWER</info>\n");

		try {
			if ($input->getOption('forced')) {
				$output->write("<info>Очистка...</info>\n");
				$this->removeEmployee();
			}
			$output->write("<info>Создание...</info>\n");

			$employee = Employee::create(
				self::EMPLOYEE_ID,
				'Пертов',
				'Константин',
				'Григорьевич',
				'kpetrov',
				'ecf24979d601e77d92c6341f52552d2a2212fd9f0260bf3734e0c04eba2c4f59e02db61b4b761f1b55f66b3b39d65344aab3ecec5e56aa1f3cae7c39babfbf11',
				[RoleEnum::VIEWER->value],
				'79121234567',
				'kpetrov_el_korov@primeira.ru',
				PersonStatusEnum::ACTIVE,
			);
			$employee->changePassword($this->passwordHasher->hash($employee, '123456789'));

			$this->unitOfWork->persist($employee);
			$this->unitOfWork->flush();

		} catch (UnitOfWorkException $exception) {
			$output->write(
				"\n<error>Ошибка сохранения сотрудника, может уже запускал команду?\n{$exception->getMessage()}</error>\n"
			);
			return self::FAILURE;

		} catch (Throwable $exception) {
			$output->write(
				"\n<error>Ошибка создания сотрудника, скорее всего данные кривые.\n{$exception->getMessage()}</error>\n"
			);
			return self::FAILURE;
		}

		$output->write("<info>Создание завершено.</info>\n");
		return self::SUCCESS;
	}

	private function removeEmployee(): void
	{
		$queryBuilder = $this->entityManager->createQueryBuilder();

		$queryBuilder->delete(Employee::class, 'e')
			->where($queryBuilder->expr()->eq('e.id', ':employeeId'))
			->setParameter(':employeeId', self::EMPLOYEE_ID)
			->getQuery()
			->execute()
		;
	}
}
