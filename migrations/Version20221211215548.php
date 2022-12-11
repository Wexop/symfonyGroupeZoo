<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221211215548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal ADD enclos_id INT NOT NULL, ADD date_depart DATE DEFAULT NULL, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE date_arrive date_arrive DATE NOT NULL');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FB1C0859 FOREIGN KEY (enclos_id) REFERENCES enclos (id)');
        $this->addSql('CREATE INDEX IDX_6AAB231FB1C0859 ON animal (enclos_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_6AAB231FB1C0859');
        $this->addSql('DROP INDEX IDX_6AAB231FB1C0859 ON animal');
        $this->addSql('ALTER TABLE animal DROP enclos_id, DROP date_depart, CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE date_arrive date_arrive DATE DEFAULT NULL');
    }
}
