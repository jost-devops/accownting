<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181124090759 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice_line_item ADD vat_rate_id INT DEFAULT NULL, DROP vat_rate');
        $this->addSql('ALTER TABLE invoice_line_item ADD CONSTRAINT FK_F1F9275B43897540 FOREIGN KEY (vat_rate_id) REFERENCES unit (id)');
        $this->addSql('CREATE INDEX IDX_F1F9275B43897540 ON invoice_line_item (vat_rate_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice_line_item DROP FOREIGN KEY FK_F1F9275B43897540');
        $this->addSql('DROP INDEX IDX_F1F9275B43897540 ON invoice_line_item');
        $this->addSql('ALTER TABLE invoice_line_item ADD vat_rate INT NOT NULL, DROP vat_rate_id');
    }
}
