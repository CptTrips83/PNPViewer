<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220115932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pnpgroup_invite (id INT AUTO_INCREMENT NOT NULL, invited_user_id INT DEFAULT NULL, invite_group_id INT NOT NULL, invite_code VARCHAR(15) NOT NULL, invite_date DATETIME NOT NULL, invite_life_time VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', INDEX IDX_438C710EC58DAD6E (invited_user_id), INDEX IDX_438C710E449B18CE (invite_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pnpgroup_invite ADD CONSTRAINT FK_438C710EC58DAD6E FOREIGN KEY (invited_user_id) REFERENCES pnpuser (id)');
        $this->addSql('ALTER TABLE pnpgroup_invite ADD CONSTRAINT FK_438C710E449B18CE FOREIGN KEY (invite_group_id) REFERENCES pnpgroup (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pnpgroup_invite DROP FOREIGN KEY FK_438C710EC58DAD6E');
        $this->addSql('ALTER TABLE pnpgroup_invite DROP FOREIGN KEY FK_438C710E449B18CE');
        $this->addSql('DROP TABLE pnpgroup_invite');
    }
}
