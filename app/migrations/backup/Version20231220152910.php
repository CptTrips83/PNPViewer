<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231220152910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_class ADD long_description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE character_stat ADD long_description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE character_stat_category ADD long_description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_class DROP long_description');
        $this->addSql('ALTER TABLE character_stat DROP long_description');
        $this->addSql('ALTER TABLE character_stat_category DROP long_description');
    }
}
