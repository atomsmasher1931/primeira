<?php

declare(strict_types=1);

namespace App\Billing\Presentation\Form;

use App\Billing\Domain\Enum\MusicianDegreeTariffEnum;
use App\Billing\Domain\Enum\TariffStatusEnum;
use App\Billing\Domain\Enum\TariffTypeEnum;
use App\Billing\Presentation\Http\Rest\V1\Input\TariffManageDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Форма работы с тарифами
 */
class TariffType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$isNotNew = !$options['isNew'];

		$builder
			->add('musicianDegree', ChoiceType::class, [
				'placeholder' => 'Не выбрано',
				'choices' => [
					'Новичок' => MusicianDegreeTariffEnum::FOR_NEWBIE->value,
					'Опытный' => MusicianDegreeTariffEnum::FOR_EXPERIENCED->value,
				],
				'label' => 'Уровень музыканта',
				'attr' => [
					'data-time' => time(),
					'readonly' => $isNotNew,
					'class' => 'tariff-musician-degree',
					'style' => $isNotNew === true ? 'background-color: #e9ecef;' : null,
				],
			])
			->add('type', ChoiceType::class, [
				'placeholder' => 'Не выбрано',
				'choices' => [
					'Бесплатно' => TariffTypeEnum::FREE->value,
					'Детский' => TariffTypeEnum::CHILDISH->value,
					'Студенческий' => TariffTypeEnum::STUDENT->value,
					'Взрослый' => TariffTypeEnum::ADULT->value,
					'Разовое занятие' => TariffTypeEnum::SINGLE->value,
					'Раз в неделю' => TariffTypeEnum::WEEKLY->value,
					'За месяц' => TariffTypeEnum::MONTHLY->value,
				],
				'label' => 'Тип тарифа',
				'attr' => [
					'readonly' => $isNotNew,
					'style' => $isNotNew === true ? 'background-color: #e9ecef;' : null,
				],
			])
			->add('value', MoneyType::class, [
				'label' => 'Стоимость',
				'currency' => 'RUB',
				'divisor' => 100,
				'html5' => true,
				'attr' => [
					'readonly' => $isNotNew,
				],
			])
			->add('startDate', DateTimeType::class, [
				'label' => 'Начало действия',
				'placeholder' => '---',
				'date_widget' => 'single_text',
				'input' => 'datetime_immutable',
			])
			->add('finishDate', DateTimeType::class, [
				'label' => 'Окончание действия',
				'placeholder' => '---',
				'date_widget' => 'single_text',
				'input' => 'datetime_immutable',
			])
			->add('status', ChoiceType::class, [
				'placeholder' => 'Не выбрано',
				'choices' => [
					'Активный' => TariffStatusEnum::ACTIVE->value,
					'Отключённый' => TariffStatusEnum::INACTIVE->value,
				],
				'label' => 'Статус'
			])
			->add('submit', SubmitType::class, ['label' => 'Сохранить'])
		;

		if ($isNotNew) {
			$builder->setMethod('PUT');
		} else {
			$builder->setMethod('POST');
		}
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => TariffManageDto::class,
			'empty_data' => new TariffManageDto(),
			'isNew' => false,
		]);
	}
}
