<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161104024920 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog_posts ADD author INT DEFAULT NULL, DROP author_id');
        $this->addSql('ALTER TABLE blog_posts ADD CONSTRAINT FK_78B2F932BDAFD8C8 FOREIGN KEY (author) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_78B2F932BDAFD8C8 ON blog_posts (author)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog_posts DROP FOREIGN KEY FK_78B2F932BDAFD8C8');
        $this->addSql('DROP INDEX IDX_78B2F932BDAFD8C8 ON blog_posts');
        $this->addSql('ALTER TABLE blog_posts ADD author_id INT NOT NULL, DROP author');
    }
}
