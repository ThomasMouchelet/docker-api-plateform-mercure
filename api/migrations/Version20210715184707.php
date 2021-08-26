<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210715184707 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivrable ADD file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE delivrable ADD CONSTRAINT FK_D628D77293CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D628D77293CB796C ON delivrable (file_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivrable DROP FOREIGN KEY FK_D628D77293CB796C');
        $this->addSql('DROP INDEX UNIQ_D628D77293CB796C ON delivrable');
        $this->addSql('ALTER TABLE delivrable DROP file_id');
    }
}
