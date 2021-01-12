<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20210112103400 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE exceptions (id INT AUTO_INCREMENT NOT NULL, virtual_project_id INT DEFAULT NULL, assigned_user_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(2048) COMMENT \'(DC2Type:exception_status)\' NOT NULL, name VARCHAR(255) NOT NULL, stacktrace TEXT NOT NULL, INDEX IDX_28721B60E8EABC94 (virtual_project_id), INDEX IDX_28721B60ADF66B1A (assigned_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exceptions ADD CONSTRAINT FK_28721B60E8EABC94 FOREIGN KEY (virtual_project_id) REFERENCES virtual_projects (id)');
        $this->addSql('ALTER TABLE exceptions ADD CONSTRAINT FK_28721B60ADF66B1A FOREIGN KEY (assigned_user_id) REFERENCES users (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE exceptions');
    }
}
