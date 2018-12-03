<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181203154827 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice_item RENAME INDEX idx_f1f9275b2989f1fd TO IDX_1DDE477B2989F1FD');
        $this->addSql('ALTER TABLE invoice_item RENAME INDEX idx_f1f9275bf8bd700d TO IDX_1DDE477BF8BD700D');
        $this->addSql('ALTER TABLE invoice_item RENAME INDEX idx_f1f9275b43897540 TO IDX_1DDE477B43897540');
        $this->addSql('ALTER TABLE invoice_item RENAME INDEX idx_f1f9275bb03a8386 TO IDX_1DDE477BB03A8386');
        $this->addSql('ALTER TABLE invoice_item RENAME INDEX idx_f1f9275b896dbbde TO IDX_1DDE477B896DBBDE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice_item RENAME INDEX idx_1dde477b2989f1fd TO IDX_F1F9275B2989F1FD');
        $this->addSql('ALTER TABLE invoice_item RENAME INDEX idx_1dde477bf8bd700d TO IDX_F1F9275BF8BD700D');
        $this->addSql('ALTER TABLE invoice_item RENAME INDEX idx_1dde477bb03a8386 TO IDX_F1F9275BB03A8386');
        $this->addSql('ALTER TABLE invoice_item RENAME INDEX idx_1dde477b896dbbde TO IDX_F1F9275B896DBBDE');
        $this->addSql('ALTER TABLE invoice_item RENAME INDEX idx_1dde477b43897540 TO IDX_F1F9275B43897540');
    }
}
