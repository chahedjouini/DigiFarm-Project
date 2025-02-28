<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219193114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add id_user_id column to machine table';
    }

    public function up(Schema $schema): void
    {
        // Check if the column already exists
        $schemaManager = $this->connection->getSchemaManager();
        $columns = $schemaManager->listTableColumns('machine');

        if (!isset($columns['id_user_id'])) {
            $this->addSql('ALTER TABLE machine ADD id_user_id INT NOT NULL');
            $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF8479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
            $this->addSql('CREATE INDEX IDX_1505DF8479F37AE5 ON machine (id_user_id)');
        }
    }

    public function down(Schema $schema): void
    {
        // Check if the column exists before attempting to drop it
        $schemaManager = $this->connection->getSchemaManager();
        $columns = $schemaManager->listTableColumns('machine');

        if (isset($columns['id_user_id'])) {
            $this->addSql('ALTER TABLE machine DROP FOREIGN KEY FK_1505DF8479F37AE5');
            $this->addSql('DROP INDEX IDX_1505DF8479F37AE5 ON machine');
            $this->addSql('ALTER TABLE machine DROP id_user_id');
        }
    }
}
