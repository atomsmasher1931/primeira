<?php

declare(strict_types=1);

namespace NotifierBundle\Entity;

use App\Core\Doctrine\Trait\EntityTimestampTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use NotifierBundle\Enum\EmailNotificationStatusEnum;
use NotifierBundle\Enum\PersonTypeEnum;

#[ORM\Entity]
#[ORM\Table(
	name: 'email_notification',
	options: ['comment' => 'Лог писем, отправляемых нотификатором']
)]
#[ORM\Index(name: 'email_notification__status__ix', columns: ['status'])]
#[ORM\HasLifecycleCallbacks]
class EmailNotification
{
	use EntityTimestampTrait;

	#[ORM\Id]
	#[ORM\Column(
		type: Types::GUID,
		options: ['comment' => 'Идентификатор']
	)]
	private string $id;

	#[ORM\Column(
		type: Types::GUID,
		options: ['comment' => 'Идентификатор получателя письма']
	)]
	private string $personId;

	#[ORM\Column(
		type: Types::SMALLINT,
		enumType: PersonTypeEnum::class,
		options: ['comment' => 'Тип получателя письма, пользователь, работник']
	)]
	private PersonTypeEnum $personType;

	#[ORM\Column(type: Types::STRING, length: 128, nullable: false, options: ['comment' => 'Адресат, кому отправляем письмо'])]
	private string $email;

	#[ORM\Column(type: Types::STRING, length: 1024, nullable: false, options: ['comment' => 'Тема письма'])]
	private string $topic;

	#[ORM\Column(type: Types::TEXT, nullable: false, options: ['comment' => 'Текст письма'])]
	private string $text;

	#[ORM\Column(type: Types::SMALLINT, nullable: false, enumType: EmailNotificationStatusEnum::class, options: ['comment' => 'Статус извещения: создано, в работе, отправлено, ошибка, отменено'])]
	private EmailNotificationStatusEnum $status;

	private function __construct()
	{
	}

	public static function create(
		string $id,
		string $personId,
		PersonTypeEnum $personType,
		string $email,
		string $topic,
		string $text,
	): EmailNotification {
		$emailNotification = new self();

		$emailNotification->id = $id;
		$emailNotification->personId = $personId;
		$emailNotification->personType = $personType;
		$emailNotification->email = $email;
		$emailNotification->topic = $topic;
		$emailNotification->text = $text;

		$emailNotification->created();

		return $emailNotification;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getPersonId(): string
	{
		return $this->personId;
	}

	public function getPersonType(): PersonTypeEnum
	{
		return $this->personType;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getTopic(): string
	{
		return $this->topic;
	}

	public function getText(): string
	{
		return $this->text;
	}

	public function getStatus(): EmailNotificationStatusEnum
	{
		return $this->status;
	}

	public function created(): void
	{
		$this->status = EmailNotificationStatusEnum::CREATED;
	}
}
