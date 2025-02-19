<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212100220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE culture ADD densite_plantation DOUBLE PRECISION NOT NULL, ADD besoins_eau DOUBLE PRECISION NOT NULL, ADD besoins_engrais VARCHAR(255) NOT NULL, ADD rendement_moyen DOUBLE PRECISION NOT NULL, ADD cout_moyen DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE etude ADD type_sol VARCHAR(255) NOT NULL, ADD irrigation TINYINT(1) NOT NULL, ADD fertilisation TINYINT(1) NOT NULL, ADD prix DOUBLE PRECISION NOT NULL, ADD rendement DOUBLE PRECISION NOT NULL, ADD precipitations DOUBLE PRECISION NOT NULL, ADD main_oeuvre DOUBLE PRECISION NOT NULL, DROP cout, CHANGE type_etude climat VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE culture DROP densite_plantation, DROP besoins_eau, DROP besoins_engrais, DROP rendement_moyen, DROP cout_moyen');
        $this->addSql('ALTER TABLE etude ADD type_etude VARCHAR(255) NOT NULL, ADD cout INT NOT NULL, DROP climat, DROP type_sol, DROP irrigation, DROP fertilisation, DROP prix, DROP rendement, DROP precipitations, DROP main_oeuvre');
    }
}
