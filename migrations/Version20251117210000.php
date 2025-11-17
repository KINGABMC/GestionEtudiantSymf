<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251117210000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des tables pour le système d\'orientation des bacheliers';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS type_bacs (
            id SERIAL NOT NULL,
            code VARCHAR(100) NOT NULL,
            libelle VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,
            est_actif BOOLEAN NOT NULL DEFAULT true,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX IF NOT EXISTS UNIQ_TYPE_BAC_CODE ON type_bacs (code)');

        $this->addSql('CREATE TABLE IF NOT EXISTS ecoles (
            id SERIAL NOT NULL,
            nom VARCHAR(255) NOT NULL,
            type VARCHAR(50) NOT NULL,
            presentation TEXT DEFAULT NULL,
            accreditations TEXT DEFAULT NULL,
            cout_scolarite NUMERIC(10, 2) DEFAULT NULL,
            taux_insertion NUMERIC(5, 2) DEFAULT NULL,
            adresse VARCHAR(255) DEFAULT NULL,
            ville VARCHAR(100) DEFAULT NULL,
            region VARCHAR(100) DEFAULT NULL,
            telephone VARCHAR(50) DEFAULT NULL,
            email VARCHAR(255) DEFAULT NULL,
            site_web VARCHAR(255) DEFAULT NULL,
            latitude TEXT DEFAULT NULL,
            longitude TEXT DEFAULT NULL,
            logo VARCHAR(255) DEFAULT NULL,
            est_verifiee BOOLEAN NOT NULL DEFAULT false,
            est_active BOOLEAN NOT NULL DEFAULT true,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('COMMENT ON COLUMN ecoles.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN ecoles.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_ECOLES_TYPE ON ecoles (type)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_ECOLES_VILLE ON ecoles (ville)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_ECOLES_REGION ON ecoles (region)');

        $this->addSql('CREATE TABLE IF NOT EXISTS filieres (
            id SERIAL NOT NULL,
            ecole_id INT NOT NULL,
            nom VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,
            moyenne_minimale NUMERIC(5, 2) DEFAULT NULL,
            duree_annees INT DEFAULT NULL,
            cout_annuel NUMERIC(10, 2) DEFAULT NULL,
            debouches TEXT DEFAULT NULL,
            documents_requis TEXT DEFAULT NULL,
            concours_obligatoire BOOLEAN NOT NULL DEFAULT false,
            matieres_importantes TEXT DEFAULT NULL,
            diplome_delivre VARCHAR(100) DEFAULT NULL,
            est_active BOOLEAN NOT NULL DEFAULT true,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_FILIERES_ECOLE ON filieres (ecole_id)');
        $this->addSql('COMMENT ON COLUMN filieres.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN filieres.updated_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('CREATE TABLE IF NOT EXISTS filiere_type_bac (
            filiere_id INT NOT NULL,
            type_bac_id INT NOT NULL,
            PRIMARY KEY(filiere_id, type_bac_id)
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_FILIERE_TYPE_BAC_FILIERE ON filiere_type_bac (filiere_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_FILIERE_TYPE_BAC_TYPE_BAC ON filiere_type_bac (type_bac_id)');

        $this->addSql('CREATE TABLE IF NOT EXISTS bachelier (
            id INT NOT NULL,
            type_bac_id INT DEFAULT NULL,
            nom_complet VARCHAR(200) NOT NULL,
            telephone VARCHAR(25) DEFAULT NULL,
            moyenne NUMERIC(5, 2) DEFAULT NULL,
            annee_bac INT DEFAULT NULL,
            centres_interet TEXT DEFAULT NULL,
            notes_matieres_json TEXT DEFAULT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_BACHELIER_TYPE_BAC ON bachelier (type_bac_id)');
        $this->addSql('COMMENT ON COLUMN bachelier.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN bachelier.updated_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('CREATE TABLE IF NOT EXISTS avis (
            id SERIAL NOT NULL,
            ecole_id INT DEFAULT NULL,
            filiere_id INT DEFAULT NULL,
            user_id INT DEFAULT NULL,
            note INT NOT NULL,
            commentaire TEXT DEFAULT NULL,
            auteur VARCHAR(200) DEFAULT NULL,
            est_verifie BOOLEAN NOT NULL DEFAULT false,
            est_publie BOOLEAN NOT NULL DEFAULT true,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_AVIS_ECOLE ON avis (ecole_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_AVIS_FILIERE ON avis (filiere_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_AVIS_USER ON avis (user_id)');
        $this->addSql('COMMENT ON COLUMN avis.created_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('ALTER TABLE filieres ADD CONSTRAINT IF NOT EXISTS FK_FILIERES_ECOLE FOREIGN KEY (ecole_id) REFERENCES ecoles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE filiere_type_bac ADD CONSTRAINT IF NOT EXISTS FK_FILIERE_TYPE_BAC_FILIERE FOREIGN KEY (filiere_id) REFERENCES filieres (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE filiere_type_bac ADD CONSTRAINT IF NOT EXISTS FK_FILIERE_TYPE_BAC_TYPE_BAC FOREIGN KEY (type_bac_id) REFERENCES type_bacs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bachelier ADD CONSTRAINT IF NOT EXISTS FK_BACHELIER_USER FOREIGN KEY (id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bachelier ADD CONSTRAINT IF NOT EXISTS FK_BACHELIER_TYPE_BAC FOREIGN KEY (type_bac_id) REFERENCES type_bacs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT IF NOT EXISTS FK_AVIS_ECOLE FOREIGN KEY (ecole_id) REFERENCES ecoles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT IF NOT EXISTS FK_AVIS_FILIERE FOREIGN KEY (filiere_id) REFERENCES filieres (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT IF NOT EXISTS FK_AVIS_USER FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS filiere_type_bac CASCADE');
        $this->addSql('DROP TABLE IF EXISTS avis CASCADE');
        $this->addSql('DROP TABLE IF EXISTS bachelier CASCADE');
        $this->addSql('DROP TABLE IF EXISTS filieres CASCADE');
        $this->addSql('DROP TABLE IF EXISTS ecoles CASCADE');
        $this->addSql('DROP TABLE IF EXISTS type_bacs CASCADE');
    }
}
