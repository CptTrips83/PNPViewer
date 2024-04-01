<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401151149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pnpuser DROP FOREIGN KEY FK_16FF88E5C746E852');
        $this->addSql('DROP INDEX UNIQ_16FF88E5C746E852 ON pnpuser');
        $this->addSql('ALTER TABLE pnpuser DROP pw_reset_request_id');
        $this->addSql('ALTER TABLE pnpuser_pwreset_request DROP INDEX UNIQ_2B923D6EA76ED395, ADD INDEX IDX_2B923D6EA76ED395 (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pnpuser ADD pw_reset_request_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pnpuser ADD CONSTRAINT FK_16FF88E5C746E852 FOREIGN KEY (pw_reset_request_id) REFERENCES pnpuser_pwreset_request (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16FF88E5C746E852 ON pnpuser (pw_reset_request_id)');
        $this->addSql('ALTER TABLE pnpuser_pwreset_request DROP INDEX IDX_2B923D6EA76ED395, ADD UNIQUE INDEX UNIQ_2B923D6EA76ED395 (user_id)');
    }
}
