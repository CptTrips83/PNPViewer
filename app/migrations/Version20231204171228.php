<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231204171228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE character_data ADD rule_set_id INT NOT NULL');
        $this->addSql('ALTER TABLE character_data ADD CONSTRAINT FK_BA0FE7FB8B51FD88 FOREIGN KEY (rule_set_id) REFERENCES rule_set (id)');
        $this->addSql('CREATE INDEX IDX_BA0FE7FB8B51FD88 ON character_data (rule_set_id)');
        $this->addSql('ALTER TABLE character_stat_value ADD CONSTRAINT FK_C0DC3CA45E502101 FOREIGN KEY (character_data_id) REFERENCES `character_data` (id)');
        $this->addSql('ALTER TABLE character_stats_category ADD rule_set_id INT NOT NULL');
        $this->addSql('ALTER TABLE character_stats_category ADD CONSTRAINT FK_3AE2F6EC8B51FD88 FOREIGN KEY (rule_set_id) REFERENCES rule_set (id)');
        $this->addSql('CREATE INDEX IDX_3AE2F6EC8B51FD88 ON character_stats_category (rule_set_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `character_data` DROP FOREIGN KEY FK_BA0FE7FB8B51FD88');
        $this->addSql('ALTER TABLE character_stats_category DROP FOREIGN KEY FK_3AE2F6EC8B51FD88');
        $this->addSql('DROP INDEX IDX_BA0FE7FB8B51FD88 ON `character_data`');
        $this->addSql('ALTER TABLE `character_data` DROP rule_set_id');
        $this->addSql('DROP INDEX IDX_3AE2F6EC8B51FD88 ON character_stats_category');
        $this->addSql('ALTER TABLE character_stats_category DROP rule_set_id');
        $this->addSql('ALTER TABLE character_stat_value DROP FOREIGN KEY FK_C0DC3CA45E502101');
    }
}
