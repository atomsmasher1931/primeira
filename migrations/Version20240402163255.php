<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402163255 extends AbstractMigration
{

	public function isTransactional(): bool
	{
		return false;
	}

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE IF NOT EXISTS contract (id UUID NOT NULL, number VARCHAR(255) NOT NULL, start_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, finish_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, status SMALLINT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, tariff_id UUID DEFAULT NULL, musician_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX CONCURRENTLY IF NOT EXISTS contract__status__ix ON contract (status)');
        $this->addSql('CREATE INDEX CONCURRENTLY IF NOT EXISTS contract__tariff_id__ix ON contract (tariff_id)');
        $this->addSql('CREATE INDEX CONCURRENTLY IF NOT EXISTS contract__musician_id__ix ON contract (musician_id)');
        $this->addSql('CREATE UNIQUE INDEX CONCURRENTLY IF NOT EXISTS contract__number__ux ON contract (number)');
        $this->addSql('COMMENT ON TABLE contract IS \'Договор между музыкантом и школой\'');
        $this->addSql('COMMENT ON COLUMN contract.id IS \'Идентификатор\'');
        $this->addSql('COMMENT ON COLUMN contract.number IS \'Номер договора\'');
        $this->addSql('COMMENT ON COLUMN contract.start_date IS \'Дата начала действия договора\'');
        $this->addSql('COMMENT ON COLUMN contract.finish_date IS \'Дата начала действия договора\'');
        $this->addSql('COMMENT ON COLUMN contract.status IS \'Статус договора\'');
        $this->addSql('COMMENT ON COLUMN contract.created_at IS \'Дата создания\'');
        $this->addSql('COMMENT ON COLUMN contract.updated_at IS \'Дата обновления\'');
        $this->addSql('COMMENT ON COLUMN contract.tariff_id IS \'Базовый тариф договора, счёт можем выставлять на другой тариф\'');
        $this->addSql('COMMENT ON COLUMN contract.musician_id IS \'Музыкант, с которым заключаем договор\'');
        $this->addSql('CREATE TABLE IF NOT EXISTS musician  (id UUID NOT NULL, last_name VARCHAR(250) NOT NULL, first_name VARCHAR(250) NOT NULL, patronymic VARCHAR(250) NOT NULL, status SMALLINT NOT NULL, degree SMALLINT NOT NULL, phone VARCHAR(11) NOT NULL, email VARCHAR(250) NOT NULL, telegram VARCHAR(200) NOT NULL, instagram VARCHAR(200) DEFAULT NULL, facebook VARCHAR(200) DEFAULT NULL, vk VARCHAR(200) DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX CONCURRENTLY IF NOT EXISTS musician__status__ix ON musician (status)');
        $this->addSql('CREATE INDEX CONCURRENTLY IF NOT EXISTS musician__degree__ix ON musician (degree)');
        $this->addSql('CREATE UNIQUE INDEX CONCURRENTLY IF NOT EXISTS musician__phone__ux ON musician (phone)');
        $this->addSql('CREATE UNIQUE INDEX CONCURRENTLY IF NOT EXISTS musician__email__ux ON musician (email)');
        $this->addSql('CREATE UNIQUE INDEX CONCURRENTLY IF NOT EXISTS musician__telegram__ux ON musician (telegram)');
        $this->addSql('CREATE UNIQUE INDEX CONCURRENTLY IF NOT EXISTS musician__instagram__ux ON musician (instagram)');
        $this->addSql('CREATE UNIQUE INDEX CONCURRENTLY IF NOT EXISTS musician__facebook__ux ON musician (facebook)');
        $this->addSql('CREATE UNIQUE INDEX CONCURRENTLY IF NOT EXISTS musician__vk__ux ON musician (vk)');
        $this->addSql('COMMENT ON TABLE musician IS \'Музыкант, перкуссионист, участник коллектива\'');
        $this->addSql('COMMENT ON COLUMN musician.id IS \'Идентификтор\'');
        $this->addSql('COMMENT ON COLUMN musician.last_name IS \'Фамилия\'');
        $this->addSql('COMMENT ON COLUMN musician.first_name IS \'Имя\'');
        $this->addSql('COMMENT ON COLUMN musician.patronymic IS \'Отчество\'');
        $this->addSql('COMMENT ON COLUMN musician.status IS \'Статус: активен (ходит), на паузе (есть надежда, что вернётся), уволен (надежды нет)\'');
        $this->addSql('COMMENT ON COLUMN musician.degree IS \'Уровень: новичок, опытный\'');
        $this->addSql('COMMENT ON COLUMN musician.phone IS \'Главный телефон\'');
        $this->addSql('COMMENT ON COLUMN musician.email IS \'E-mail\'');
        $this->addSql('COMMENT ON COLUMN musician.telegram IS \'Телеграмм-аккаунт\'');
        $this->addSql('COMMENT ON COLUMN musician.instagram IS \'Инстаграмм-аккаунт\'');
        $this->addSql('COMMENT ON COLUMN musician.facebook IS \'Фэйсбук-аккаунт\'');
        $this->addSql('COMMENT ON COLUMN musician.vk IS \'ВК-аккаунт\'');
        $this->addSql('COMMENT ON COLUMN musician.created_at IS \'Дата создания\'');
        $this->addSql('COMMENT ON COLUMN musician.updated_at IS \'Дата обновления\'');
        $this->addSql('CREATE TABLE IF NOT EXISTS tariff (id UUID NOT NULL, musician_degree_tariff SMALLINT NOT NULL, type SMALLINT NOT NULL, value INT NOT NULL, start_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, finish_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, status SMALLINT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX CONCURRENTLY IF NOT EXISTS musician__musician_degree_tariff__ix ON tariff (musician_degree_tariff)');
        $this->addSql('CREATE INDEX CONCURRENTLY IF NOT EXISTS musician__type__ix ON tariff (type)');
        $this->addSql('COMMENT ON TABLE tariff IS \'Тариф\'');
        $this->addSql('COMMENT ON COLUMN tariff.id IS \'Идентификатор\'');
        $this->addSql('COMMENT ON COLUMN tariff.musician_degree_tariff IS \'Уровень музыканта, для которого работает тариф\'');
        $this->addSql('COMMENT ON COLUMN tariff.type IS \'Тип тарифа\'');
        $this->addSql('COMMENT ON COLUMN tariff.value IS \'Номинал тарифа, сколько денег возьмём в копейках\'');
        $this->addSql('COMMENT ON COLUMN tariff.start_date IS \'Дата начала действия тарифа\'');
        $this->addSql('COMMENT ON COLUMN tariff.finish_date IS \'Дата начала действия тарифа\'');
        $this->addSql('COMMENT ON COLUMN tariff.status IS \'Статус тарифа\'');
        $this->addSql('COMMENT ON COLUMN tariff.created_at IS \'Дата создания\'');
        $this->addSql('COMMENT ON COLUMN tariff.updated_at IS \'Дата обновления\'');
        $this->addSql('ALTER TABLE IF EXISTS contract ADD CONSTRAINT fk__contract__tariff_id__tariff__id FOREIGN KEY (tariff_id) REFERENCES tariff (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE IF EXISTS contract ADD CONSTRAINT fk__contract__musician_id__musician__id FOREIGN KEY (musician_id) REFERENCES musician (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE IF EXISTS contract DROP CONSTRAINT fk__contract__tariff_id__tariff__id');
        $this->addSql('ALTER TABLE IF EXISTS contract DROP CONSTRAINT fk__contract__musician_id__musician__id');
        $this->addSql('DROP TABLE IF EXISTS contract');
        $this->addSql('DROP TABLE IF EXISTS musician');
        $this->addSql('DROP TABLE IF EXISTS tariff');
    }
}
