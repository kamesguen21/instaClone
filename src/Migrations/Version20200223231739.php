<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200223231739 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE follow (id INT AUTO_INCREMENT NOT NULL, follower_id INT NOT NULL, following_id INT NOT NULL, INDEX IDX_68344470AC24F853 (follower_id), INDEX IDX_683444701816E3A3 (following_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_68344470AC24F853 FOREIGN KEY (follower_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_683444701816E3A3 FOREIGN KEY (following_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE follower');
        $this->addSql('DROP TABLE following');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE follower (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, follower_id_id INT DEFAULT NULL, date_created DATETIME DEFAULT NULL, date_updated DATETIME DEFAULT NULL, INDEX IDX_B9D609469D86650F (user_id_id), UNIQUE INDEX UNIQ_B9D60946E8DDDA11 (follower_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE following (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, following_id_id INT DEFAULT NULL, date_created DATETIME DEFAULT NULL, date_updated DATETIME DEFAULT NULL, INDEX IDX_71BF8DE39D86650F (user_id_id), UNIQUE INDEX UNIQ_71BF8DE33CF8336F (following_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE follower ADD CONSTRAINT FK_B9D609469D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE follower ADD CONSTRAINT FK_B9D60946E8DDDA11 FOREIGN KEY (follower_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE33CF8336F FOREIGN KEY (following_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE39D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE follow');
    }
}
