<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190419141437 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_D34A04ADECD792C0');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, default_currency_id, name, description, default_price FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, default_currency_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB NOT NULL COLLATE BINARY, default_price DOUBLE PRECISION NOT NULL, CONSTRAINT FK_D34A04ADECD792C0 FOREIGN KEY (default_currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product (id, default_currency_id, name, description, default_price) SELECT id, default_currency_id, name, description, default_price FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADECD792C0 ON product (default_currency_id)');
        $this->addSql('DROP INDEX IDX_5373C96638248176');
        $this->addSql('CREATE TEMPORARY TABLE __temp__country AS SELECT id, currency_id, name, code FROM country');
        $this->addSql('DROP TABLE country');
        $this->addSql('CREATE TABLE country (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, currency_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, code VARCHAR(3) NOT NULL COLLATE BINARY, CONSTRAINT FK_5373C96638248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO country (id, currency_id, name, code) SELECT id, currency_id, name, code FROM __temp__country');
        $this->addSql('DROP TABLE __temp__country');
        $this->addSql('CREATE INDEX IDX_5373C96638248176 ON country (currency_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_5373C96638248176');
        $this->addSql('CREATE TEMPORARY TABLE __temp__country AS SELECT id, currency_id, name, code FROM country');
        $this->addSql('DROP TABLE country');
        $this->addSql('CREATE TABLE country (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, currency_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(3) NOT NULL)');
        $this->addSql('INSERT INTO country (id, currency_id, name, code) SELECT id, currency_id, name, code FROM __temp__country');
        $this->addSql('DROP TABLE __temp__country');
        $this->addSql('CREATE INDEX IDX_5373C96638248176 ON country (currency_id)');
        $this->addSql('DROP INDEX UNIQ_D34A04ADECD792C0');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, default_currency_id, name, description, default_price FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, default_currency_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, default_price DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO product (id, default_currency_id, name, description, default_price) SELECT id, default_currency_id, name, description, default_price FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADECD792C0 ON product (default_currency_id)');
    }
}
