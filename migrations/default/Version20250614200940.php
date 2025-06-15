<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250614200940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Expedition (id INT AUTO_INCREMENT NOT NULL, entity_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', total DOUBLE PRECISION NOT NULL, INDEX IDX_49CF93AE81257D5D (entity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Expedition ADD CONSTRAINT FK_49CF93AE81257D5D FOREIGN KEY (entity_id) REFERENCES Entite (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Expedition DROP FOREIGN KEY FK_49CF93AE81257D5D');
        $this->addSql('DROP TABLE Expedition');
    }
}
