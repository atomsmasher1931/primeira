<?php

declare(strict_types=1);

namespace App\Tests\Api\Person\Employee;

use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

/**
 * @covers \App\Person\Presentation\Http\Rest\Employee\Login\V1\LoginController
 */
class LoginApiCest
{
	private const LOGIN = 'ipetrov';
	private const PASSWORD = '123456';
	public function test(ApiTester $I): void
	{
		$I->sendPost(
			'/api/person/v1/employee/login',
			[
				'login' => self::LOGIN,
				'password' => self::PASSWORD,
			]
		);
		$I->seeResponseCodeIs(HttpCode::OK);
		$I->seeResponseIsJson();
		$I->seeResponseMatchesJsonType(['token' => 'string']);
	}
}
