<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240609100529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee (id UUID NOT NULL, last_name VARCHAR(250) NOT NULL, first_name VARCHAR(250) NOT NULL, patronymic VARCHAR(250) NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(128) NOT NULL, roles JSON NOT NULL, phone VARCHAR(11) NOT NULL, email VARCHAR(250) NOT NULL, status SMALLINT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX ux__user__login ON employee (login)');
        $this->addSql('COMMENT ON TABLE employee IS \'Пользователь системы\'');
        $this->addSql('COMMENT ON COLUMN employee.id IS \'Идентификатор\'');
        $this->addSql('COMMENT ON COLUMN employee.last_name IS \'Фамилия\'');
        $this->addSql('COMMENT ON COLUMN employee.first_name IS \'Имя\'');
        $this->addSql('COMMENT ON COLUMN employee.patronymic IS \'Отчество\'');
        $this->addSql('COMMENT ON COLUMN employee.login IS \'Логин\'');
        $this->addSql('COMMENT ON COLUMN employee.password IS \'Пароль\'');
        $this->addSql('COMMENT ON COLUMN employee.salt IS \'Соль для пароля\'');
        $this->addSql('COMMENT ON COLUMN employee.roles IS \'Роли\'');
        $this->addSql('COMMENT ON COLUMN employee.phone IS \'Главный телефон\'');
        $this->addSql('COMMENT ON COLUMN employee.email IS \'E-mail\'');
        $this->addSql('COMMENT ON COLUMN employee.status IS \'Статус: активен, на паузе, уволен\'');
        $this->addSql('COMMENT ON COLUMN employee.created_at IS \'Дата создания\'');
        $this->addSql('COMMENT ON COLUMN employee.updated_at IS \'Дата обновления\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE employee');
    }
}
