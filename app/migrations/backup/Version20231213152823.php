<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231213152823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_class (id INT AUTO_INCREMENT NOT NULL, rule_set_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, min_level INT NOT NULL, highest_level INT NOT NULL, INDEX IDX_1388FEFD8B51FD88 (rule_set_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_class_level (id INT AUTO_INCREMENT NOT NULL, character_data_id INT NOT NULL, character_class_id INT NOT NULL, level INT NOT NULL, INDEX IDX_785747895E502101 (character_data_id), INDEX IDX_78574789B201E281 (character_class_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_data (id INT AUTO_INCREMENT NOT NULL, rule_set_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_BA0FE7FB8B51FD88 (rule_set_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_stat (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, min_value INT NOT NULL, highest_value INT NOT NULL, INDEX IDX_3744EBB912469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_stat_category (id INT AUTO_INCREMENT NOT NULL, rule_set_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_BBCA752F8B51FD88 (rule_set_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_stat_value (id INT AUTO_INCREMENT NOT NULL, character_stat_id INT NOT NULL, character_data_id INT NOT NULL, value INT NOT NULL, INDEX IDX_C0DC3CA460F5AF36 (character_stat_id), INDEX IDX_C0DC3CA45E502101 (character_data_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rule_set (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, version VARCHAR(100) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE character_class ADD CONSTRAINT FK_1388FEFD8B51FD88 FOREIGN KEY (rule_set_id) REFERENCES rule_set (id)');
        $this->addSql('ALTER TABLE character_class_level ADD CONSTRAINT FK_785747895E502101 FOREIGN KEY (character_data_id) REFERENCES character_data (id)');
        $this->addSql('ALTER TABLE character_class_level ADD CONSTRAINT FK_78574789B201E281 FOREIGN KEY (character_class_id) REFERENCES character_class (id)');
        $this->addSql('ALTER TABLE character_data ADD CONSTRAINT FK_BA0FE7FB8B51FD88 FOREIGN KEY (rule_set_id) REFERENCES rule_set (id)');
        $this->addSql('ALTER TABLE character_stat ADD CONSTRAINT FK_3744EBB912469DE2 FOREIGN KEY (category_id) REFERENCES character_stat_category (id)');
        $this->addSql('ALTER TABLE character_stat_category ADD CONSTRAINT FK_BBCA752F8B51FD88 FOREIGN KEY (rule_set_id) REFERENCES rule_set (id)');
        $this->addSql('ALTER TABLE character_stat_value ADD CONSTRAINT FK_C0DC3CA460F5AF36 FOREIGN KEY (character_stat_id) REFERENCES character_stat (id)');
        $this->addSql('ALTER TABLE character_stat_value ADD CONSTRAINT FK_C0DC3CA45E502101 FOREIGN KEY (character_data_id) REFERENCES character_data (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_class DROP FOREIGN KEY FK_1388FEFD8B51FD88');
        $this->addSql('ALTER TABLE character_class_level DROP FOREIGN KEY FK_785747895E502101');
        $this->addSql('ALTER TABLE character_class_level DROP FOREIGN KEY FK_78574789B201E281');
        $this->addSql('ALTER TABLE character_data DROP FOREIGN KEY FK_BA0FE7FB8B51FD88');
        $this->addSql('ALTER TABLE character_stat DROP FOREIGN KEY FK_3744EBB912469DE2');
        $this->addSql('ALTER TABLE character_stat_category DROP FOREIGN KEY FK_BBCA752F8B51FD88');
        $this->addSql('ALTER TABLE character_stat_value DROP FOREIGN KEY FK_C0DC3CA460F5AF36');
        $this->addSql('ALTER TABLE character_stat_value DROP FOREIGN KEY FK_C0DC3CA45E502101');
        $this->addSql('DROP TABLE character_class');
        $this->addSql('DROP TABLE character_class_level');
        $this->addSql('DROP TABLE character_data');
        $this->addSql('DROP TABLE character_stat');
        $this->addSql('DROP TABLE character_stat_category');
        $this->addSql('DROP TABLE character_stat_value');
        $this->addSql('DROP TABLE rule_set');
    }
}
