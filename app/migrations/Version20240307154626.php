<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307154626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student_group ADD teacher_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE student_group ADD CONSTRAINT FK_E5F73D582EBB220A FOREIGN KEY (teacher_id_id) REFERENCES teacher (id)');
        $this->addSql('CREATE INDEX IDX_E5F73D582EBB220A ON student_group (teacher_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student_group DROP FOREIGN KEY FK_E5F73D582EBB220A');
        $this->addSql('DROP INDEX IDX_E5F73D582EBB220A ON student_group');
        $this->addSql('ALTER TABLE student_group DROP teacher_id_id');
    }
}
