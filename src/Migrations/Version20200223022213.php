<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200223022213 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64936DAA59F');
        $this->addSql('CREATE TABLE user_photos (user_id INT NOT NULL, photos_id INT NOT NULL, INDEX IDX_6D24FBE4A76ED395 (user_id), INDEX IDX_6D24FBE4301EC62 (photos_id), PRIMARY KEY(user_id, photos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_photos ADD CONSTRAINT FK_6D24FBE4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_photos ADD CONSTRAINT FK_6D24FBE4301EC62 FOREIGN KEY (photos_id) REFERENCES photos (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE saved_photos');
        $this->addSql('DROP INDEX IDX_8D93D64936DAA59F ON user');
        $this->addSql('ALTER TABLE user DROP saved_photos_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE saved_photos (id INT AUTO_INCREMENT NOT NULL, photo_id_id INT DEFAULT NULL, date_created DATETIME DEFAULT NULL, date_updated DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6C709BC2C51599E0 (photo_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE saved_photos ADD CONSTRAINT FK_6C709BC2C51599E0 FOREIGN KEY (photo_id_id) REFERENCES photos (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE user_photos');
        $this->addSql('ALTER TABLE user ADD saved_photos_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64936DAA59F FOREIGN KEY (saved_photos_id) REFERENCES saved_photos (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D64936DAA59F ON user (saved_photos_id)');
    }
}
