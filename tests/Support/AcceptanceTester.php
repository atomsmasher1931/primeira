<?php

declare(strict_types=1);

namespace App\Tests\Support;

use App\Core\Security\Authenticator\JWTTokenAuthenticator;
use Codeception\Scenario;
use Codeception\Util\HttpCode;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
	private const ADMIN_LOGIN = 'ipetrov';
	private const ADMIN_PASSWORD = '123456';
	private const VIEWER_LOGIN = 'kpetrov';
	private const VIEWER_PASSWORD = '123456789';

    use _generated\AcceptanceTesterActions;

	public function amAdmin(): void
	{
		$this->authenticated(self::ADMIN_LOGIN, self::ADMIN_PASSWORD);
	}

	public function amViewer(): void
	{
		$this->authenticated(self::VIEWER_LOGIN, self::VIEWER_PASSWORD);
	}

	private function authenticated(string $login, string $password): void
	{
		$this->sendAjaxPostRequest(
			'/api/person/v1/employee/login',
			[
				'login' => $login,
				'password' => $password,
			]
		);

		$this->seeResponseCodeIs(HttpCode::OK);
		$result = $this->grabPageSource();
		$this->assertJson($result);
		$result = json_decode($result, true);
		$this->assertArrayHasKey('token', $result);

		$this->setHeader('Authorization', "Bearer {$result['token']}");
	}
}
