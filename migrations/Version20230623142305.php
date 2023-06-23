<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230623142305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE panier');
        $this->addSql('CREATE TEMPORARY TABLE __temp__devis AS SELECT id, date, livraison, lieu, date_fin, name, last_name, commande, email FROM devis');
        $this->addSql('DROP TABLE devis');
        $this->addSql('CREATE TABLE devis (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE DEFAULT NULL, livraison BOOLEAN DEFAULT NULL, lieu VARCHAR(50) DEFAULT NULL, date_fin DATE DEFAULT NULL, name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, commande CLOB DEFAULT NULL, email VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO devis (id, date, livraison, lieu, date_fin, name, last_name, commande, email) SELECT id, date, livraison, lieu, date_fin, name, last_name, commande, email FROM __temp__devis');
        $this->addSql('DROP TABLE __temp__devis');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, articles_id INTEGER DEFAULT NULL, date DATE DEFAULT NULL, livraison BOOLEAN DEFAULT NULL, lieu VARCHAR(50) DEFAULT NULL COLLATE "BINARY", date_fin DATE DEFAULT NULL, CONSTRAINT FK_24CC0DF21EBAF6CC FOREIGN KEY (articles_id) REFERENCES article (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_24CC0DF21EBAF6CC ON panier (articles_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__devis AS SELECT id, date, livraison, lieu, date_fin, name, last_name, commande, email FROM devis');
        $this->addSql('DROP TABLE devis');
        $this->addSql('CREATE TABLE devis (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, articles_id INTEGER DEFAULT NULL, date DATE DEFAULT NULL, livraison BOOLEAN DEFAULT NULL, lieu VARCHAR(50) DEFAULT NULL, date_fin DATE DEFAULT NULL, name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, commande CLOB DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_8B27C52B1EBAF6CC FOREIGN KEY (articles_id) REFERENCES article (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO devis (id, date, livraison, lieu, date_fin, name, last_name, commande, email) SELECT id, date, livraison, lieu, date_fin, name, last_name, commande, email FROM __temp__devis');
        $this->addSql('DROP TABLE __temp__devis');
        $this->addSql('CREATE INDEX IDX_8B27C52B1EBAF6CC ON devis (articles_id)');
    }
}
