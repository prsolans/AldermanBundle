<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20130924103458 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE Alderman (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, party VARCHAR(255) NOT NULL, firstElected DATE NOT NULL, Ward INT DEFAULT NULL, INDEX IDX_5961A827695DF725 (Ward), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE Ward (id INT AUTO_INCREMENT NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE Alderman ADD CONSTRAINT FK_5961A827695DF725 FOREIGN KEY (Ward) REFERENCES Ward (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Alderman DROP FOREIGN KEY FK_5961A827695DF725");
        $this->addSql("DROP TABLE Alderman");
        $this->addSql("DROP TABLE Ward");
    }
}
