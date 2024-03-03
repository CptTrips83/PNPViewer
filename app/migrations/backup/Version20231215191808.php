<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231215191808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_stat_category ADD class_needed_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE character_stat_category ADD CONSTRAINT FK_BBCA752FB701D53D FOREIGN KEY (class_needed_id) REFERENCES character_class (id)');
        $this->addSql('CREATE INDEX IDX_BBCA752FB701D53D ON character_stat_category (class_needed_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_stat_category DROP FOREIGN KEY FK_BBCA752FB701D53D');
        $this->addSql('DROP INDEX IDX_BBCA752FB701D53D ON character_stat_category');
        $this->addSql('ALTER TABLE character_stat_category DROP class_needed_id');
    }
}
