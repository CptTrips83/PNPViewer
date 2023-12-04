<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231204155113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `character_data` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_stat (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_3744EBB912469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_stat_value (id INT AUTO_INCREMENT NOT NULL, character_stat_id INT NOT NULL, character_data_id INT DEFAULT NULL, value INT NOT NULL, INDEX IDX_C0DC3CA460F5AF36 (character_stat_id), INDEX IDX_C0DC3CA45E502101 (character_data_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_stats_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE character_stat ADD CONSTRAINT FK_3744EBB912469DE2 FOREIGN KEY (category_id) REFERENCES character_stats_category (id)');
        $this->addSql('ALTER TABLE character_stat_value ADD CONSTRAINT FK_C0DC3CA460F5AF36 FOREIGN KEY (character_stat_id) REFERENCES character_stat (id)');
        $this->addSql('ALTER TABLE character_stat_value ADD CONSTRAINT FK_C0DC3CA45E502101 FOREIGN KEY (character_data_id) REFERENCES `character_data` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_stat DROP FOREIGN KEY FK_3744EBB912469DE2');
        $this->addSql('ALTER TABLE character_stat_value DROP FOREIGN KEY FK_C0DC3CA460F5AF36');
        $this->addSql('ALTER TABLE character_stat_value DROP FOREIGN KEY FK_C0DC3CA45E502101');
        $this->addSql('DROP TABLE `character_data`');
        $this->addSql('DROP TABLE character_stat');
        $this->addSql('DROP TABLE character_stat_value');
        $this->addSql('DROP TABLE character_stats_category');
    }
}
