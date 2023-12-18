<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218150225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE contribution_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE invitation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE suggestion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contribution (id INT NOT NULL, of_event_id INT NOT NULL, of_profile_id INT NOT NULL, product VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EA351E15CD6C1E60 ON contribution (of_event_id)');
        $this->addSql('CREATE INDEX IDX_EA351E1525AED32D ON contribution (of_profile_id)');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, organizer_id INT NOT NULL, place VARCHAR(255) NOT NULL, description TEXT NOT NULL, starting_date DATE NOT NULL, end_date DATE NOT NULL, private BOOLEAN NOT NULL, private_place BOOLEAN NOT NULL, on_schedule BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7876C4DDA ON event (organizer_id)');
        $this->addSql('CREATE TABLE event_profile (event_id INT NOT NULL, profile_id INT NOT NULL, PRIMARY KEY(event_id, profile_id))');
        $this->addSql('CREATE INDEX IDX_40003A071F7E88B ON event_profile (event_id)');
        $this->addSql('CREATE INDEX IDX_40003A0CCFA12B8 ON event_profile (profile_id)');
        $this->addSql('CREATE TABLE invitation (id INT NOT NULL, for_event_id INT NOT NULL, to_profile_id INT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F11D61A234148199 ON invitation (for_event_id)');
        $this->addSql('CREATE INDEX IDX_F11D61A2EBC9F0C5 ON invitation (to_profile_id)');
        $this->addSql('CREATE TABLE profile (id INT NOT NULL, of_user_id INT NOT NULL, display_name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8157AA0F5A1B2224 ON profile (of_user_id)');
        $this->addSql('COMMENT ON COLUMN profile.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE suggestion (id INT NOT NULL, for_profile_id INT DEFAULT NULL, of_event_id INT NOT NULL, of_contribution_id INT DEFAULT NULL, is_taken BOOLEAN NOT NULL, product VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DD80F31B8060FE74 ON suggestion (for_profile_id)');
        $this->addSql('CREATE INDEX IDX_DD80F31BCD6C1E60 ON suggestion (of_event_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DD80F31BCD40B077 ON suggestion (of_contribution_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E15CD6C1E60 FOREIGN KEY (of_event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contribution ADD CONSTRAINT FK_EA351E1525AED32D FOREIGN KEY (of_profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7876C4DDA FOREIGN KEY (organizer_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_profile ADD CONSTRAINT FK_40003A071F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_profile ADD CONSTRAINT FK_40003A0CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A234148199 FOREIGN KEY (for_event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2EBC9F0C5 FOREIGN KEY (to_profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0F5A1B2224 FOREIGN KEY (of_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE suggestion ADD CONSTRAINT FK_DD80F31B8060FE74 FOREIGN KEY (for_profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE suggestion ADD CONSTRAINT FK_DD80F31BCD6C1E60 FOREIGN KEY (of_event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE suggestion ADD CONSTRAINT FK_DD80F31BCD40B077 FOREIGN KEY (of_contribution_id) REFERENCES contribution (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE contribution_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE event_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE invitation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE profile_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE suggestion_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE contribution DROP CONSTRAINT FK_EA351E15CD6C1E60');
        $this->addSql('ALTER TABLE contribution DROP CONSTRAINT FK_EA351E1525AED32D');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7876C4DDA');
        $this->addSql('ALTER TABLE event_profile DROP CONSTRAINT FK_40003A071F7E88B');
        $this->addSql('ALTER TABLE event_profile DROP CONSTRAINT FK_40003A0CCFA12B8');
        $this->addSql('ALTER TABLE invitation DROP CONSTRAINT FK_F11D61A234148199');
        $this->addSql('ALTER TABLE invitation DROP CONSTRAINT FK_F11D61A2EBC9F0C5');
        $this->addSql('ALTER TABLE profile DROP CONSTRAINT FK_8157AA0F5A1B2224');
        $this->addSql('ALTER TABLE suggestion DROP CONSTRAINT FK_DD80F31B8060FE74');
        $this->addSql('ALTER TABLE suggestion DROP CONSTRAINT FK_DD80F31BCD6C1E60');
        $this->addSql('ALTER TABLE suggestion DROP CONSTRAINT FK_DD80F31BCD40B077');
        $this->addSql('DROP TABLE contribution');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_profile');
        $this->addSql('DROP TABLE invitation');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE suggestion');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
