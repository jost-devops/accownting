<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181125120143 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE time_track_item DROP FOREIGN KEY FK_AD721F6166D1F9C');
        $this->addSql('ALTER TABLE time_track_item ADD CONSTRAINT FK_AD721F6166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE invoice_line_item DROP FOREIGN KEY FK_F1F9275B2989F1FD');
        $this->addSql('ALTER TABLE invoice_line_item ADD CONSTRAINT FK_F1F9275B2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice_line_item DROP FOREIGN KEY FK_F1F9275B2989F1FD');
        $this->addSql('ALTER TABLE invoice_line_item ADD CONSTRAINT FK_F1F9275B2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id)');
        $this->addSql('ALTER TABLE time_track_item DROP FOREIGN KEY FK_AD721F6166D1F9C');
        $this->addSql('ALTER TABLE time_track_item ADD CONSTRAINT FK_AD721F6166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }
}
