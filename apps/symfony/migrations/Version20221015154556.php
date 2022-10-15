<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221015154556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `activation_tokens` (uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', token VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C1DFC3595F37A13B (token), UNIQUE INDEX UNIQ_C1DFC359A76ED395 (user_id), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `password_reset_tokens` (uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', token VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3967A2165F37A13B (token), UNIQUE INDEX UNIQ_3967A216A76ED395 (user_id), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `trick_categories` (uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B37BD6F55E237E06 (name), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `trick_comments` (uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', trick_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', author_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', content TEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3A4D12959FCC6316 (trick_uuid), INDEX IDX_3A4D12953590D879 (author_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `trick_images` (uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', trick_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', path VARCHAR(255) NOT NULL, alt VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_BB0A3F77B548B0F (path), INDEX IDX_BB0A3F779FCC6316 (trick_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `trick_thumbnails` (uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', path VARCHAR(255) NOT NULL, alt VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_DB2C4FFDB548B0F (path), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `trick_videos` (uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', trick_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', url VARCHAR(255) NOT NULL, INDEX IDX_72BFE52F9FCC6316 (trick_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `tricks` (uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', category_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', thumbnail_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_E1D902C15E237E06 (name), UNIQUE INDEX UNIQ_E1D902C1989D9B62 (slug), INDEX IDX_E1D902C15AE42AE1 (category_uuid), UNIQUE INDEX UNIQ_E1D902C127B35E98 (thumbnail_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `users` (uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `activation_tokens` ADD CONSTRAINT FK_C1DFC359A76ED395 FOREIGN KEY (user_id) REFERENCES `users` (uuid)');
        $this->addSql('ALTER TABLE `password_reset_tokens` ADD CONSTRAINT FK_3967A216A76ED395 FOREIGN KEY (user_id) REFERENCES `users` (uuid)');
        $this->addSql('ALTER TABLE `trick_comments` ADD CONSTRAINT FK_3A4D12959FCC6316 FOREIGN KEY (trick_uuid) REFERENCES `tricks` (uuid)');
        $this->addSql('ALTER TABLE `trick_comments` ADD CONSTRAINT FK_3A4D12953590D879 FOREIGN KEY (author_uuid) REFERENCES `users` (uuid)');
        $this->addSql('ALTER TABLE `trick_images` ADD CONSTRAINT FK_BB0A3F779FCC6316 FOREIGN KEY (trick_uuid) REFERENCES `tricks` (uuid)');
        $this->addSql('ALTER TABLE `trick_videos` ADD CONSTRAINT FK_72BFE52F9FCC6316 FOREIGN KEY (trick_uuid) REFERENCES `tricks` (uuid)');
        $this->addSql('ALTER TABLE `tricks` ADD CONSTRAINT FK_E1D902C15AE42AE1 FOREIGN KEY (category_uuid) REFERENCES `trick_categories` (uuid)');
        $this->addSql('ALTER TABLE `tricks` ADD CONSTRAINT FK_E1D902C127B35E98 FOREIGN KEY (thumbnail_uuid) REFERENCES `trick_thumbnails` (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `activation_tokens` DROP FOREIGN KEY FK_C1DFC359A76ED395');
        $this->addSql('ALTER TABLE `password_reset_tokens` DROP FOREIGN KEY FK_3967A216A76ED395');
        $this->addSql('ALTER TABLE `trick_comments` DROP FOREIGN KEY FK_3A4D12959FCC6316');
        $this->addSql('ALTER TABLE `trick_comments` DROP FOREIGN KEY FK_3A4D12953590D879');
        $this->addSql('ALTER TABLE `trick_images` DROP FOREIGN KEY FK_BB0A3F779FCC6316');
        $this->addSql('ALTER TABLE `trick_videos` DROP FOREIGN KEY FK_72BFE52F9FCC6316');
        $this->addSql('ALTER TABLE `tricks` DROP FOREIGN KEY FK_E1D902C15AE42AE1');
        $this->addSql('ALTER TABLE `tricks` DROP FOREIGN KEY FK_E1D902C127B35E98');
        $this->addSql('DROP TABLE `activation_tokens`');
        $this->addSql('DROP TABLE `password_reset_tokens`');
        $this->addSql('DROP TABLE `trick_categories`');
        $this->addSql('DROP TABLE `trick_comments`');
        $this->addSql('DROP TABLE `trick_images`');
        $this->addSql('DROP TABLE `trick_thumbnails`');
        $this->addSql('DROP TABLE `trick_videos`');
        $this->addSql('DROP TABLE `tricks`');
        $this->addSql('DROP TABLE `users`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
