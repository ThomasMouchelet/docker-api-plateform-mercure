<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210803055403 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE homework_file (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, homework_id INT DEFAULT NULL, uploaded_at DATETIME NOT NULL, file_url VARCHAR(255) DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, file_slug VARCHAR(255) DEFAULT NULL, INDEX IDX_3E993B4541807E1D (teacher_id), INDEX IDX_3E993B45B203DDE5 (homework_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE homework_file ADD CONSTRAINT FK_3E993B4541807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE homework_file ADD CONSTRAINT FK_3E993B45B203DDE5 FOREIGN KEY (homework_id) REFERENCES homework (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE homework_file');
    }
}
