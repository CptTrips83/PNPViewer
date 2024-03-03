<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231215122212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pnpuser (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_16FF88E5F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
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
        $this->addSql('DROP TABLE pnpuser');
        $this->addSql('ALTER TABLE character_class DROP FOREIGN KEY FK_1388FEFD8B51FD88');
        $this->addSql('ALTER TABLE character_class_level DROP FOREIGN KEY FK_785747895E502101');
        $this->addSql('ALTER TABLE character_class_level DROP FOREIGN KEY FK_78574789B201E281');
        $this->addSql('ALTER TABLE character_data DROP FOREIGN KEY FK_BA0FE7FB8B51FD88');
        $this->addSql('ALTER TABLE character_stat DROP FOREIGN KEY FK_3744EBB912469DE2');
        $this->addSql('ALTER TABLE character_stat_category DROP FOREIGN KEY FK_BBCA752F8B51FD88');
        $this->addSql('ALTER TABLE character_stat_value DROP FOREIGN KEY FK_C0DC3CA460F5AF36');
        $this->addSql('ALTER TABLE character_stat_value DROP FOREIGN KEY FK_C0DC3CA45E502101');
    }
}
