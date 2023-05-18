<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518141446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accueil_bottom (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(25) DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, link VARCHAR(25) DEFAULT NULL)');
        $this->addSql('CREATE TABLE accueil_top (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, picture VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, text VARCHAR(255) DEFAULT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE accueil_bottom');
        $this->addSql('DROP TABLE accueil_top');
    }
}
