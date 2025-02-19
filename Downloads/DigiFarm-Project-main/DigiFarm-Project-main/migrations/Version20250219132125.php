<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219132125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, age INT NOT NULL, poids DOUBLE PRECISION NOT NULL, race VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suivi (id INT AUTO_INCREMENT NOT NULL, id_animal INT DEFAULT NULL, veterinaires_id INT DEFAULT NULL, temperature DOUBLE PRECISION DEFAULT NULL, rythme_cardiaque DOUBLE PRECISION DEFAULT NULL, etat VARCHAR(255) NOT NULL, id_client INT DEFAULT NULL, UNIQUE INDEX UNIQ_2EBCCA8F4C9C96F2 (id_animal), INDEX IDX_2EBCCA8FDB7791FD (veterinaires_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE veterinaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, num_tel INT NOT NULL, email VARCHAR(255) NOT NULL, adresse_cabine VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE suivi ADD CONSTRAINT FK_2EBCCA8F4C9C96F2 FOREIGN KEY (id_animal) REFERENCES animal (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suivi ADD CONSTRAINT FK_2EBCCA8FDB7791FD FOREIGN KEY (veterinaires_id) REFERENCES veterinaire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suivi DROP FOREIGN KEY FK_2EBCCA8F4C9C96F2');
        $this->addSql('ALTER TABLE suivi DROP FOREIGN KEY FK_2EBCCA8FDB7791FD');
        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP TABLE suivi');
        $this->addSql('DROP TABLE veterinaire');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
