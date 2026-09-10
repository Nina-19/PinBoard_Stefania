<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260910101757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD firstname VARCHAR(50) NOT NULL AFTER id, ADD lastname VARCHAR(50) NOT NULL AFTER firstname, ADD image_name VARCHAR(255) DEFAULT NULL AFTER lastname');
        $this->addSql('ALTER TABLE users MODIFY COLUMN created_at DATETIME NOT NULL AFTER image_name, MODIFY COLUMN updated_at DATETIME NOT NULL AFTER created_at');
        $this->addSql('ALTER TABLE pins ADD CONSTRAINT FK_3F0FE980A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pins DROP FOREIGN KEY FK_3F0FE980A76ED395');
        $this->addSql('ALTER TABLE users DROP firstname, DROP lastname, DROP image_name');
    }
}
