<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240629074912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE musician ADD notifier VARCHAR(255)');
        $this->addSql("COMMENT ON COLUMN musician.notifier IS 'Предпочитаемый метод получения уведомления: пока только e-mail'");
		$this->addSql("UPDATE musician SET notifier = 'email' WHERE notifier IS NULL");
	    $this->addSql('ALTER TABLE musician ALTER COLUMN notifier SET NOT NULL');
        $this->addSql('CREATE INDEX musician__notifier__ix ON musician (notifier)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX musician__notifier__ix');
        $this->addSql('ALTER TABLE musician DROP notifier');
    }
}
