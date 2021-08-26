<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210801071553 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE upload_file DROP FOREIGN KEY FK_81BB169CAF882FA');
        $this->addSql('DROP INDEX UNIQ_81BB169CAF882FA ON upload_file');
        $this->addSql('ALTER TABLE upload_file DROP delivrable_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE upload_file ADD delivrable_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE upload_file ADD CONSTRAINT FK_81BB169CAF882FA FOREIGN KEY (delivrable_id) REFERENCES delivrable (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81BB169CAF882FA ON upload_file (delivrable_id)');
    }
}
