<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401130204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pnpuser_pwreset_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created DATETIME NOT NULL, code VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2B923D6EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pnpuser_pwreset_request ADD CONSTRAINT FK_2B923D6EA76ED395 FOREIGN KEY (user_id) REFERENCES pnpuser (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pnpuser_pwreset_request DROP FOREIGN KEY FK_2B923D6EA76ED395');
        $this->addSql('DROP TABLE pnpuser_pwreset_request');
    }
}
