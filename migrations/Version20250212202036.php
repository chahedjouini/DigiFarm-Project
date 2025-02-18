<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212202036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix foreign key constraints and column definitions in maintenance and technicien tables.';
    }

    public function up(Schema $schema): void
    {
        // Drop old foreign key constraints
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E913457256'); // technicien_id
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9F6B75B26'); // machine_id

        // Drop old indexes
        $this->addSql('DROP INDEX IDX_2F84F8E9F6B75B26 ON maintenance'); // machine_id
        $this->addSql('DROP INDEX IDX_2F84F8E913457256 ON maintenance'); // technicien_id

        // Drop old columns (machine_id and technicien_id)
        $this->addSql('ALTER TABLE maintenance DROP machine_id, DROP technicien_id');

        // Ensure id_machine_id and id_technicien_id are NOT NULL
        $this->addSql('ALTER TABLE maintenance CHANGE id_machine_id id_machine_id INT NOT NULL, CHANGE id_technicien_id id_technicien_id INT NOT NULL');

        // Drop id_machine_id from technicien table
        $this->addSql('ALTER TABLE technicien DROP id_machine_id');
    }

    public function down(Schema $schema): void
    {
        // Add id_machine_id back to technicien table
        $this->addSql('ALTER TABLE technicien ADD id_machine_id INT NOT NULL');

        // Add machine_id and technicien_id back to maintenance table
        $this->addSql('ALTER TABLE maintenance ADD machine_id INT NOT NULL, ADD technicien_id INT NOT NULL');

        // Recreate foreign key constraints
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E913457256 FOREIGN KEY (technicien_id) REFERENCES technicien (id)');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9F6B75B26 FOREIGN KEY (machine_id) REFERENCES machine (id)');

        // Recreate indexes
        $this->addSql('CREATE INDEX IDX_2F84F8E9F6B75B26 ON maintenance (machine_id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E913457256 ON maintenance (technicien_id)');

        // Set id_machine_id and id_technicien_id to DEFAULT NULL
        $this->addSql('ALTER TABLE maintenance CHANGE id_machine_id id_machine_id INT DEFAULT NULL, CHANGE id_technicien_id id_technicien_id INT DEFAULT NULL');
    }
}