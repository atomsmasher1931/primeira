<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Http\Rest\V1\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Контроллер тарифов
 */
#[Route(path: '/api/v1/billing/tariff')]
class TariffController extends AbstractController
{
}
