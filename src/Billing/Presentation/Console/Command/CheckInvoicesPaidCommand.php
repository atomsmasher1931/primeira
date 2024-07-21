<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Console\Command;

use App\Billing\Application\UseCase\CheckInvoicesPaidUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CheckInvoicesPaidCommand extends Command
{
	public function __construct(private readonly CheckInvoicesPaidUseCase $checkInvoicesPaidUseCase)
	{
		parent::__construct();
	}

	protected function configure(): void
	{
		$this->setName('billing:invoice:check_paid_status')
			->setDescription('Проверка статуса оплаты доставленных в эквайринг счетов')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$output->write("<info>Команда проверки статуса оплаты доставленных в эквайринг счетов</info>\n");

		try {
			$paid = ($this->checkInvoicesPaidUseCase)();
		} catch (Throwable $exception) {
			$output->write(
				"\n<error>Ошибка проверка статуса оплаты.\n{$exception->getMessage()}</error>\n"
			);
			return self::FAILURE;
		}

		$output->write("<info>Проверка завершена успешно, {$paid} оплачены</info>\n");
		return self::SUCCESS;
	}
}
