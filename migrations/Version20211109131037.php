<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211109131037 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assessment_learning_objective (assessment_id INT NOT NULL, learning_objective_id INT NOT NULL, INDEX IDX_FBB2BB8BDD3DD5F1 (assessment_id), INDEX IDX_FBB2BB8BD7BB5C64 (learning_objective_id), PRIMARY KEY(assessment_id, learning_objective_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assessment_learning_objective ADD CONSTRAINT FK_FBB2BB8BDD3DD5F1 FOREIGN KEY (assessment_id) REFERENCES assessment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assessment_learning_objective ADD CONSTRAINT FK_FBB2BB8BD7BB5C64 FOREIGN KEY (learning_objective_id) REFERENCES learning_objective (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assessment DROP learning_objectives');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE assessment_learning_objective');
        $this->addSql('ALTER TABLE assessment ADD learning_objectives LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
    }
}
