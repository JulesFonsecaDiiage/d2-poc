<?php

declare(strict_types=1);

namespace FacturationMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250719132627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE PrestationDiversConsolide (id INT IDENTITY NOT NULL, uuid_entite VARBINARY(255) NOT NULL, periode DATE NOT NULL, total_ht NUMERIC(12, 2), id_facture INT, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE PrestationDiversConsolidePrestation (id INT IDENTITY NOT NULL, prestation_divers_consolide_id INT NOT NULL, qte INT NOT NULL, prix_unitaire_ht NUMERIC(12, 2), prix_total_ht NUMERIC(12, 2), data VARCHAR(MAX), PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_BD7202E374B6D19D ON PrestationDiversConsolidePrestation (prestation_divers_consolide_id)');
        $this->addSql('ALTER TABLE PrestationDiversConsolidePrestation ADD CONSTRAINT FK_BD7202E374B6D19D FOREIGN KEY (prestation_divers_consolide_id) REFERENCES PrestationDiversConsolide (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA db_accessadmin');
        $this->addSql('CREATE SCHEMA db_backupoperator');
        $this->addSql('CREATE SCHEMA db_datareader');
        $this->addSql('CREATE SCHEMA db_datawriter');
        $this->addSql('CREATE SCHEMA db_ddladmin');
        $this->addSql('CREATE SCHEMA db_denydatareader');
        $this->addSql('CREATE SCHEMA db_denydatawriter');
        $this->addSql('CREATE SCHEMA db_owner');
        $this->addSql('CREATE SCHEMA db_securityadmin');
        $this->addSql('CREATE SCHEMA dbo');
        $this->addSql('ALTER TABLE PrestationDiversConsolidePrestation DROP CONSTRAINT FK_BD7202E374B6D19D');
        $this->addSql('DROP TABLE PrestationDiversConsolide');
        $this->addSql('DROP TABLE PrestationDiversConsolidePrestation');
    }
}
