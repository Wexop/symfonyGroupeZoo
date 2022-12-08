<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221208160702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE enclos (id INT AUTO_INCREMENT NOT NULL, espace_id_id INT NOT NULL, nom VARCHAR(255) DEFAULT NULL, superficie INT NOT NULL, max_animaux INT NOT NULL, quarentaine TINYINT(1) NOT NULL, INDEX IDX_8CCECB21D2E1EE88 (espace_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE enclos ADD CONSTRAINT FK_8CCECB21D2E1EE88 FOREIGN KEY (espace_id_id) REFERENCES espace (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enclos DROP FOREIGN KEY FK_8CCECB21D2E1EE88');
        $this->addSql('DROP TABLE enclos');
    }
}
