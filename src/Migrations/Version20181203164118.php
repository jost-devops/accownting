<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181203164118 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company ADD next_offer_number INT DEFAULT NULL, ADD next_invoice_number INT DEFAULT NULL');

        $this->addSql('UPDATE company c SET next_invoice_number = (SELECT IFNULL(MAX(invoice_number), 0) + 1 FROM invoice WHERE company_id = c.id)');
        $this->addSql('UPDATE company c SET next_offer_number = (SELECT IFNULL(MAX(offer_number), 0) + 1 FROM offer WHERE company_id = c.id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company DROP next_offer_number, DROP next_invoice_number');
    }
}
