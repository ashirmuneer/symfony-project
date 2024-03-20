<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240320193935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C8FABDD9F');
        $this->addSql('DROP INDEX idx_9474526c8fabdd9f ON comment');
        $this->addSql('CREATE INDEX IDX_9474526CDAE07E97 ON comment (blog_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C8FABDD9F FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE product ADD price INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD product_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE product');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CDAE07E97');
        $this->addSql('DROP INDEX idx_9474526cdae07e97 ON comment');
        $this->addSql('CREATE INDEX IDX_9474526C8FABDD9F ON comment (blog_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');

        $this->addSql('ALTER TABLE product DROP COLUMN price');
        $this->addSql('ALTER TABLE product DROP COLUMN product_name');
    }
}
