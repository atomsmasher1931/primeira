<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240721235307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE receipt (id UUID NOT NULL, issue_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, receipt_hash VARCHAR(255) NOT NULL, payment_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, receipt_message VARCHAR(255) NOT NULL, payer_full_name VARCHAR(255) NOT NULL, value INT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX receipt__receipt_hash__ix ON receipt (receipt_hash)');
        $this->addSql('COMMENT ON TABLE receipt IS \'Чек из ОФД по факту оплаты счёта на услуги\'');
        $this->addSql('COMMENT ON COLUMN receipt.id IS \'Идентификатор\'');
        $this->addSql('COMMENT ON COLUMN receipt.issue_date IS \'Дата выпуска чека\'');
        $this->addSql('COMMENT ON COLUMN receipt.payment_date IS \'Дата оплаты счёта\'');
        $this->addSql('COMMENT ON COLUMN receipt.value IS \'Сумма чека\'');
        $this->addSql('COMMENT ON COLUMN receipt.created_at IS \'Дата создания\'');
        $this->addSql('COMMENT ON COLUMN receipt.updated_at IS \'Дата обновления\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE receipt');
    }
}
