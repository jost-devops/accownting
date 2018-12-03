<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181203145905 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE offer_item (id INT AUTO_INCREMENT NOT NULL, offer_id INT DEFAULT NULL, unit_id INT DEFAULT NULL, vat_rate_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, price_single DOUBLE PRECISION NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_E1E30B0953C674EE (offer_id), INDEX IDX_E1E30B09F8BD700D (unit_id), INDEX IDX_E1E30B0943897540 (vat_rate_id), INDEX IDX_E1E30B09B03A8386 (created_by_id), INDEX IDX_E1E30B09896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, customer_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, offer_number VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, offer_date DATE NOT NULL, country VARCHAR(255) DEFAULT \'de\' NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_29D6873E979B1AD6 (company_id), INDEX IDX_29D6873E9395C3F3 (customer_id), INDEX IDX_29D6873EB03A8386 (created_by_id), INDEX IDX_29D6873E896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer_item ADD CONSTRAINT FK_E1E30B0953C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_item ADD CONSTRAINT FK_E1E30B09F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id)');
        $this->addSql('ALTER TABLE offer_item ADD CONSTRAINT FK_E1E30B0943897540 FOREIGN KEY (vat_rate_id) REFERENCES vat_rate (id)');
        $this->addSql('ALTER TABLE offer_item ADD CONSTRAINT FK_E1E30B09B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offer_item ADD CONSTRAINT FK_E1E30B09896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE offer_item DROP FOREIGN KEY FK_E1E30B0953C674EE');
        $this->addSql('DROP TABLE offer_item');
        $this->addSql('DROP TABLE offer');
    }
}
