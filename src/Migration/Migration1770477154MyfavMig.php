<?php declare(strict_types=1);

namespace Myfav\Mig\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1770477154MyfavMig extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1770477154;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement(
            'CREATE TABLE IF NOT EXISTS `myfav_mig` (
                `id` BINARY(16) NOT NULL,
                `name` VARCHAR(256) DEFAULT \'New\',
                `controller_name` VARCHAR(256) DEFAULT NULL,
                `pos` INT(11) DEFAULT 0,
                `state` INT(11) DEFAULT 0,
                `settings` JSON DEFAULT NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
