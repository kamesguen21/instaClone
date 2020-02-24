<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200221235802 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE photos_hashtag (photos_id INT NOT NULL, hashtag_id INT NOT NULL, INDEX IDX_D904A521301EC62 (photos_id), INDEX IDX_D904A521FB34EF56 (hashtag_id), PRIMARY KEY(photos_id, hashtag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE photos_hashtag ADD CONSTRAINT FK_D904A521301EC62 FOREIGN KEY (photos_id) REFERENCES photos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photos_hashtag ADD CONSTRAINT FK_D904A521FB34EF56 FOREIGN KEY (hashtag_id) REFERENCES hashtag (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE photo_hashtag');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE photo_hashtag (id INT AUTO_INCREMENT NOT NULL, hashtag_id_id INT DEFAULT NULL, photo_id_id INT DEFAULT NULL, INDEX IDX_6F553DECC51599E0 (photo_id_id), UNIQUE INDEX UNIQ_6F553DECF6228C5F (hashtag_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE photo_hashtag ADD CONSTRAINT FK_6F553DECC51599E0 FOREIGN KEY (photo_id_id) REFERENCES photos (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE photo_hashtag ADD CONSTRAINT FK_6F553DECF6228C5F FOREIGN KEY (hashtag_id_id) REFERENCES hashtag (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE photos_hashtag');
    }
}
