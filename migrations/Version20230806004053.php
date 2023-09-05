<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230806004053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, fk_user_id INT DEFAULT NULL, fk_logement_id INT DEFAULT NULL, valeur_etoile VARCHAR(255) NOT NULL, createdat VARCHAR(255) NOT NULL, INDEX IDX_8F91ABF05741EEB9 (fk_user_id), INDEX IDX_8F91ABF0315D5561 (fk_logement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_destination (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_hebergement (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_inspiration (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, nombre_sejour VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE code_promo (id INT AUTO_INCREMENT NOT NULL, fk_logement_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, pourcentage VARCHAR(255) NOT NULL, INDEX IDX_5C4683B7315D5561 (fk_logement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, fk_user_id INT DEFAULT NULL, fk_logement_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, created VARCHAR(255) DEFAULT NULL, INDEX IDX_67F068BC5741EEB9 (fk_user_id), INDEX IDX_67F068BC315D5561 (fk_logement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE logement (id INT AUTO_INCREMENT NOT NULL, fk_categorie_hebergement_id INT DEFAULT NULL, fk_categorie_destination_id INT DEFAULT NULL, fk_categorie_inspiration_id INT DEFAULT NULL, fk_user_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, image1 VARCHAR(255) DEFAULT NULL, image2 VARCHAR(255) DEFAULT NULL, image3 VARCHAR(255) DEFAULT NULL, image4 VARCHAR(255) DEFAULT NULL, montant VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, capacite_accueil VARCHAR(255) NOT NULL, disponible VARCHAR(255) NOT NULL, nombre_piece VARCHAR(255) NOT NULL, nombre_chambre VARCHAR(255) NOT NULL, created VARCHAR(255) DEFAULT NULL, statut VARCHAR(255) NOT NULL, hebergement_id INT NOT NULL, destination_id INT NOT NULL, inspiration_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F0FD4457430994BC (fk_categorie_hebergement_id), INDEX IDX_F0FD4457E1DEFA9A (fk_categorie_destination_id), INDEX IDX_F0FD44574BC0F785 (fk_categorie_inspiration_id), INDEX IDX_F0FD44575741EEB9 (fk_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE logement_option (id INT AUTO_INCREMENT NOT NULL, fk_logement_id INT DEFAULT NULL, fk_option_id INT DEFAULT NULL, option_valeur VARCHAR(255) NOT NULL, INDEX IDX_E7CCFA3B315D5561 (fk_logement_id), INDEX IDX_E7CCFA3BC69DCAFF (fk_option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, logement_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_5A8600B058ABF955 (logement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, fk_logement_id INT DEFAULT NULL, fk_user_id INT DEFAULT NULL, fk_code_promo_id INT DEFAULT NULL, date_depart VARCHAR(255) NOT NULL, date_arrive VARCHAR(255) NOT NULL, paiement VARCHAR(255) NOT NULL, confirme VARCHAR(255) NOT NULL, createdat VARCHAR(255) NOT NULL, user_insolite VARCHAR(255) NOT NULL, INDEX IDX_42C84955315D5561 (fk_logement_id), INDEX IDX_42C849555741EEB9 (fk_user_id), INDEX IDX_42C849554D0B7716 (fk_code_promo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, role_user VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, date_naissance VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, nom_rue VARCHAR(255) NOT NULL, numero_rue VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, active VARCHAR(255) NOT NULL, created VARCHAR(255) DEFAULT NULL, partenaire VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF05741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0315D5561 FOREIGN KEY (fk_logement_id) REFERENCES logement (id)');
        $this->addSql('ALTER TABLE code_promo ADD CONSTRAINT FK_5C4683B7315D5561 FOREIGN KEY (fk_logement_id) REFERENCES logement (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC5741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC315D5561 FOREIGN KEY (fk_logement_id) REFERENCES logement (id)');
        $this->addSql('ALTER TABLE logement ADD CONSTRAINT FK_F0FD4457430994BC FOREIGN KEY (fk_categorie_hebergement_id) REFERENCES categorie_hebergement (id)');
        $this->addSql('ALTER TABLE logement ADD CONSTRAINT FK_F0FD4457E1DEFA9A FOREIGN KEY (fk_categorie_destination_id) REFERENCES categorie_destination (id)');
        $this->addSql('ALTER TABLE logement ADD CONSTRAINT FK_F0FD44574BC0F785 FOREIGN KEY (fk_categorie_inspiration_id) REFERENCES categorie_inspiration (id)');
        $this->addSql('ALTER TABLE logement ADD CONSTRAINT FK_F0FD44575741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE logement_option ADD CONSTRAINT FK_E7CCFA3B315D5561 FOREIGN KEY (fk_logement_id) REFERENCES logement (id)');
        $this->addSql('ALTER TABLE logement_option ADD CONSTRAINT FK_E7CCFA3BC69DCAFF FOREIGN KEY (fk_option_id) REFERENCES `option` (id)');
        $this->addSql('ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B058ABF955 FOREIGN KEY (logement_id) REFERENCES logement (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955315D5561 FOREIGN KEY (fk_logement_id) REFERENCES logement (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849555741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849554D0B7716 FOREIGN KEY (fk_code_promo_id) REFERENCES code_promo (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF05741EEB9');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0315D5561');
        $this->addSql('ALTER TABLE code_promo DROP FOREIGN KEY FK_5C4683B7315D5561');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC5741EEB9');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC315D5561');
        $this->addSql('ALTER TABLE logement DROP FOREIGN KEY FK_F0FD4457430994BC');
        $this->addSql('ALTER TABLE logement DROP FOREIGN KEY FK_F0FD4457E1DEFA9A');
        $this->addSql('ALTER TABLE logement DROP FOREIGN KEY FK_F0FD44574BC0F785');
        $this->addSql('ALTER TABLE logement DROP FOREIGN KEY FK_F0FD44575741EEB9');
        $this->addSql('ALTER TABLE logement_option DROP FOREIGN KEY FK_E7CCFA3B315D5561');
        $this->addSql('ALTER TABLE logement_option DROP FOREIGN KEY FK_E7CCFA3BC69DCAFF');
        $this->addSql('ALTER TABLE `option` DROP FOREIGN KEY FK_5A8600B058ABF955');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955315D5561');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849555741EEB9');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849554D0B7716');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE categorie_destination');
        $this->addSql('DROP TABLE categorie_hebergement');
        $this->addSql('DROP TABLE categorie_inspiration');
        $this->addSql('DROP TABLE code_promo');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE logement');
        $this->addSql('DROP TABLE logement_option');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
