<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231219160348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_data ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE character_data ADD CONSTRAINT FK_BA0FE7FBA76ED395 FOREIGN KEY (user_id) REFERENCES pnpuser (id)');
        $this->addSql('CREATE INDEX IDX_BA0FE7FBA76ED395 ON character_data (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_data DROP FOREIGN KEY FK_BA0FE7FBA76ED395');
        $this->addSql('DROP INDEX IDX_BA0FE7FBA76ED395 ON character_data');
        $this->addSql('ALTER TABLE character_data DROP user_id');
    }
}
