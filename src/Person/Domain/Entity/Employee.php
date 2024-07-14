<?php

declare(strict_types=1);

namespace App\Person\Domain\Entity;

use App\Core\Doctrine\Trait\EntityTimestampTrait;
use App\Person\Domain\Enum\PersonStatusEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(
	name: 'employee',
	options: ['comment' => 'Пользователь системы']
)]
#[ORM\UniqueConstraint(name: 'ux__user__login', columns: ['login'])]
#[ORM\HasLifecycleCallbacks]
class Employee implements UserInterface, PasswordAuthenticatedUserInterface
{
	use EntityTimestampTrait;

	#[ORM\Id]
	#[ORM\Column(
		type: Types::GUID,
		options: ['comment' => 'Идентификатор']
	)]
	private string $id;

	#[ORM\Column(
		type: Types::STRING,
		length: 250,
		options: ['comment' => 'Фамилия']
	)]
	private string $lastName;

	#[ORM\Column(
		type: Types::STRING,
		length: 250,
		options: ['comment' => 'Имя']
	)]
	private string $firstName;

	#[ORM\Column(
		type: Types::STRING,
		length: 250,
		options: ['comment' => 'Отчество']
	)]
	private string $patronymic;

	#[ORM\Column(
		type: Types::STRING,
		length: 255,
		nullable: false,
		options: ['comment' => 'Логин']
	)]
	private string $login;

	#[ORM\Column(
		type: Types::STRING,
		length: 255,
		nullable: false,
		options: ['comment' => 'Пароль']
	)]
	private string $password;

	#[ORM\Column(
		type: Types::STRING,
		length: 128,
		nullable: false,
		options: ['comment' => 'Соль для пароля']
	)]
	private string $salt;

	#[ORM\Column(
		type: Types::JSON,
		nullable: false,
		options: ['comment' => 'Роли']
	)]
	private array $roles;

	#[ORM\Column(
		type: Types::STRING,
		length: 11,
		nullable: false,
		options: ['comment' => 'Главный телефон']
	)]
	private string $phone;

	#[ORM\Column(
		type: Types::STRING,
		length: 250,
		nullable: false,
		options: ['comment' => 'E-mail']
	)]
	private string $email;

	#[ORM\Column(
		type: Types::SMALLINT,
		enumType: PersonStatusEnum::class,
		options: ['comment' => 'Статус: активен, на паузе, уволен']
	)]
	private PersonStatusEnum $status;

	private function __construct()
	{
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getLastName(): string
	{
		return $this->lastName;
	}

	public function getFirstName(): string
	{
		return $this->firstName;
	}

	public function getPatronymic(): string
	{
		return $this->patronymic;
	}

	public function getLogin(): string
	{
		return $this->login;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function changePassword(string $password): void
	{
		$this->password = $password;
	}

	public function getSalt(): string
	{
		return $this->salt;
	}

	public function getPhone(): string
	{
		return $this->phone;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getStatus(): PersonStatusEnum
	{
		return $this->status;
	}

	/**
	 * @return string[]
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;
		$roles[] = 'ROLE_USER';

		return array_unique($roles);
	}

	public function eraseCredentials(): void
	{
	}

	public function getUserIdentifier(): string
	{
		return $this->login;
	}

	public static function create(
		string $id,
		string $lastName,
		string $firstName,
		string $patronymic,
		string $login,
		string $salt,
		array $roles,
		string $phone,
		string $email,
		PersonStatusEnum $status,
	): self {
		$employee = new self();

		$employee->id = $id;
		$employee->lastName = $lastName;
		$employee->firstName = $firstName;
		$employee->patronymic = $patronymic;
		$employee->login = $login;
		$employee->salt = $salt;
		$employee->roles = $roles;
		$employee->phone = $phone;
		$employee->email = $email;
		$employee->status = $status;

		return $employee;
	}
}
