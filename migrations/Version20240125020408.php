<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240125020408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE base_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE article (id INT NOT NULL, created_at_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_modified_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, article_number VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, base_price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE customer (id INT NOT NULL, created_at_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_modified_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE shopping_cart (id INT NOT NULL, customer_id INT DEFAULT NULL, created_at_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_modified_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_72AAD4F69395C3F3 ON shopping_cart (customer_id)');
        $this->addSql('CREATE TABLE shopping_cart_item (id INT NOT NULL, shopping_cart_id INT NOT NULL, article_id INT NOT NULL, taxation_id INT DEFAULT NULL, created_at_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_modified_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E59A1DF445F80CD ON shopping_cart_item (shopping_cart_id)');
        $this->addSql('CREATE INDEX IDX_E59A1DF47294869C ON shopping_cart_item (article_id)');
        $this->addSql('CREATE INDEX IDX_E59A1DF42224E81F ON shopping_cart_item (taxation_id)');
        $this->addSql('CREATE TABLE taxation (id INT NOT NULL, created_at_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_modified_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, tax_name VARCHAR(255) NOT NULL, tax_value_percentage DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F69395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_cart_item ADD CONSTRAINT FK_E59A1DF445F80CD FOREIGN KEY (shopping_cart_id) REFERENCES shopping_cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_cart_item ADD CONSTRAINT FK_E59A1DF47294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_cart_item ADD CONSTRAINT FK_E59A1DF42224E81F FOREIGN KEY (taxation_id) REFERENCES taxation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE base_id_seq CASCADE');
        $this->addSql('ALTER TABLE shopping_cart DROP CONSTRAINT FK_72AAD4F69395C3F3');
        $this->addSql('ALTER TABLE shopping_cart_item DROP CONSTRAINT FK_E59A1DF445F80CD');
        $this->addSql('ALTER TABLE shopping_cart_item DROP CONSTRAINT FK_E59A1DF47294869C');
        $this->addSql('ALTER TABLE shopping_cart_item DROP CONSTRAINT FK_E59A1DF42224E81F');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE shopping_cart');
        $this->addSql('DROP TABLE shopping_cart_item');
        $this->addSql('DROP TABLE taxation');
    }
}
