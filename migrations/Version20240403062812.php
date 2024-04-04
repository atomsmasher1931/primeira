<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403062812 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
	    $this->addSql(
			<<<SQL
INSERT INTO tariff(id, musician_degree_tariff, type, value, start_date, finish_date, status, created_at, updated_at)
VALUES
	('fa069516-a30a-4bc5-a967-3e4f735faa9a', 2, 0, 0, '2024-01-01 00:00:00', '2024-12-31 23:59:59', 1, '2024-04-03 09:36:00', '2024-04-03 09:36:00'),
	('5e16b8c5-adb7-47ee-9048-81153ffe39e4', 2, 1, 200000, '2024-01-01 00:00:00', '2024-12-31 23:59:59', 1, '2024-04-03 09:36:00', '2024-04-03 09:36:00'),
	('785e8a3f-d06d-4b3b-8cdc-d650e01223f2', 2, 2, 400000, '2024-01-01 00:00:00', '2024-12-31 23:59:59', 1, '2024-04-03 09:36:00', '2024-04-03 09:36:00'),
	('233843ba-0e84-43ce-abc2-d7930e0f3733', 2, 3, 560000, '2024-01-01 00:00:00', '2024-12-31 23:59:59', 1, '2024-04-03 09:36:00', '2024-04-03 09:36:00'),
	('fa11d588-91ef-42c3-9691-3ce8d4b6dc89', 1, 4, 120000, '2024-01-01 00:00:00', '2024-12-31 23:59:59', 1, '2024-04-03 09:36:00', '2024-04-03 09:36:00'),
	('aff8456d-6dd0-42fc-a068-86c1caa0212f', 1, 5, 400000, '2024-01-01 00:00:00', '2024-12-31 23:59:59', 1, '2024-04-03 09:36:00', '2024-04-03 09:36:00'),
	('fe20e655-a174-48c8-9464-2c15d78f7cf3', 1, 6, 750000, '2024-01-01 00:00:00', '2024-12-31 23:59:59', 1, '2024-04-03 09:36:00', '2024-04-03 09:36:00');
SQL
	    );

    }

    public function down(Schema $schema): void
    {
	    $this->addSql(
		    <<<SQL
DELETE
FROM tariff
WHERE id IN ('fa069516-a30a-4bc5-a967-3e4f735faa9a',
			 '5e16b8c5-adb7-47ee-9048-81153ffe39e4',
			 '785e8a3f-d06d-4b3b-8cdc-d650e01223f2',
			 '233843ba-0e84-43ce-abc2-d7930e0f3733',
			 'fa11d588-91ef-42c3-9691-3ce8d4b6dc89',
			 'aff8456d-6dd0-42fc-a068-86c1caa0212f',
			 'fe20e655-a174-48c8-9464-2c15d78f7cf3');
SQL
	    );
    }
}
