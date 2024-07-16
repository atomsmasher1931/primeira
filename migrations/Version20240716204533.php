<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716204533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание базового админа. Поменяйте пароль человеку';
    }

    public function up(Schema $schema): void
    {
	    $this->addSql(<<<SQL
INSERT INTO employee(id, last_name, first_name, patronymic, login, password, salt, roles, phone, email, status,
					 created_at, updated_at)
VALUES ('ab0cf837-5eb4-48dc-b40a-5c7605ec6112', 'Петров', 'Иван', 'Денисович', 'ipetrov',
		'$2y$13$.8uIDeMXUzIX72eDQYg2yebTD.MimCcuxDwuOgfU1/o4YnIu0qEeu',
		'4e51bf95cec7174e9e378ee2d071f5d027e78c717f605c1dc3204952209e1234e9dd01068e8da70232635894a1ee786c26a2df584ada140f1b09d2f104281f06',
		'["ROLE_ADMIN"]', '79261234567', 'ipetrov@primeira.ru', 1, now(), now());
SQL
	    );

    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM employee WHERE id = 'ab0cf837-5eb4-48dc-b40a-5c7605ec6112'");
    }
}
