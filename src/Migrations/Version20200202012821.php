<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200202012821 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL, INDEX IDX_9474526C9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE follower (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, follower_id_id INT DEFAULT NULL, date_created DATETIME DEFAULT NULL, date_updated DATETIME DEFAULT NULL, INDEX IDX_B9D609469D86650F (user_id_id), UNIQUE INDEX UNIQ_B9D60946E8DDDA11 (follower_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE following (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, following_id_id INT DEFAULT NULL, date_created DATETIME DEFAULT NULL, date_updated DATETIME DEFAULT NULL, INDEX IDX_71BF8DE39D86650F (user_id_id), UNIQUE INDEX UNIQ_71BF8DE33CF8336F (following_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hashtag (id INT AUTO_INCREMENT NOT NULL, photo_id_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL, INDEX IDX_5AB52A61C51599E0 (photo_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE likes (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, photo_id_id INT DEFAULT NULL, date_created DATETIME DEFAULT NULL, date_updated DATETIME DEFAULT NULL, INDEX IDX_49CA4E7D9D86650F (user_id_id), INDEX IDX_49CA4E7DC51599E0 (photo_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo_comment (id INT AUTO_INCREMENT NOT NULL, photo_id_id INT DEFAULT NULL, date_created DATETIME DEFAULT NULL, date_updated DATETIME DEFAULT NULL, INDEX IDX_A19445E1C51599E0 (photo_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo_hashtag (id INT AUTO_INCREMENT NOT NULL, hashtag_id_id INT DEFAULT NULL, photo_id_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_6F553DECF6228C5F (hashtag_id_id), INDEX IDX_6F553DECC51599E0 (photo_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photos (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, caption LONGTEXT DEFAULT NULL, image_path VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, date_created DATETIME DEFAULT NULL, date_updated DATETIME DEFAULT NULL, INDEX IDX_876E0D99D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saved_photos (id INT AUTO_INCREMENT NOT NULL, photo_id_id INT DEFAULT NULL, date_created DATETIME DEFAULT NULL, date_updated DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6C709BC2C51599E0 (photo_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE follower ADD CONSTRAINT FK_B9D609469D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE follower ADD CONSTRAINT FK_B9D60946E8DDDA11 FOREIGN KEY (follower_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE39D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE33CF8336F FOREIGN KEY (following_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE hashtag ADD CONSTRAINT FK_5AB52A61C51599E0 FOREIGN KEY (photo_id_id) REFERENCES photos (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DC51599E0 FOREIGN KEY (photo_id_id) REFERENCES photos (id)');
        $this->addSql('ALTER TABLE photo_comment ADD CONSTRAINT FK_A19445E1C51599E0 FOREIGN KEY (photo_id_id) REFERENCES photos (id)');
        $this->addSql('ALTER TABLE photo_hashtag ADD CONSTRAINT FK_6F553DECF6228C5F FOREIGN KEY (hashtag_id_id) REFERENCES hashtag (id)');
        $this->addSql('ALTER TABLE photo_hashtag ADD CONSTRAINT FK_6F553DECC51599E0 FOREIGN KEY (photo_id_id) REFERENCES photos (id)');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D99D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_photos ADD CONSTRAINT FK_6C709BC2C51599E0 FOREIGN KEY (photo_id_id) REFERENCES photos (id)');
        $this->addSql('ALTER TABLE user ADD saved_photos_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64936DAA59F FOREIGN KEY (saved_photos_id) REFERENCES saved_photos (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64936DAA59F ON user (saved_photos_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE photo_hashtag DROP FOREIGN KEY FK_6F553DECF6228C5F');
        $this->addSql('ALTER TABLE hashtag DROP FOREIGN KEY FK_5AB52A61C51599E0');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DC51599E0');
        $this->addSql('ALTER TABLE photo_comment DROP FOREIGN KEY FK_A19445E1C51599E0');
        $this->addSql('ALTER TABLE photo_hashtag DROP FOREIGN KEY FK_6F553DECC51599E0');
        $this->addSql('ALTER TABLE saved_photos DROP FOREIGN KEY FK_6C709BC2C51599E0');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64936DAA59F');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE follower');
        $this->addSql('DROP TABLE following');
        $this->addSql('DROP TABLE hashtag');
        $this->addSql('DROP TABLE likes');
        $this->addSql('DROP TABLE photo_comment');
        $this->addSql('DROP TABLE photo_hashtag');
        $this->addSql('DROP TABLE photos');
        $this->addSql('DROP TABLE saved_photos');
        $this->addSql('DROP INDEX IDX_8D93D64936DAA59F ON user');
        $this->addSql('ALTER TABLE user DROP saved_photos_id');
    }
}
