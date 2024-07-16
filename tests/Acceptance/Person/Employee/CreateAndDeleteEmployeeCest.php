<?php

declare(strict_types=1);

namespace Acceptance\Person\Employee;

use App\Tests\Support\AcceptanceTester;
use Codeception\Util\HttpCode;

/**
 *
 */
class CreateAndDeleteEmployeeCest
{
	public function testAccessPermited(AcceptanceTester $I): void
	{
		$I->amAdmin();
		$I->sendAjaxPostRequest(
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
		$result = $I->grabPageSource();
		$I->assertJson($result);
		$result = json_decode($result, true);
		$I->assertArrayHasKey('id', $result);

		$I->sendAjaxRequest('DELETE',"/api/person/v1/employee/delete/{$result['id']}");
		$I->seeResponseCodeIs(HttpCode::OK);
	}

	public function testAccessDenied(AcceptanceTester $I)
	{
		$I->amViewer();
		$I->sendAjaxPostRequest(
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

		$result = $I->grabPageSource();
		$I->assertJson($result);

		$result = json_decode($result, true);
		$I->assertArrayHasKey('success', $result);
		$I->assertArrayHasKey('message', $result);
		$I->assertFalse($result['success']);
	}
}
