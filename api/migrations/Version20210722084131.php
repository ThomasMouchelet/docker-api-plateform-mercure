<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210722084131 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivrable DROP FOREIGN KEY FK_D628D77293CB796C');
        $this->addSql('CREATE TABLE upload_file (id INT AUTO_INCREMENT NOT NULL, delivrable_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, path VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_81BB169CAF882FA (delivrable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE upload_file ADD CONSTRAINT FK_81BB169CAF882FA FOREIGN KEY (delivrable_id) REFERENCES delivrable (id)');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP INDEX UNIQ_D628D77293CB796C ON delivrable');
        $this->addSql('ALTER TABLE delivrable DROP file_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, subject_id INT DEFAULT NULL, homework_id INT DEFAULT NULL, uploaded_at DATETIME DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, path VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_8C9F361023EDC87 (subject_id), INDEX IDX_8C9F36107E3C61F9 (owner_id), INDEX IDX_8C9F3610B203DDE5 (homework_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F361023EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F36107E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610B203DDE5 FOREIGN KEY (homework_id) REFERENCES homework (id)');
        $this->addSql('DROP TABLE upload_file');
        $this->addSql('ALTER TABLE delivrable ADD file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE delivrable ADD CONSTRAINT FK_D628D77293CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D628D77293CB796C ON delivrable (file_id)');
    }
}
