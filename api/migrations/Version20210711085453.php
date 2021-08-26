<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210711085453 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invitations (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, user_subscribe_id INT DEFAULT NULL, classroom_id INT DEFAULT NULL, accepted_by_id INT DEFAULT NULL, uuid VARCHAR(255) NOT NULL, accepted TINYINT(1) NOT NULL, INDEX IDX_232710AE7E3C61F9 (owner_id), UNIQUE INDEX UNIQ_232710AE47E63B73 (user_subscribe_id), INDEX IDX_232710AE6278D5A8 (classroom_id), UNIQUE INDEX UNIQ_232710AE20F699D9 (accepted_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invitations ADD CONSTRAINT FK_232710AE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invitations ADD CONSTRAINT FK_232710AE47E63B73 FOREIGN KEY (user_subscribe_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invitations ADD CONSTRAINT FK_232710AE6278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE invitations ADD CONSTRAINT FK_232710AE20F699D9 FOREIGN KEY (accepted_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE invitations');
    }
}
