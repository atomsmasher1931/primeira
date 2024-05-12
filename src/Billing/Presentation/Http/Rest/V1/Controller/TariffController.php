<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\V1\Controller;

use App\Billing\Application\UseCase\CreateTariffUseCase;
use App\Billing\Application\UseCase\GetTariffUseCase;
use App\Billing\Application\UseCase\UpdateTariffUseCase;
use App\Billing\Domain\Entity\Tariff;
use App\Billing\Presentation\Form\TariffType;
use App\Billing\Presentation\Http\Rest\V1\Factory\TariffDtoFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Контроллер тарифов
 */
#[Route(path: '/api/v1/billing/tariff')]
class TariffController extends AbstractController
{
	public function __construct(
		private readonly GetTariffUseCase $getTariffUseCase,
		private readonly CreateTariffUseCase $createTariffUseCase,
		private readonly UpdateTariffUseCase $updateTariffUseCase,
		private readonly FormFactoryInterface $formFactory,
		private readonly TariffDtoFactory $tariffDtoFactory,
	) {
	}

	#[Route(path: '/create', name: 'create_tariff', methods: ['GET', 'POST'])]
	#[Route(path: '/update/{id}', name: 'update_tariff', methods: ['GET', 'POST', 'PUT'])]
	public function updateTariff(Request $request, string $_route, ?string $id = null): Response
	{
		//TODO Надо бы проверить BOOL, может чего выловим

		$isNew = $_route === 'create_tariff';

		$tariff = $tariffDto = null;
		if ($id !== null) {
			$tariff = $this->getTariffUseCase->getById($id);
			$tariffDto = $this->tariffDtoFactory->createFromTariffToForm($tariff);
		}

		$form = $this->formFactory->create(TariffType::class, $tariffDto, ['isNew' => $isNew]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$tariffDto = $form->getData();

			if ($isNew) {
				$tariff = $this->createTariffUseCase->create($tariffDto);
				return $this->redirectToRoute('update_tariff', ['id' => $tariff->id]);

			} else {
				$this->updateTariffUseCase->putTariff($tariff, $this->tariffDtoFactory->createForUpdateTariff($tariffDto));
			}
		}

		return $this->render('@billing/manageTariff.twig', [
			'form' => $form,
			'isNew' => false,
			'tariff' => $tariff,
		]);
	}

	#[Route(path: '/{id}', methods: ['GET'])]
	public function getById(string $id): Tariff
	{
		return $this->getTariffUseCase->getById($id);
	}

	/**
	 * @return Tariff[]
	 */
	#[Route(path: '/all', methods: ['GET'])]
	public function getAll(): array
	{
		return $this->getTariffUseCase->getAll();
	}
}
