<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230602123949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devis (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, articles_id INTEGER DEFAULT NULL, date DATE DEFAULT NULL, livraison BOOLEAN DEFAULT NULL, lieu VARCHAR(50) DEFAULT NULL, date_fin DATE DEFAULT NULL, CONSTRAINT FK_8B27C52B1EBAF6CC FOREIGN KEY (articles_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_8B27C52B1EBAF6CC ON devis (articles_id)');
        $this->addSql('DROP TABLE panier');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, name, is_verified FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, name VARCHAR(50) DEFAULT NULL, is_verified BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password, name, is_verified) SELECT id, email, roles, password, name, is_verified FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, articles_id INTEGER DEFAULT NULL, date DATE DEFAULT NULL, livraison BOOLEAN DEFAULT NULL, lieu VARCHAR(50) DEFAULT NULL COLLATE "BINARY", date_fin DATE DEFAULT NULL, CONSTRAINT FK_24CC0DF21EBAF6CC FOREIGN KEY (articles_id) REFERENCES article (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_24CC0DF21EBAF6CC ON panier (articles_id)');
        $this->addSql('DROP TABLE devis');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, name, is_verified FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, name VARCHAR(50) DEFAULT NULL, is_verified BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password, name, is_verified) SELECT id, email, roles, password, name, is_verified FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
