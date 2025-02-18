<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218195321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entite (id INT AUTO_INCREMENT NOT NULL, uuid VARBINARY(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestation_divers_consolide (id INT AUTO_INCREMENT NOT NULL, entite_id INT NOT NULL, periode DATE NOT NULL, total_ht NUMERIC(12, 2) DEFAULT NULL, id_facture INT DEFAULT NULL, INDEX IDX_F42953B69BEA957A (entite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestation_divers_consolide_prestation (id INT AUTO_INCREMENT NOT NULL, prestation_divers_consolide_id INT NOT NULL, qte INT NOT NULL, prix_unitaire_ht NUMERIC(12, 2) DEFAULT NULL, prix_total_ht NUMERIC(12, 2) DEFAULT NULL, data LONGTEXT DEFAULT NULL, INDEX IDX_82EDD81674B6D19D (prestation_divers_consolide_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prestation_divers_consolide ADD CONSTRAINT FK_F42953B69BEA957A FOREIGN KEY (entite_id) REFERENCES entite (id)');
        $this->addSql('ALTER TABLE prestation_divers_consolide_prestation ADD CONSTRAINT FK_82EDD81674B6D19D FOREIGN KEY (prestation_divers_consolide_id) REFERENCES prestation_divers_consolide (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prestation_divers_consolide DROP FOREIGN KEY FK_F42953B69BEA957A');
        $this->addSql('ALTER TABLE prestation_divers_consolide_prestation DROP FOREIGN KEY FK_82EDD81674B6D19D');
        $this->addSql('DROP TABLE entite');
        $this->addSql('DROP TABLE prestation_divers_consolide');
        $this->addSql('DROP TABLE prestation_divers_consolide_prestation');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
