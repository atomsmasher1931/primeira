<?php

declare(strict_types=1);

namespace Acceptance\Person\Employee;

use App\Tests\Support\AcceptanceTester;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use Codeception\Util\HttpCode;

/**
 * @covers \App\Person\Presentation\Http\Rest\Employee\Login\V1\LoginController
 */
class LoginApiCest
{
	private const LOGIN = 'ipetrov';
	private const PASSWORD = '123456';

	#[DataProvider('loginProvider')]
	public function test(AcceptanceTester $I, Example $example): void
	{
		$I->sendPost(
			'/api/person/v1/employee/login',
			[
				'login' => $example['account']['login'],
				'password' => $example['account']['password'],
			]
		);
		$I->seeResponseCodeIs($example['result']['code']);
		$I->seeResponseIsJson();
		$I->seeResponseMatchesJsonType($example['result']['response']);
	}

	public function loginProvider(): \Traversable
	{
		yield [
			'account' => [
				'login' => self::LOGIN,
				'password' => self::PASSWORD,
			],
			'result' => [
				'code' => HttpCode::OK,
				'response' => ['token' => 'string'],
			],
		];
		yield [
			'account' => [
				'login' => self::LOGIN,
				'password' => self::PASSWORD . '1',
			],
			'result' => [
				'code' => HttpCode::UNAUTHORIZED,
				'response' => ['success' => 'boolean', 'message' => 'string'],
			],
		];
	}
}
