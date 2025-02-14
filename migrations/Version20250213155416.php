<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213155416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie CHANGE name name VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE formation CHANGE published_at published_at DATETIME NOT NULL, CHANGE title title VARCHAR(100) NOT NULL, CHANGE video_id video_id VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE playlist CHANGE name name VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE categorie CHANGE name name VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE formation CHANGE published_at published_at DATETIME DEFAULT NULL, CHANGE title title VARCHAR(100) DEFAULT NULL, CHANGE video_id video_id VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE playlist CHANGE name name VARCHAR(100) DEFAULT NULL');
    }
}
