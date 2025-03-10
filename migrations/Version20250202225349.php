<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202225349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maintenance ADD id_machine_id INT DEFAULT NULL, ADD id_technicien_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9533DDBF1 FOREIGN KEY (id_machine_id) REFERENCES machine (id)');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9AD6DA333 FOREIGN KEY (id_technicien_id) REFERENCES technicien (id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E9533DDBF1 ON maintenance (id_machine_id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E9AD6DA333 ON maintenance (id_technicien_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9533DDBF1');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9AD6DA333');
        $this->addSql('DROP INDEX IDX_2F84F8E9533DDBF1 ON maintenance');
        $this->addSql('DROP INDEX IDX_2F84F8E9AD6DA333 ON maintenance');
        $this->addSql('ALTER TABLE maintenance DROP id_machine_id, DROP id_technicien_id');
    }
}
