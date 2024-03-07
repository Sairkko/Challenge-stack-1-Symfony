<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307091400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_student_group (event_id INT NOT NULL, student_group_id INT NOT NULL, INDEX IDX_C1AE8DF471F7E88B (event_id), INDEX IDX_C1AE8DF44DDF95DC (student_group_id), PRIMARY KEY(event_id, student_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_student_group ADD CONSTRAINT FK_C1AE8DF471F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_student_group ADD CONSTRAINT FK_C1AE8DF44DDF95DC FOREIGN KEY (student_group_id) REFERENCES student_group (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_student_group DROP FOREIGN KEY FK_C1AE8DF471F7E88B');
        $this->addSql('ALTER TABLE event_student_group DROP FOREIGN KEY FK_C1AE8DF44DDF95DC');
        $this->addSql('DROP TABLE event_student_group');
    }
}
