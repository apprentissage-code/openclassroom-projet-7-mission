<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250227093047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE project ADD date_archivage TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE project ALTER id DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project DROP date_archivage');
        $this->addSql('CREATE SEQUENCE project_id_seq');
        $this->addSql('SELECT setval(\'project_id_seq\', (SELECT MAX(id) FROM project))');
        $this->addSql('ALTER TABLE project ALTER id SET DEFAULT nextval(\'project_id_seq\')');
        $this->addSql('CREATE SEQUENCE employee_id_seq');
        $this->addSql('SELECT setval(\'employee_id_seq\', (SELECT MAX(id) FROM employee))');
        $this->addSql('ALTER TABLE employee ALTER id SET DEFAULT nextval(\'employee_id_seq\')');
    }
}
