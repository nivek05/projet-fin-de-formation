<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210215160406 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP INDEX UNIQ_42C8495531528CB4, ADD INDEX IDX_42C8495531528CB4 (disponibility_id)');
        $this->addSql('ALTER TABLE reservation CHANGE disponibility_id disponibility_id INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP INDEX IDX_42C8495531528CB4, ADD UNIQUE INDEX UNIQ_42C8495531528CB4 (disponibility_id)');
        $this->addSql('ALTER TABLE reservation CHANGE disponibility_id disponibility_id INT DEFAULT NULL');
    }
}
