<?php

declare(strict_types=1);

namespace FacturationMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250221151002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE PrestationDiversConsolide (id INT AUTO_INCREMENT NOT NULL, uuid_entite VARBINARY(255) NOT NULL, periode DATE NOT NULL, total_ht NUMERIC(12, 2) DEFAULT NULL, id_facture INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE PrestationDiversConsolidePrestation (id INT AUTO_INCREMENT NOT NULL, prestation_divers_consolide_id INT NOT NULL, qte INT NOT NULL, prix_unitaire_ht NUMERIC(12, 2) DEFAULT NULL, prix_total_ht NUMERIC(12, 2) DEFAULT NULL, data LONGTEXT DEFAULT NULL, INDEX IDX_BD7202E374B6D19D (prestation_divers_consolide_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE PrestationDiversConsolidePrestation ADD CONSTRAINT FK_BD7202E374B6D19D FOREIGN KEY (prestation_divers_consolide_id) REFERENCES PrestationDiversConsolide (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE PrestationDiversConsolidePrestation DROP FOREIGN KEY FK_BD7202E374B6D19D');
        $this->addSql('DROP TABLE PrestationDiversConsolide');
        $this->addSql('DROP TABLE PrestationDiversConsolidePrestation');
    }
}
