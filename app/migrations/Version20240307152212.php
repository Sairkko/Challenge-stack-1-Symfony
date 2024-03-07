<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307152212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE student_group_module (student_group_id INT NOT NULL, module_id INT NOT NULL, INDEX IDX_467C4F4F4DDF95DC (student_group_id), INDEX IDX_467C4F4FAFC2B591 (module_id), PRIMARY KEY(student_group_id, module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_module (test_id INT NOT NULL, module_id INT NOT NULL, INDEX IDX_9BFD21061E5D0459 (test_id), INDEX IDX_9BFD2106AFC2B591 (module_id), PRIMARY KEY(test_id, module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_student_group (test_id INT NOT NULL, student_group_id INT NOT NULL, INDEX IDX_C3028B141E5D0459 (test_id), INDEX IDX_C3028B144DDF95DC (student_group_id), PRIMARY KEY(test_id, student_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE student_group_module ADD CONSTRAINT FK_467C4F4F4DDF95DC FOREIGN KEY (student_group_id) REFERENCES student_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_group_module ADD CONSTRAINT FK_467C4F4FAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE test_module ADD CONSTRAINT FK_9BFD21061E5D0459 FOREIGN KEY (test_id) REFERENCES test (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE test_module ADD CONSTRAINT FK_9BFD2106AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE test_student_group ADD CONSTRAINT FK_C3028B141E5D0459 FOREIGN KEY (test_id) REFERENCES test (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE test_student_group ADD CONSTRAINT FK_C3028B144DDF95DC FOREIGN KEY (student_group_id) REFERENCES student_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student DROP profil_picture');
        $this->addSql('ALTER TABLE test ADD type TINYTEXT DEFAULT NULL, ADD start_date DATETIME DEFAULT NULL, ADD end_date DATETIME DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student_group_module DROP FOREIGN KEY FK_467C4F4F4DDF95DC');
        $this->addSql('ALTER TABLE student_group_module DROP FOREIGN KEY FK_467C4F4FAFC2B591');
        $this->addSql('ALTER TABLE test_module DROP FOREIGN KEY FK_9BFD21061E5D0459');
        $this->addSql('ALTER TABLE test_module DROP FOREIGN KEY FK_9BFD2106AFC2B591');
        $this->addSql('ALTER TABLE test_student_group DROP FOREIGN KEY FK_C3028B141E5D0459');
        $this->addSql('ALTER TABLE test_student_group DROP FOREIGN KEY FK_C3028B144DDF95DC');
        $this->addSql('DROP TABLE student_group_module');
        $this->addSql('DROP TABLE test_module');
        $this->addSql('DROP TABLE test_student_group');
        $this->addSql('ALTER TABLE test DROP type, DROP start_date, DROP end_date, CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student ADD profil_picture LONGTEXT DEFAULT NULL');
    }
}
