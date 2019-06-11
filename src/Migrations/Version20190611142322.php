<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190611142322 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice_item ADD parent_item_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice_item ADD CONSTRAINT FK_1DDE477B60272618 FOREIGN KEY (parent_item_id) REFERENCES invoice_item (id)');
        $this->addSql('CREATE INDEX IDX_1DDE477B60272618 ON invoice_item (parent_item_id)');
        $this->addSql('ALTER TABLE offer_item ADD parent_item_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offer_item ADD CONSTRAINT FK_E1E30B0960272618 FOREIGN KEY (parent_item_id) REFERENCES offer_item (id)');
        $this->addSql('CREATE INDEX IDX_E1E30B0960272618 ON offer_item (parent_item_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice_item DROP FOREIGN KEY FK_1DDE477B60272618');
        $this->addSql('DROP INDEX IDX_1DDE477B60272618 ON invoice_item');
        $this->addSql('ALTER TABLE invoice_item DROP parent_item_id');
        $this->addSql('ALTER TABLE offer_item DROP FOREIGN KEY FK_E1E30B0960272618');
        $this->addSql('DROP INDEX IDX_E1E30B0960272618 ON offer_item');
        $this->addSql('ALTER TABLE offer_item DROP parent_item_id');
    }
}
