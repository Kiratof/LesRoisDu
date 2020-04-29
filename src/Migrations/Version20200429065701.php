<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200429065701 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cases (id INT AUTO_INCREMENT NOT NULL, plateau_en_jeu_id INT DEFAULT NULL, plateau_id INT DEFAULT NULL, descriptif_defi VARCHAR(100) NOT NULL, consignes LONGTEXT DEFAULT NULL, code_validation VARCHAR(5) DEFAULT NULL, numero INT NOT NULL, INDEX IDX_1C1B038B672BDE3B (plateau_en_jeu_id), INDEX IDX_1C1B038B927847DB (plateau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partie (id INT AUTO_INCREMENT NOT NULL, plateau_id INT DEFAULT NULL, plateau_de_jeu_id INT DEFAULT NULL, createur_id INT NOT NULL, nom VARCHAR(60) NOT NULL, description LONGTEXT DEFAULT NULL, code VARCHAR(5) NOT NULL, nb_plateaux SMALLINT NOT NULL, nb_pion_par_plateau SMALLINT NOT NULL, nb_faces_de SMALLINT NOT NULL, est_lance TINYINT(1) NOT NULL, derniere_modification DATETIME NOT NULL, date_rejoins DATETIME DEFAULT NULL, INDEX IDX_59B1F3D927847DB (plateau_id), UNIQUE INDEX UNIQ_59B1F3D2D3AD62E (plateau_de_jeu_id), INDEX IDX_59B1F3D73A201E5 (createur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partie_utilisateur (partie_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_87F5E0FBE075F7A4 (partie_id), INDEX IDX_87F5E0FBFB88E14F (utilisateur_id), PRIMARY KEY(partie_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pion (id INT AUTO_INCREMENT NOT NULL, plateau_en_jeu_id INT NOT NULL, nom VARCHAR(20) NOT NULL, couleur VARCHAR(10) NOT NULL, avancement_plateau INT NOT NULL, numero_joueur INT NOT NULL, INDEX IDX_4512B418672BDE3B (plateau_en_jeu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plateau (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(60) NOT NULL, description LONGTEXT DEFAULT NULL, niveau_difficulte VARCHAR(20) NOT NULL, nb_cases INT NOT NULL, code VARCHAR(5) NOT NULL, derniere_modification DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plateau_utilisateur (plateau_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_5924F752927847DB (plateau_id), INDEX IDX_5924F752FB88E14F (utilisateur_id), PRIMARY KEY(plateau_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plateau_en_jeu (id INT AUTO_INCREMENT NOT NULL, joueur_id INT DEFAULT NULL, nom VARCHAR(60) NOT NULL, description LONGTEXT DEFAULT NULL, niveau_difficulte VARCHAR(20) NOT NULL, nb_cases INT NOT NULL, INDEX IDX_8F9AE8DEA9E2D76C (joueur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource (id INT AUTO_INCREMENT NOT NULL, cases_id INT NOT NULL, chemin LONGTEXT NOT NULL, INDEX IDX_939F45442A69AB62 (cases_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, pseudo VARCHAR(30) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, est_invite TINYINT(1) NOT NULL, avatar LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), UNIQUE INDEX UNIQ_1D1C63B386CC499D (pseudo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cases ADD CONSTRAINT FK_1C1B038B672BDE3B FOREIGN KEY (plateau_en_jeu_id) REFERENCES plateau_en_jeu (id)');
        $this->addSql('ALTER TABLE cases ADD CONSTRAINT FK_1C1B038B927847DB FOREIGN KEY (plateau_id) REFERENCES plateau (id)');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3D927847DB FOREIGN KEY (plateau_id) REFERENCES plateau (id)');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3D2D3AD62E FOREIGN KEY (plateau_de_jeu_id) REFERENCES plateau_en_jeu (id)');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3D73A201E5 FOREIGN KEY (createur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE partie_utilisateur ADD CONSTRAINT FK_87F5E0FBE075F7A4 FOREIGN KEY (partie_id) REFERENCES partie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partie_utilisateur ADD CONSTRAINT FK_87F5E0FBFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pion ADD CONSTRAINT FK_4512B418672BDE3B FOREIGN KEY (plateau_en_jeu_id) REFERENCES plateau_en_jeu (id)');
        $this->addSql('ALTER TABLE plateau_utilisateur ADD CONSTRAINT FK_5924F752927847DB FOREIGN KEY (plateau_id) REFERENCES plateau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plateau_utilisateur ADD CONSTRAINT FK_5924F752FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plateau_en_jeu ADD CONSTRAINT FK_8F9AE8DEA9E2D76C FOREIGN KEY (joueur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F45442A69AB62 FOREIGN KEY (cases_id) REFERENCES cases (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F45442A69AB62');
        $this->addSql('ALTER TABLE partie_utilisateur DROP FOREIGN KEY FK_87F5E0FBE075F7A4');
        $this->addSql('ALTER TABLE cases DROP FOREIGN KEY FK_1C1B038B927847DB');
        $this->addSql('ALTER TABLE partie DROP FOREIGN KEY FK_59B1F3D927847DB');
        $this->addSql('ALTER TABLE plateau_utilisateur DROP FOREIGN KEY FK_5924F752927847DB');
        $this->addSql('ALTER TABLE cases DROP FOREIGN KEY FK_1C1B038B672BDE3B');
        $this->addSql('ALTER TABLE partie DROP FOREIGN KEY FK_59B1F3D2D3AD62E');
        $this->addSql('ALTER TABLE pion DROP FOREIGN KEY FK_4512B418672BDE3B');
        $this->addSql('ALTER TABLE partie DROP FOREIGN KEY FK_59B1F3D73A201E5');
        $this->addSql('ALTER TABLE partie_utilisateur DROP FOREIGN KEY FK_87F5E0FBFB88E14F');
        $this->addSql('ALTER TABLE plateau_utilisateur DROP FOREIGN KEY FK_5924F752FB88E14F');
        $this->addSql('ALTER TABLE plateau_en_jeu DROP FOREIGN KEY FK_8F9AE8DEA9E2D76C');
        $this->addSql('DROP TABLE cases');
        $this->addSql('DROP TABLE partie');
        $this->addSql('DROP TABLE partie_utilisateur');
        $this->addSql('DROP TABLE pion');
        $this->addSql('DROP TABLE plateau');
        $this->addSql('DROP TABLE plateau_utilisateur');
        $this->addSql('DROP TABLE plateau_en_jeu');
        $this->addSql('DROP TABLE ressource');
        $this->addSql('DROP TABLE utilisateur');
    }
}
