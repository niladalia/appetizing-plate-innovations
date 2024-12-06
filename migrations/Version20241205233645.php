<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241205233645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE items (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql("CREATE TABLE `orders` (id VARCHAR(36) NOT NULL, status ENUM('received', 'in_preparation', 'ready_to_serve') DEFAULT 'received' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4");
        $this->addSql('CREATE TABLE order_item (id VARCHAR(36) NOT NULL, order_id VARCHAR(36) NOT NULL, item_id VARCHAR(36) NOT NULL, quantity INT DEFAULT NULL, subtotal DOUBLE PRECISION DEFAULT NULL,  PRIMARY KEY(id), INDEX IDX_52EA1F098D9F6D38 (order_id), INDEX IDX_52EA1F09126F525E (item_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES `orders` (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09126F525E FOREIGN KEY (item_id) REFERENCES items (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F098D9F6D38');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09126F525E');
        $this->addSql('DROP TABLE items');
        $this->addSql('DROP TABLE `orders`');
        $this->addSql('DROP TABLE order_item');
    }
}
