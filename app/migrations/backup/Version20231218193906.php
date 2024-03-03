<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218193906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pnpgroup (id INT AUTO_INCREMENT NOT NULL, rule_set_id INT NOT NULL, game_master_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_B23BC7588B51FD88 (rule_set_id), INDEX IDX_B23BC758C1151A13 (game_master_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pnpgroup ADD CONSTRAINT FK_B23BC7588B51FD88 FOREIGN KEY (rule_set_id) REFERENCES rule_set (id)');
        $this->addSql('ALTER TABLE pnpgroup ADD CONSTRAINT FK_B23BC758C1151A13 FOREIGN KEY (game_master_id) REFERENCES pnpuser (id)');
        $this->addSql('ALTER TABLE character_data ADD pnp_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE character_data ADD CONSTRAINT FK_BA0FE7FB9DE9F7B4 FOREIGN KEY (pnp_group_id) REFERENCES pnpgroup (id)');
        $this->addSql('CREATE INDEX IDX_BA0FE7FB9DE9F7B4 ON character_data (pnp_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_data DROP FOREIGN KEY FK_BA0FE7FB9DE9F7B4');
        $this->addSql('ALTER TABLE pnpgroup DROP FOREIGN KEY FK_B23BC7588B51FD88');
        $this->addSql('ALTER TABLE pnpgroup DROP FOREIGN KEY FK_B23BC758C1151A13');
        $this->addSql('DROP TABLE pnpgroup');
        $this->addSql('DROP INDEX IDX_BA0FE7FB9DE9F7B4 ON character_data');
        $this->addSql('ALTER TABLE character_data DROP pnp_group_id');
    }
}
