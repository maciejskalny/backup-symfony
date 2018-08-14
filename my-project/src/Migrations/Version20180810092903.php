<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180810092903 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_category ADD main_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC7356E4873418 FOREIGN KEY (main_image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CDFC7356E4873418 ON product_category (main_image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC7356E4873418');
        $this->addSql('DROP INDEX UNIQ_CDFC7356E4873418 ON product_category');
        $this->addSql('ALTER TABLE product_category DROP main_image_id');
    }
}
