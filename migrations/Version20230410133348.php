<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230410133348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание таблицы лабораторных работ';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE lab_work (id INT AUTO_INCREMENT NOT NULL, system_name VARCHAR(40) NOT NULL, visible_name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_42D26C1B4FEFCDF0 (system_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE lab_work');
    }
}
