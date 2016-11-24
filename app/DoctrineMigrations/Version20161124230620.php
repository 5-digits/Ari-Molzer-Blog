<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161124230620 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, author INT DEFAULT NULL, title VARCHAR(100) NOT NULL, subtitle VARCHAR(150) NOT NULL, slug VARCHAR(100) NOT NULL, header_image VARCHAR(255) NOT NULL, short_description TINYTEXT NOT NULL, body LONGTEXT NOT NULL, published TINYINT(1) DEFAULT \'0\' NOT NULL, date_created DATETIME NOT NULL, date_modified DATETIME NOT NULL, INDEX IDX_5A8A6C8DBDAFD8C8 (author), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DBDAFD8C8 FOREIGN KEY (author) REFERENCES user (id)');
        $this->addSql('DROP TABLE blog_posts');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE blog_posts (id INT AUTO_INCREMENT NOT NULL, author INT DEFAULT NULL, title VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, subtitle VARCHAR(150) NOT NULL COLLATE utf8_unicode_ci, slug VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, short_description LONGTEXT NOT NULL COLLATE utf8_unicode_ci, date_created DATETIME NOT NULL, date_modified DATETIME NOT NULL, header_image VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, body LONGTEXT NOT NULL COLLATE utf8_unicode_ci, published TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_78B2F932BDAFD8C8 (author), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_posts ADD CONSTRAINT FK_78B2F932BDAFD8C8 FOREIGN KEY (author) REFERENCES user (id)');
        $this->addSql('DROP TABLE post');
    }
}
