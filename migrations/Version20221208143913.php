<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221208143913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, avancement INT NOT NULL, perso TINYINT(1) NOT NULL, INDEX IDX_527EDB2512469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_perso (task_id INT NOT NULL, user_id INT DEFAULT NULL, repetition INT NOT NULL, due_date DATETIME NOT NULL, date_debut DATETIME NOT NULL, INDEX IDX_7AC6E101A76ED395 (user_id), PRIMARY KEY(task_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2512469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE task_perso ADD CONSTRAINT FK_7AC6E1018DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE task_perso ADD CONSTRAINT FK_7AC6E101A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2512469DE2');
        $this->addSql('ALTER TABLE task_perso DROP FOREIGN KEY FK_7AC6E1018DB60186');
        $this->addSql('ALTER TABLE task_perso DROP FOREIGN KEY FK_7AC6E101A76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_perso');
    }
}
