<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240721192942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice (id UUID NOT NULL, number VARCHAR(255) NOT NULL, issue_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, expired_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, sent_to_acquire_date TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, received_by_acquire_date TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, paid_date TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, acquiring_number VARCHAR(255) DEFAULT NULL, status SMALLINT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, contract_id UUID DEFAULT NULL, tariff_id UUID DEFAULT NULL, musician_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_906517442576E0FD ON invoice (contract_id)');
        $this->addSql('CREATE INDEX invoice__status__ix ON invoice (status)');
        $this->addSql('CREATE INDEX invoice__contract_id__ix ON invoice (tariff_id)');
        $this->addSql('CREATE INDEX invoice__tariff_id__ix ON invoice (tariff_id)');
        $this->addSql('CREATE INDEX invoice__musician_id__ix ON invoice (musician_id)');
        $this->addSql('CREATE UNIQUE INDEX invoice__number__ux ON invoice (number)');
        $this->addSql('CREATE UNIQUE INDEX invoice__acquiring_number__ux ON invoice (acquiring_number)');
        $this->addSql('COMMENT ON TABLE invoice IS \'Ежемесячный счёт музыканту на услуги\'');
        $this->addSql('COMMENT ON COLUMN invoice.id IS \'Идентификатор\'');
        $this->addSql('COMMENT ON COLUMN invoice.issue_date IS \'Дата оформления счёта\'');
        $this->addSql('COMMENT ON COLUMN invoice.expired_date IS \'Дата протухания счёта\'');
        $this->addSql('COMMENT ON COLUMN invoice.sent_to_acquire_date IS \'Дата отправки счёта эквайеру\'');
        $this->addSql('COMMENT ON COLUMN invoice.received_by_acquire_date IS \'Дата получения счёта эквайером\'');
        $this->addSql('COMMENT ON COLUMN invoice.paid_date IS \'Дата оплаты счёта\'');
        $this->addSql('COMMENT ON COLUMN invoice.created_at IS \'Дата создания\'');
        $this->addSql('COMMENT ON COLUMN invoice.updated_at IS \'Дата обновления\'');
        $this->addSql('COMMENT ON COLUMN invoice.contract_id IS \'Договор, по которому выставлен счёт\'');
        $this->addSql('COMMENT ON COLUMN invoice.tariff_id IS \'Тариф, на который выставлен счёт\'');
        $this->addSql('COMMENT ON COLUMN invoice.musician_id IS \'Музыкант, которому выставлен счёт\'');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT fk__invoice__contract_id__contract__id FOREIGN KEY (contract_id) REFERENCES contract (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT fk__invoice__tariff_id__tariff__id FOREIGN KEY (tariff_id) REFERENCES tariff (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT fk__invoice__musician_id__musician__id FOREIGN KEY (musician_id) REFERENCES musician (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT fk__invoice__contract_id__contract__id');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT fk__invoice__tariff_id__tariff__id');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT fk__invoice__musician_id__musician__id');
        $this->addSql('DROP TABLE invoice');
    }
}
