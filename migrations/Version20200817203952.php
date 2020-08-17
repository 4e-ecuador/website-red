<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200817203952 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `key` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, waypoint_id INT NOT NULL, count INT NOT NULL, INDEX IDX_8A90ABA9A76ED395 (user_id), INDEX IDX_8A90ABA97BB1FD97 (waypoint_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `key` ADD CONSTRAINT FK_8A90ABA9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `key` ADD CONSTRAINT FK_8A90ABA97BB1FD97 FOREIGN KEY (waypoint_id) REFERENCES waypoint (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `key`');
    }
}
