<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\Contract\GetById\V1;

use App\Billing\Application\UseCase\GetContractByIdUseCase;
use App\Billing\Domain\Entity\Contract;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class GetContractByIdController extends AbstractController
{
	public function __construct(private readonly GetContractByIdUseCase $getContractUseCase)
	{
	}

	#[Route(path: '/api/billing/v1/contract/{id}', requirements: ['id' => '[0-9a-f\-]{36}'], methods: ['GET'])]
	public function __invoke(string $id): Contract
	{
		return $this->getContractUseCase->get($id);
	}
}
