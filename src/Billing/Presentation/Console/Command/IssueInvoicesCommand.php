<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Console\Command;

use App\Billing\Application\UseCase\IssueInvoicesUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class IssueInvoicesCommand extends Command
{
	public function __construct(private readonly IssueInvoicesUseCase $createInvoicedUseCase)
	{
		parent::__construct();
	}

	protected function configure(): void
	{
		$this->setName('billing:invoice:issue')
			->setDescription('Выписать счета по активным контрактам на текущий месяц')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$output->write("<info>Команда выписки счетов по активным контрактам на текущий месяц</info>\n");

		try {
			$count = ($this->createInvoicedUseCase)();
		} catch (Throwable $exception) {
			$output->write(
				"\n<error>Ошибка выписки счёта, скорее всего данные.\n{$exception->getMessage()}</error>\n"
			);
			return self::FAILURE;
		}

		$output->write("<info>Выписано {$count} счетов</info>\n");
		return self::SUCCESS;
	}
}
