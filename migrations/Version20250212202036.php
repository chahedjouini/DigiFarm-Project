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
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E913457256');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9F6B75B26');
        $this->addSql('DROP INDEX IDX_2F84F8E9F6B75B26 ON maintenance');
        $this->addSql('DROP INDEX IDX_2F84F8E913457256 ON maintenance');
        $this->addSql('ALTER TABLE maintenance DROP machine_id, DROP technicien_id, CHANGE id_machine_id id_machine_id INT NOT NULL, CHANGE id_technicien_id id_technicien_id INT NOT NULL');
        $this->addSql('ALTER TABLE technicien DROP id_machine_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maintenance ADD machine_id INT NOT NULL, ADD technicien_id INT NOT NULL, CHANGE id_machine_id id_machine_id INT DEFAULT NULL, CHANGE id_technicien_id id_technicien_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E913457256 FOREIGN KEY (technicien_id) REFERENCES technicien (id)');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9F6B75B26 FOREIGN KEY (machine_id) REFERENCES machine (id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E9F6B75B26 ON maintenance (machine_id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E913457256 ON maintenance (technicien_id)');
        $this->addSql('ALTER TABLE technicien ADD id_machine_id INT NOT NULL');
    }
}
