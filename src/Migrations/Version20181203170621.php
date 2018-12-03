<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181203170621 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company ADD next_customer_number INT NOT NULL, CHANGE next_offer_number next_offer_number INT NOT NULL, CHANGE next_invoice_number next_invoice_number INT NOT NULL');
        $this->addSql('UPDATE company c SET next_customer_number = (SELECT IFNULL(MAX(customer_number), 0) + 1 FROM customer WHERE company_id = c.id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company DROP next_customer_number, CHANGE next_offer_number next_offer_number INT DEFAULT NULL, CHANGE next_invoice_number next_invoice_number INT DEFAULT NULL');
    }
}
