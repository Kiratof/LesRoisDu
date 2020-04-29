<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200429071447 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cases ADD numero INT NOT NULL');
        $this->addSql('ALTER TABLE partie DROP FOREIGN KEY FK_59B1F3D2D3AD62E');
        $this->addSql('ALTER TABLE partie ADD est_lance TINYINT(1) NOT NULL, ADD derniere_modification DATETIME NOT NULL, ADD date_rejoins DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3D2D3AD62E FOREIGN KEY (plateau_de_jeu_id) REFERENCES plateau_en_jeu (id)');
        $this->addSql('ALTER TABLE pion ADD numero_joueur INT NOT NULL');
        $this->addSql('ALTER TABLE plateau ADD nb_cases INT NOT NULL, ADD code VARCHAR(5) NOT NULL, ADD derniere_modification DATETIME NOT NULL');
        $this->addSql('ALTER TABLE plateau_en_jeu ADD nb_cases INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD adresse_email VARCHAR(180) NOT NULL, DROP adresse_mail, DROP nom, DROP prenom, CHANGE mot_de_passe mot_de_passe VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B388D20D42 ON utilisateur (adresse_email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B386CC499D ON utilisateur (pseudo)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cases DROP numero');
        $this->addSql('ALTER TABLE partie DROP FOREIGN KEY FK_59B1F3D2D3AD62E');
        $this->addSql('ALTER TABLE partie DROP est_lance, DROP derniere_modification, DROP date_rejoins');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3D2D3AD62E FOREIGN KEY (plateau_de_jeu_id) REFERENCES plateau (id)');
        $this->addSql('ALTER TABLE pion DROP numero_joueur');
        $this->addSql('ALTER TABLE plateau DROP nb_cases, DROP code, DROP derniere_modification');
        $this->addSql('ALTER TABLE plateau_en_jeu DROP nb_cases');
        $this->addSql('DROP INDEX UNIQ_1D1C63B388D20D42 ON utilisateur');
        $this->addSql('DROP INDEX UNIQ_1D1C63B386CC499D ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur ADD adresse_mail VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD nom VARCHAR(60) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD prenom VARCHAR(60) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP adresse_email, CHANGE mot_de_passe mot_de_passe VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
