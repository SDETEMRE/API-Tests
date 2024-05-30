<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20240428174124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `Service Log` table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('service_log');
        $table->addColumn('id', Types::BIGINT)->setAutoincrement(true);
        $table->addColumn('service_name', Types::STRING)->setLength(50)->setNotnull(true);
        $table->addColumn('date_time', Types::DATETIME_MUTABLE)->setNotnull(true);
        $table->addColumn('http_method', Types::STRING)->setLength(10)->setNotnull(true);
        $table->addColumn('route', Types::TEXT)->setNotnull(true);
        $table->addColumn('protocol', Types::STRING)->setLength(15)->setNotnull(true);
        $table->addColumn('http_code', Types::SMALLINT)->setNotnull(true);

        $table->setPrimaryKey(['id']);
        $table->addIndex(['service_name','http_code', 'date_time'], 'idx_service_name_http_code_date_time');

    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('service_log');
    }
}
