<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161107133436 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE post_bookmark (id INT AUTO_INCREMENT NOT NULL, user INT DEFAULT NULL, bookmarked TINYINT(1) DEFAULT \'1\' NOT NULL, date_created DATETIME NOT NULL, date_modified DATETIME NOT NULL, INDEX IDX_658F1BBC8D93D649 (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_like (id INT AUTO_INCREMENT NOT NULL, user INT DEFAULT NULL, bookmarked TINYINT(1) DEFAULT \'1\' NOT NULL, date_created DATETIME NOT NULL, date_modified DATETIME NOT NULL, INDEX IDX_653627B88D93D649 (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_bookmark ADD CONSTRAINT FK_658F1BBC8D93D649 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_like ADD CONSTRAINT FK_653627B88D93D649 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE blog_posts CHANGE header_image header_image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD password VARCHAR(100) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE post_bookmark');
        $this->addSql('DROP TABLE post_like');
        $this->addSql('ALTER TABLE blog_posts CHANGE header_image header_image VARCHAR(100) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE user DROP password');
    }
}
