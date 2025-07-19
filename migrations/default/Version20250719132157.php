<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250719132157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Entite (id INT IDENTITY NOT NULL, uuid VARBINARY(255) NOT NULL, active BIT NOT NULL, name NVARCHAR(255) NOT NULL, email NVARCHAR(255) NOT NULL, created_at DATETIME2(6) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE Expedition (id INT IDENTITY NOT NULL, entity_id INT, code NVARCHAR(255) NOT NULL, created_at DATETIME2(6) NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_49CF93AE81257D5D ON Expedition (entity_id)');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'Expedition\', N\'COLUMN\', \'created_at\'');
        $this->addSql('CREATE TABLE Produit (id INT IDENTITY NOT NULL, libelle NVARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, date_creation DATETIME2(6) NOT NULL, actif BIT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT IDENTITY NOT NULL, body VARCHAR(MAX) NOT NULL, headers VARCHAR(MAX) NOT NULL, queue_name NVARCHAR(190) NOT NULL, created_at DATETIME2(6) NOT NULL, available_at DATETIME2(6) NOT NULL, delivered_at DATETIME2(6), PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', \'created_at\'');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', \'available_at\'');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'messenger_messages\', N\'COLUMN\', \'delivered_at\'');
        $this->addSql('ALTER TABLE Expedition ADD CONSTRAINT FK_49CF93AE81257D5D FOREIGN KEY (entity_id) REFERENCES Entite (id)');
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
        $this->addSql('ALTER TABLE Expedition DROP CONSTRAINT FK_49CF93AE81257D5D');
        $this->addSql('DROP TABLE Entite');
        $this->addSql('DROP TABLE Expedition');
        $this->addSql('DROP TABLE Produit');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
