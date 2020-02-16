<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200215112045 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sale_order (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, date DATE NOT NULL, INDEX IDX_25F5CB1B9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale_order_item (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, sale_order_id INT NOT NULL, quantity INT NOT NULL, unit_price INT NOT NULL, total_price INT NOT NULL, note VARCHAR(255) DEFAULT NULL, INDEX IDX_AFBEFB4D4584665A (product_id), INDEX IDX_AFBEFB4D93EB8192 (sale_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sale_order ADD CONSTRAINT FK_25F5CB1B9395C3F3 FOREIGN KEY (customer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sale_order_item ADD CONSTRAINT FK_AFBEFB4D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE sale_order_item ADD CONSTRAINT FK_AFBEFB4D93EB8192 FOREIGN KEY (sale_order_id) REFERENCES sale_order (id)');
        $this->addSql('ALTER TABLE product ADD country_id INT NOT NULL, ADD barcode VARCHAR(25) DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADF92F3E70 ON product (country_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sale_order_item DROP FOREIGN KEY FK_AFBEFB4D93EB8192');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADF92F3E70');
        $this->addSql('DROP TABLE sale_order');
        $this->addSql('DROP TABLE sale_order_item');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP INDEX IDX_D34A04ADF92F3E70 ON product');
        $this->addSql('ALTER TABLE product DROP country_id, DROP barcode');
    }
}
