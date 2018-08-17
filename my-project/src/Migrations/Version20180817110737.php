<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180817110737 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, main_image_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, add_date DATE NOT NULL, last_modified_date DATE NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), UNIQUE INDEX UNIQ_D34A04ADE4873418 (main_image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images_products (product_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_109927304584665A (product_id), UNIQUE INDEX UNIQ_109927303DA5256D (image_id), PRIMARY KEY(product_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (id INT AUTO_INCREMENT NOT NULL, main_image_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, add_date DATE NOT NULL, last_modified_date DATE NOT NULL, UNIQUE INDEX UNIQ_CDFC7356E4873418 (main_image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images_categories (category_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_8B556BE712469DE2 (category_id), UNIQUE INDEX UNIQ_8B556BE73DA5256D (image_id), PRIMARY KEY(category_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADE4873418 FOREIGN KEY (main_image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE images_products ADD CONSTRAINT FK_109927304584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE images_products ADD CONSTRAINT FK_109927303DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC7356E4873418 FOREIGN KEY (main_image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE images_categories ADD CONSTRAINT FK_8B556BE712469DE2 FOREIGN KEY (category_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE images_categories ADD CONSTRAINT FK_8B556BE73DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE images_products DROP FOREIGN KEY FK_109927304584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADE4873418');
        $this->addSql('ALTER TABLE images_products DROP FOREIGN KEY FK_109927303DA5256D');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC7356E4873418');
        $this->addSql('ALTER TABLE images_categories DROP FOREIGN KEY FK_8B556BE73DA5256D');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE images_categories DROP FOREIGN KEY FK_8B556BE712469DE2');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE images_products');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE images_categories');
    }
}
