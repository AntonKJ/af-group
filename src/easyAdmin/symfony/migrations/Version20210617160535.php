<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210617160535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE news_rubrics (news_id INT NOT NULL, rubrics_id INT NOT NULL, INDEX IDX_88E3A9FB5A459A0 (news_id), INDEX IDX_88E3A9F53D89DD2 (rubrics_id), PRIMARY KEY(news_id, rubrics_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE news_rubrics ADD CONSTRAINT FK_88E3A9FB5A459A0 FOREIGN KEY (news_id) REFERENCES news (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE news_rubrics ADD CONSTRAINT FK_88E3A9F53D89DD2 FOREIGN KEY (rubrics_id) REFERENCES rubrics (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE news CHANGE publish_date publish_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE rubrics DROP FOREIGN KEY FK_5F6A2679B5A459A0');
        $this->addSql('DROP INDEX IDX_5F6A2679B5A459A0 ON rubrics');
        $this->addSql('ALTER TABLE rubrics DROP news_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE news_rubrics');
        $this->addSql('ALTER TABLE news CHANGE publish_date publish_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE rubrics ADD news_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rubrics ADD CONSTRAINT FK_5F6A2679B5A459A0 FOREIGN KEY (news_id) REFERENCES news (id)');
        $this->addSql('CREATE INDEX IDX_5F6A2679B5A459A0 ON rubrics (news_id)');
    }
}
