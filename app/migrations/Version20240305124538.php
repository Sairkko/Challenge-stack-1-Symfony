<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305124538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, id_teacher_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, start_datetime DATETIME NOT NULL, end_datetime DATETIME NOT NULL, color VARCHAR(255) NOT NULL, INDEX IDX_3BAE0AA7E40AFECA (id_teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesson (id INT AUTO_INCREMENT NOT NULL, id_module_id INT NOT NULL, id_teacher_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, goal VARCHAR(255) DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, INDEX IDX_F87474F32FF709B6 (id_module_id), INDEX IDX_F87474F3E40AFECA (id_teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesson_permission (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, id_teacher_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_C242628E40AFECA (id_teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, id_test_id INT NOT NULL, type TINYTEXT NOT NULL, question_text VARCHAR(255) NOT NULL, point INT DEFAULT NULL, INDEX IDX_B6F7494EC0C0AD29 (id_test_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_reponse (id INT AUTO_INCREMENT NOT NULL, id_question_id INT NOT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_516A0BDA6353B48 (id_question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE school (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_student_groupe_id INT NOT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B723AF3379F37AE5 (id_user_id), INDEX IDX_B723AF33EC1C9C1B (id_student_groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_group_school (student_group_id INT NOT NULL, school_id INT NOT NULL, INDEX IDX_B3C6B3DC4DDF95DC (student_group_id), INDEX IDX_B3C6B3DCC32A47EE (school_id), PRIMARY KEY(student_group_id, school_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_reponse (id INT AUTO_INCREMENT NOT NULL, id_student_id INT NOT NULL, id_question_id INT NOT NULL, value VARCHAR(255) DEFAULT NULL, is_correct_by_teacher TINYTEXT NOT NULL, UNIQUE INDEX UNIQ_B2806B3F6E1ECFCD (id_student_id), UNIQUE INDEX UNIQ_B2806B3F6353B48 (id_question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B0F6A6D579F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, id_teacher_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_D87F7E0CE40AFECA (id_teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7E40AFECA FOREIGN KEY (id_teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F32FF709B6 FOREIGN KEY (id_module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F3E40AFECA FOREIGN KEY (id_teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628E40AFECA FOREIGN KEY (id_teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EC0C0AD29 FOREIGN KEY (id_test_id) REFERENCES test (id)');
        $this->addSql('ALTER TABLE question_reponse ADD CONSTRAINT FK_516A0BDA6353B48 FOREIGN KEY (id_question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3379F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33EC1C9C1B FOREIGN KEY (id_student_groupe_id) REFERENCES student_group (id)');
        $this->addSql('ALTER TABLE student_group_school ADD CONSTRAINT FK_B3C6B3DC4DDF95DC FOREIGN KEY (student_group_id) REFERENCES student_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_group_school ADD CONSTRAINT FK_B3C6B3DCC32A47EE FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_reponse ADD CONSTRAINT FK_B2806B3F6E1ECFCD FOREIGN KEY (id_student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE student_reponse ADD CONSTRAINT FK_B2806B3F6353B48 FOREIGN KEY (id_question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D579F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0CE40AFECA FOREIGN KEY (id_teacher_id) REFERENCES teacher (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7E40AFECA');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F32FF709B6');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F3E40AFECA');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628E40AFECA');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EC0C0AD29');
        $this->addSql('ALTER TABLE question_reponse DROP FOREIGN KEY FK_516A0BDA6353B48');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3379F37AE5');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33EC1C9C1B');
        $this->addSql('ALTER TABLE student_group_school DROP FOREIGN KEY FK_B3C6B3DC4DDF95DC');
        $this->addSql('ALTER TABLE student_group_school DROP FOREIGN KEY FK_B3C6B3DCC32A47EE');
        $this->addSql('ALTER TABLE student_reponse DROP FOREIGN KEY FK_B2806B3F6E1ECFCD');
        $this->addSql('ALTER TABLE student_reponse DROP FOREIGN KEY FK_B2806B3F6353B48');
        $this->addSql('ALTER TABLE teacher DROP FOREIGN KEY FK_B0F6A6D579F37AE5');
        $this->addSql('ALTER TABLE test DROP FOREIGN KEY FK_D87F7E0CE40AFECA');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE lesson');
        $this->addSql('DROP TABLE lesson_permission');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_reponse');
        $this->addSql('DROP TABLE school');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE student_group');
        $this->addSql('DROP TABLE student_group_school');
        $this->addSql('DROP TABLE student_reponse');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE test');
    }
}
