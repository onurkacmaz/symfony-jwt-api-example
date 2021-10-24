<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211023203737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_address (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, address LONGTEXT NOT NULL, city VARCHAR(100) NOT NULL, district VARCHAR(100) NOT NULL, country VARCHAR(50) NOT NULL, created_at DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_product (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, quantity DOUBLE PRECISION NOT NULL, created_at DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD order_code VARCHAR(50) NOT NULL, ADD shipping_date DATE DEFAULT NULL, ADD created_at DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE order_address');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('ALTER TABLE `order` DROP order_code, DROP shipping_date, DROP created_at');
    }
}
