<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180817114957 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE images_categories DROP FOREIGN KEY FK_8B556BE712469DE2');
        $this->addSql('ALTER TABLE images_categories DROP FOREIGN KEY FK_8B556BE73DA5256D');
        $this->addSql('ALTER TABLE images_categories ADD CONSTRAINT FK_8B556BE712469DE2 FOREIGN KEY (category_id) REFERENCES product_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE images_categories ADD CONSTRAINT FK_8B556BE73DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE images_products DROP FOREIGN KEY FK_109927303DA5256D');
        $this->addSql('ALTER TABLE images_products DROP FOREIGN KEY FK_109927304584665A');
        $this->addSql('ALTER TABLE images_products ADD CONSTRAINT FK_109927303DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE images_products ADD CONSTRAINT FK_109927304584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE images_categories DROP FOREIGN KEY FK_8B556BE712469DE2');
        $this->addSql('ALTER TABLE images_categories DROP FOREIGN KEY FK_8B556BE73DA5256D');
        $this->addSql('ALTER TABLE images_categories ADD CONSTRAINT FK_8B556BE712469DE2 FOREIGN KEY (category_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE images_categories ADD CONSTRAINT FK_8B556BE73DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE images_products DROP FOREIGN KEY FK_109927304584665A');
        $this->addSql('ALTER TABLE images_products DROP FOREIGN KEY FK_109927303DA5256D');
        $this->addSql('ALTER TABLE images_products ADD CONSTRAINT FK_109927304584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE images_products ADD CONSTRAINT FK_109927303DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
    }
}
