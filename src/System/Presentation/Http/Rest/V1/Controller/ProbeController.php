<?php

declare(strict_types=1);

namespace App\System\Presentation\Http\Rest\V1\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Проверка проекта на работоспособность
 */
#[Route(path: '/api/v1/probe')]
class ProbeController extends AbstractController
{
	private const RESULT_APP = ['app' => 'ok'];

	public function __construct(private readonly EntityManagerInterface $entityManager)
	{
	}

	#[Route(path: '/liveness', methods: ['GET'])]
	public function liveness(): Response
	{
		return $this->json(self::RESULT_APP);
	}

	#[Route(path: '/readiness', methods: ['GET'])]
	public function readiness(): Response
	{
		$startTime = microtime(true);
				$result = self::RESULT_APP;
		try {
			$queryBuilder = $this->entityManager->getConnection()->createQueryBuilder();
			$queryBuilder->select('1');
			$answer = (bool)$queryBuilder->executeQuery()->fetchFirstColumn() ? 'ok' : 'fail';
			$finishTime = microtime(true);

			$result['db'] = ['status' => $answer, 'duration' => $finishTime - $startTime];
		} catch (\Throwable $exception) {
			$result['db'] = ['status' => 'fail', 'error' => $exception->getMessage()];
		}

		return $this->json($result);
	}
}
