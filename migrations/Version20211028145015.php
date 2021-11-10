<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211028145015 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module ADD department VARCHAR(255) DEFAULT NULL, ADD sub_department VARCHAR(255) DEFAULT NULL, ADD code VARCHAR(20) DEFAULT NULL, ADD prerequiste VARCHAR(255) DEFAULT NULL, ADD assessment_mid_hours DOUBLE PRECISION DEFAULT NULL, ADD assessment_final_hours DOUBLE PRECISION DEFAULT NULL, ADD sync_elearning_hours DOUBLE PRECISION DEFAULT NULL, ADD async_elearning_hours DOUBLE PRECISION DEFAULT NULL, ADD self_study_hours DOUBLE PRECISION DEFAULT NULL, ADD projecthours DOUBLE PRECISION DEFAULT NULL, ADD other_hours DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module DROP department, DROP sub_department, DROP code, DROP prerequiste, DROP assessment_mid_hours, DROP assessment_final_hours, DROP sync_elearning_hours, DROP async_elearning_hours, DROP self_study_hours, DROP projecthours, DROP other_hours');
    }
}
