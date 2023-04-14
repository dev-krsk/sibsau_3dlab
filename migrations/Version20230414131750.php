<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414131750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание таблицы договоров и связующей таблицы договоров и лабораторных работ';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE content_contract (id INT AUTO_INCREMENT NOT NULL, contract_id INT NOT NULL, lab_work_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', removed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BB38C2B62576E0FD (contract_id), INDEX IDX_BB38C2B6D7A71EB0 (lab_work_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', removed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, INDEX IDX_E98F2859A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE content_contract ADD CONSTRAINT FK_BB38C2B62576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('ALTER TABLE content_contract ADD CONSTRAINT FK_BB38C2B6D7A71EB0 FOREIGN KEY (lab_work_id) REFERENCES lab_work (id)');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE content_contract DROP FOREIGN KEY FK_BB38C2B62576E0FD');
        $this->addSql('ALTER TABLE content_contract DROP FOREIGN KEY FK_BB38C2B6D7A71EB0');
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F2859A76ED395');
        $this->addSql('DROP TABLE content_contract');
        $this->addSql('DROP TABLE contract');
    }
}
