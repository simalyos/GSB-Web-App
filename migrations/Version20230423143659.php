<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230423143659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE data_orders (id INT AUTO_INCREMENT NOT NULL, id_dt_order INT NOT NULL, id_order INT NOT NULL, id_product INT NOT NULL, quantity VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE roles ADD id INT AUTO_INCREMENT NOT NULL, CHANGE id_role id_role INT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY users_ibfk_1');
        $this->addSql('DROP INDEX id_role ON users');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE data_orders');
        $this->addSql('ALTER TABLE roles MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON roles');
        $this->addSql('ALTER TABLE roles DROP id, CHANGE id_role id_role INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE roles ADD PRIMARY KEY (id_role)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT users_ibfk_1 FOREIGN KEY (id_role) REFERENCES roles (id_role)');
        $this->addSql('CREATE INDEX id_role ON users (id_role)');
    }
}
