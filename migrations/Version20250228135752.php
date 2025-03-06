<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250228135752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, idc INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, numero INT NOT NULL, typeabb VARCHAR(255) NOT NULL, dureeabb INT NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE animal (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, age INT NOT NULL, poids DOUBLE PRECISION NOT NULL, race VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, abonnement_id INT NOT NULL, datef DATE NOT NULL, prixt DOUBLE PRECISION NOT NULL, cin INT NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_FE866410F1D74413 (abonnement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suivi (id INT AUTO_INCREMENT NOT NULL, id_animal INT DEFAULT NULL, veterinaire_id INT NOT NULL, temperature DOUBLE PRECISION DEFAULT NULL, rythme_cardiaque DOUBLE PRECISION DEFAULT NULL, etat VARCHAR(255) NOT NULL, id_client INT DEFAULT NULL, UNIQUE INDEX UNIQ_2EBCCA8F4C9C96F2 (id_animal), INDEX IDX_2EBCCA8F5C80924 (veterinaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse_mail VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE veterinaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, num_tel INT NOT NULL, email VARCHAR(255) NOT NULL, adresse_cabine VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410F1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement (id)');
        $this->addSql('ALTER TABLE suivi ADD CONSTRAINT FK_2EBCCA8F4C9C96F2 FOREIGN KEY (id_animal) REFERENCES animal (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suivi ADD CONSTRAINT FK_2EBCCA8F5C80924 FOREIGN KEY (veterinaire_id) REFERENCES veterinaire (id)');
        $this->addSql('ALTER TABLE culture ADD id_user_id INT DEFAULT NULL, ADD densite_plantation DOUBLE PRECISION NOT NULL, ADD besoins_eau DOUBLE PRECISION NOT NULL, ADD besoins_engrais VARCHAR(255) NOT NULL, ADD rendement_moyen DOUBLE PRECISION NOT NULL, ADD cout_moyen DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE culture ADD CONSTRAINT FK_B6A99CEB79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6A99CEB79F37AE5 ON culture (id_user_id)');
        $this->addSql('ALTER TABLE etude ADD id_user_id INT DEFAULT NULL, ADD type_sol VARCHAR(255) NOT NULL, ADD irrigation TINYINT(1) NOT NULL, ADD fertilisation TINYINT(1) NOT NULL, ADD prix DOUBLE PRECISION NOT NULL, ADD rendement DOUBLE PRECISION NOT NULL, ADD precipitations DOUBLE PRECISION NOT NULL, ADD main_oeuvre DOUBLE PRECISION NOT NULL, DROP id_user, DROP cout, CHANGE culture_id culture_id INT NOT NULL, CHANGE type_etude climat VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE etude ADD CONSTRAINT FK_1DDEA924B108249D FOREIGN KEY (culture_id) REFERENCES culture (id)');
        $this->addSql('ALTER TABLE etude ADD CONSTRAINT FK_1DDEA924C5568CE4 FOREIGN KEY (expert_id) REFERENCES expert (id)');
        $this->addSql('ALTER TABLE etude ADD CONSTRAINT FK_1DDEA92479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1DDEA92479F37AE5 ON etude (id_user_id)');
        $this->addSql('ALTER TABLE expert ADD dispo VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE maintenance ADD id_machine_id INT NOT NULL, ADD id_technicien_id INT NOT NULL');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9533DDBF1 FOREIGN KEY (id_machine_id) REFERENCES machine (id)');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9AD6DA333 FOREIGN KEY (id_technicien_id) REFERENCES technicien (id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E9533DDBF1 ON maintenance (id_machine_id)');
        $this->addSql('CREATE INDEX IDX_2F84F8E9AD6DA333 ON maintenance (id_technicien_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE culture DROP FOREIGN KEY FK_B6A99CEB79F37AE5');
        $this->addSql('ALTER TABLE etude DROP FOREIGN KEY FK_1DDEA92479F37AE5');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410F1D74413');
        $this->addSql('ALTER TABLE suivi DROP FOREIGN KEY FK_2EBCCA8F4C9C96F2');
        $this->addSql('ALTER TABLE suivi DROP FOREIGN KEY FK_2EBCCA8F5C80924');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE suivi');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE veterinaire');
        $this->addSql('DROP INDEX IDX_B6A99CEB79F37AE5 ON culture');
        $this->addSql('ALTER TABLE culture DROP id_user_id, DROP densite_plantation, DROP besoins_eau, DROP besoins_engrais, DROP rendement_moyen, DROP cout_moyen');
        $this->addSql('ALTER TABLE etude DROP FOREIGN KEY FK_1DDEA924B108249D');
        $this->addSql('ALTER TABLE etude DROP FOREIGN KEY FK_1DDEA924C5568CE4');
        $this->addSql('DROP INDEX IDX_1DDEA92479F37AE5 ON etude');
        $this->addSql('ALTER TABLE etude ADD id_user INT NOT NULL, ADD type_etude VARCHAR(255) NOT NULL, ADD cout INT NOT NULL, DROP id_user_id, DROP climat, DROP type_sol, DROP irrigation, DROP fertilisation, DROP prix, DROP rendement, DROP precipitations, DROP main_oeuvre, CHANGE culture_id culture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE expert DROP dispo');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9533DDBF1');
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E9AD6DA333');
        $this->addSql('DROP INDEX IDX_2F84F8E9533DDBF1 ON maintenance');
        $this->addSql('DROP INDEX IDX_2F84F8E9AD6DA333 ON maintenance');
        $this->addSql('ALTER TABLE maintenance DROP id_machine_id, DROP id_technicien_id');
    }
}
