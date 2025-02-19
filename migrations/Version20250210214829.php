<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210214829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etude ADD CONSTRAINT FK_1DDEA924B108249D FOREIGN KEY (culture_id) REFERENCES culture (id)');
        $this->addSql('ALTER TABLE etude ADD CONSTRAINT FK_1DDEA924C5568CE4 FOREIGN KEY (expert_id) REFERENCES expert (id)');
        $this->addSql('ALTER TABLE expert ADD dispo VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE maintenance ADD id_machine_id INT DEFAULT NULL, ADD id_technicien_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9533DDBF1 FOREIGN KEY (id_machine_id) REFERENCES machine (id)');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9AD6DA333 FOREIGN KEY (id_technicien_id) REFERENCES technicien (id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E9533DDBF1 ON maintenance (id_machine_id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E9AD6DA333 ON maintenance (id_technicien_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etude DROP FOREIGN KEY FK_1DDEA924B108249D');
        $this->addSql('ALTER TABLE etude DROP FOREIGN KEY FK_1DDEA924C5568CE4');
        $this->addSql('ALTER TABLE expert DROP dispo');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9533DDBF1');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9AD6DA333');
        $this->addSql('DROP INDEX IDX_2F84F8E9533DDBF1 ON maintenance');
        $this->addSql('DROP INDEX IDX_2F84F8E9AD6DA333 ON maintenance');
        $this->addSql('ALTER TABLE maintenance DROP id_machine_id, DROP id_technicien_id');
    }
}
