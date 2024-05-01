<?php

declare(strict_types=1);

namespace App\Core\Utils;

/**
 * Класс работает только с российскими номерами, остальные не проходят
 */
class PhoneValueObject
{
	private const INTERNATIONAL_COUNTRY_CODE = '7';
	private const RUSSIAN_COUNTRY_CODE = '8';
	private string $operatorCode;
	private string $subscriberNumber;



	public function __construct(string $phone)
	{
		$this->processToRaw($phone);
	}

	private function processToRaw(string $number): void
	{
		$clearedNumber = preg_replace('/[^0-9]/ui', '', $number);
		$result = preg_match('/^(7|8)([0-9]{3})([0-9]{7})$/', $clearedNumber, $parsedNumber);
		if ($result === false) {
			throw new \Exception('Ошибка парса номера телефона');
		}

		$this->operatorCode = $parsedNumber[2];
		$this->subscriberNumber = $parsedNumber[3];
	}

	private function formatSubscriberNumber(string $number): string
	{
		$formatedNumber = preg_replace('/^([0-9]{3})([0-9]{2})([0-9]{2})$/ui', '$1-$2-$3', $number);

		if (!is_string($formatedNumber)) {
			throw new \Exception('Ошибка форматирования номера абонента. Не смогли дописать минусы');
		}

		return $formatedNumber;
	}

	public function getInternationalFormattedPhoneNumber(): string
	{
		return '+'.self::INTERNATIONAL_COUNTRY_CODE."({$this->operatorCode}){$this->formatSubscriberNumber($this->subscriberNumber)}";
	}

	public function getInternationalPhoneNumber(): string
	{
		return '+'.self::INTERNATIONAL_COUNTRY_CODE."{$this->operatorCode}{$this->subscriberNumber}";
	}

	public function getRussianFormattedPhoneNumber(): string
	{
		return self::RUSSIAN_COUNTRY_CODE."({$this->operatorCode}){$this->formatSubscriberNumber($this->subscriberNumber)}";
	}

	public function getRawPhoneNumber(): string
	{
		return self::INTERNATIONAL_COUNTRY_CODE."{$this->operatorCode}{$this->subscriberNumber}";
	}
}
