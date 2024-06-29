<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240629210302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email_notification (id UUID NOT NULL, person_id UUID NOT NULL, person_type SMALLINT NOT NULL, email VARCHAR(128) NOT NULL, topic VARCHAR(512) NOT NULL, text VARCHAR(1024) NOT NULL, status SMALLINT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX email_notification__status__ix ON email_notification (status)');
        $this->addSql('COMMENT ON TABLE email_notification IS \'Лог писем, отправляемых нотификатором\'');
        $this->addSql('COMMENT ON COLUMN email_notification.id IS \'Идентификатор\'');
        $this->addSql('COMMENT ON COLUMN email_notification.person_id IS \'Идентификатор получателя письма\'');
        $this->addSql('COMMENT ON COLUMN email_notification.person_type IS \'Тип получателя письма, пользователь, работник\'');
        $this->addSql('COMMENT ON COLUMN email_notification.email IS \'Адресат, кому отправляем письмо\'');
        $this->addSql('COMMENT ON COLUMN email_notification.topic IS \'Тема письма\'');
        $this->addSql('COMMENT ON COLUMN email_notification.text IS \'Текст письма\'');
        $this->addSql('COMMENT ON COLUMN email_notification.status IS \'Статус извещения: создано, в работе, отправлено, ошибка, отменено\'');
        $this->addSql('COMMENT ON COLUMN email_notification.created_at IS \'Дата создания\'');
        $this->addSql('COMMENT ON COLUMN email_notification.updated_at IS \'Дата обновления\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE email_notification');
    }
}
