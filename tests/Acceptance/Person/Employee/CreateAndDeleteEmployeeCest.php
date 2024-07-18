<?php

declare(strict_types=1);

namespace Acceptance\Person\Employee;

use App\Tests\Support\AcceptanceTester;
use Codeception\Util\HttpCode;

class CreateAndDeleteEmployeeCest
{
	public function testAccessPermited(AcceptanceTester $I): void
	{
		$I->amAdmin();
		$I->sendPost(
			'/api/person/v1/employee/create',
			[
				'lastName' => 'Иванов',
				'firstName' => 'Николай',
				'patronymic' => 'Сергеевич',
				'login' => 'nivanov',
				'passwords' => '123456',
				'roles' => ['ROLE_TARIFF_MANAGER', 'ROLE_VIEWER'],
				'email' => 'nivanov_1931@primeira.ru',
				'phone' => '79111234567',
				'status' => 1,
			]
		);
		$I->seeResponseCodeIs(HttpCode::OK);
		$I->seeResponseIsJson();
		$I->seeResponseMatchesJsonType(
			[
				'id' => 'string',
				'login' => 'string',
				'roles' => ['string'],
				'lastName' => 'string',
				'firstName' => 'string',
				'patronymic' => 'string',
				'phone' => 'string',
				'email' => 'string:email',
			]
		);
		$token = $I->grabDataFromResponseByJsonPath('$.id')[0];

		$I->sendDelete("/api/person/v1/employee/delete/{$token}");
		$I->seeResponseCodeIs(HttpCode::OK);
	}

	public function testAccessDenied(AcceptanceTester $I): void
	{
		$I->amViewer();
		$I->sendPost(
			'/api/person/v1/employee/create',
			[
				'lastName' => 'Иванов',
				'firstName' => 'Николай',
				'patronymic' => 'Сергеевич',
				'login' => 'nivanov',
				'passwords' => '123456',
				'roles' => ['ROLE_TARIFF_MANAGER', 'ROLE_VIEWER'],
				'email' => 'nivanov_1931@primeira.ru',
				'phone' => '79111234567',
				'status' => 1,
			]
		);

		$I->seeResponseCodeIs(HttpCode::FORBIDDEN);
		$I->seeResponseIsJson();
		$I->seeResponseMatchesJsonType(
			[
				'success' => 'boolean',
				'message' => 'string',
			]
		);
		$I->assertFalse($I->grabDataFromResponseByJsonPath('$.success')[0]);
	}
}
