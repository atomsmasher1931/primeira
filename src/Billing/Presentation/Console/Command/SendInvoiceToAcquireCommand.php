<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Console\Command;

use App\Billing\Application\UseCase\SendInvoiceToAcquireUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class SendInvoiceToAcquireCommand extends Command
{
	public function __construct(private readonly SendInvoiceToAcquireUseCase $sendInvoiceToAcquireUseCase)
	{
		parent::__construct();
	}

	protected function configure(): void
	{
		$this->setName('billing:invoice:send_to_acquire')
			->setDescription('Отправка созданных и недоставленных счетов в эквайринг')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$output->write("<info>Команда отправки созданных и недоставленных счетов в эквайринг</info>\n");

		try {
			$result = ($this->sendInvoiceToAcquireUseCase)();
		} catch (Throwable $exception) {
			$output->write(
				"\n<error>Ошибка отправки счётов, скорее всего данные.\n{$exception->getMessage()}</error>\n"
			);
			return self::FAILURE;
		}

		if ($result['didnt_sent'] > 0) {
			$output->write(
				"\n<error>Ошибка отправки счётов:\n* не отправлено: {$result['didnt_sent']},\n* отправлено: {$result['sent']},\n* переотправлено: {$result['resent']}</error>\n"
			);
			return self::FAILURE;
		}
		$sent = $result['sent']+$result['resent'];

		$output->write("<info>Все счета {$sent} успешно отправлены, из них отправлены повторно {$result['resent']}</info>\n");
		return self::SUCCESS;
	}
}
