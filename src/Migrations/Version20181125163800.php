<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181125163800 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE time_track_item ADD person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE time_track_item ADD CONSTRAINT FK_AD721F6217BBB47 FOREIGN KEY (person_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AD721F6217BBB47 ON time_track_item (person_id)');
        $this->addSql('ALTER TABLE user ADD last_used DATETIME DEFAULT NULL');
        $this->addSql('UPDATE time_track_item SET person_id = created_by_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE time_track_item DROP FOREIGN KEY FK_AD721F6217BBB47');
        $this->addSql('DROP INDEX IDX_AD721F6217BBB47 ON time_track_item');
        $this->addSql('ALTER TABLE time_track_item DROP person_id');
        $this->addSql('ALTER TABLE user DROP last_used');
    }
}
