<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210711092517 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, invitation_id INT DEFAULT NULL, user_register_id INT DEFAULT NULL, accepted TINYINT(1) DEFAULT NULL, INDEX IDX_5E90F6D6A35D7AF0 (invitation_id), INDEX IDX_5E90F6D6E06D02EB (user_register_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, classroom_id INT DEFAULT NULL, uuid VARCHAR(255) NOT NULL, INDEX IDX_F11D61A27E3C61F9 (owner_id), INDEX IDX_F11D61A26278D5A8 (classroom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6A35D7AF0 FOREIGN KEY (invitation_id) REFERENCES invitation (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6E06D02EB FOREIGN KEY (user_register_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A27E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A26278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('DROP TABLE invitations');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6A35D7AF0');
        $this->addSql('CREATE TABLE invitations (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, user_subscribe_id INT DEFAULT NULL, classroom_id INT DEFAULT NULL, accepted_by_id INT DEFAULT NULL, uuid VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, accepted TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_232710AE20F699D9 (accepted_by_id), INDEX IDX_232710AE6278D5A8 (classroom_id), UNIQUE INDEX UNIQ_232710AE47E63B73 (user_subscribe_id), INDEX IDX_232710AE7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE invitations ADD CONSTRAINT FK_232710AE20F699D9 FOREIGN KEY (accepted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invitations ADD CONSTRAINT FK_232710AE47E63B73 FOREIGN KEY (user_subscribe_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invitations ADD CONSTRAINT FK_232710AE6278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE invitations ADD CONSTRAINT FK_232710AE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE inscription');
        $this->addSql('DROP TABLE invitation');
    }
}
