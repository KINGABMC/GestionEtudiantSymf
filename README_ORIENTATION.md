# Application d'Orientation pour Bacheliers

Application web Symfony pour l'orientation des nouveaux bacheliers vers les écoles et filières adaptées à leur profil.

## Fonctionnalités principales

### A. Espace Orientation
- Recherche d'écoles (publiques/privées) par localisation, type, et critères
- Recherche de filières selon le type de BAC
- Consultation des conditions d'accès (moyenne minimale, notes requises, concours)
- Comparateur d'écoles (coûts, durées, accréditations, taux d'insertion)

### B. Conseiller Intelligent
Le bachelier peut entrer:
- Type de BAC
- Moyenne générale
- Centres d'intérêt

L'application génère:
- Les filières compatibles
- Les écoles recommandées
- Un score d'affinité par filière (0-100%)
- Les risques ou inadéquations possibles

### C. Espace Écoles
Chaque école dispose d'une fiche détaillée avec:
- Présentation complète
- Accréditations (ANQEP, CAMES, etc.)
- Coût de scolarité
- Filières proposées
- Taux d'insertion professionnelle
- Coordonnées et localisation
- Avis d'étudiants

### D. Système d'Avis
- Notation des écoles et filières (1-5 étoiles)
- Commentaires d'étudiants
- Modération et vérification des avis

## Architecture technique

### Entités principales
- **Ecole**: Informations sur les établissements
- **Filiere**: Programmes de formation
- **TypeBac**: Types de baccalauréat (L, S, G, T)
- **Bachelier**: Profil des utilisateurs bacheliers
- **Avis**: Évaluations des écoles et filières
- **User**: Système d'authentification

### Services
- **ConseillerOrientationService**: Algorithme de recommandation intelligent
  - Calcul du score d'affinité basé sur:
    - Moyenne du bachelier vs minimum requis (40 points max)
    - Notes dans les matières importantes (30 points max)
    - Correspondance avec centres d'intérêt (30 points max)
  - Identification des risques (moyenne insuffisante, concours, coût élevé)
  - Génération de recommandations détaillées

### Contrôleurs
- **OrientationController**: Toutes les fonctionnalités publiques
  - Page d'accueil
  - Liste et détails des écoles
  - Liste et détails des filières
  - Conseiller intelligent
  - Comparateur d'écoles

## Installation et démarrage

### Prérequis
- PHP 8.2+
- PostgreSQL
- Composer
- Symfony CLI

### Installation

1. Installer les dépendances:
```bash
composer install
```

2. Configurer la base de données dans `.env`:
```
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/orientation_db"
```

3. Créer la base de données:
```bash
php bin/console doctrine:database:create
```

4. Exécuter les migrations:
```bash
php bin/console doctrine:migrations:migrate
```

5. Charger les données de test:
```bash
php bin/console doctrine:fixtures:load
```

### Démarrage du serveur
```bash
symfony server:start
```

L'application sera accessible à l'adresse: http://localhost:8000

## Données de test

Les fixtures créent:
- 4 types de BAC (L, S, G, T)
- 5 écoles (UCAD, ESP, ISM, UVS, ESEA)
- 8 filières diverses (Génie Informatique, Médecine, Management, etc.)

## URLs principales

- `/` ou `/orientation` - Page d'accueil
- `/orientation/ecoles` - Liste des écoles
- `/orientation/filieres` - Liste des filières
- `/orientation/conseiller` - Conseiller intelligent
- `/orientation/comparateur` - Comparateur d'écoles
- `/orientation/ecole/{id}` - Détail d'une école
- `/orientation/filiere/{id}` - Détail d'une filière

## Sécurité et rôles

- **ROLE_USER**: Accès basique
- **ROLE_BACHELIER**: Utilisateur bachelier
- **ROLE_EMPLOYE**: Employé (ancien système)
- **ROLE_ADMIN**: Administrateur complet

Toutes les pages d'orientation sont accessibles publiquement (`PUBLIC_ACCESS`).

## Algorithme de recommandation

Le score d'affinité est calculé comme suit:

1. **Score de moyenne (40 points max)**:
   - Écart >= 3 points: 40 points
   - Écart >= 1.5 points: 30 points
   - Écart >= 0 points: 20 points

2. **Score matières importantes (30 points max)**:
   - Proportion de bonnes notes (>= 12/20) dans les matières clés

3. **Score centres d'intérêt (30 points max)**:
   - Correspondance avec la description et les débouchés de la filière

**Niveaux de recommandation**:
- Score >= 75%: Fortement recommandé
- Score >= 60%: Recommandé
- Score >= 40%: Possible
- Score < 40%: Peu recommandé

## Technologies utilisées

- **Framework**: Symfony 7.3
- **Base de données**: PostgreSQL
- **ORM**: Doctrine
- **Templates**: Twig
- **CSS**: Bootstrap 5.3
- **Icons**: Bootstrap Icons

## Structure du projet

```
src/
├── Controller/
│   └── OrientationController.php
├── Entity/
│   ├── Ecole.php
│   ├── Filiere.php
│   ├── TypeBac.php
│   ├── Bachelier.php
│   ├── Avis.php
│   └── User.php
├── Repository/
│   ├── EcoleRepository.php
│   ├── FiliereRepository.php
│   └── ...
├── Service/
│   └── ConseillerOrientationService.php
└── DataFixtures/
    ├── TypeBacFixtures.php
    ├── EcoleFixtures.php
    └── FiliereFixtures.php

templates/
└── orientation/
    ├── index.html.twig
    ├── conseiller.html.twig
    ├── resultats_conseiller.html.twig
    ├── ecoles.html.twig
    ├── ecole_detail.html.twig
    ├── filieres.html.twig
    ├── filiere_detail.html.twig
    └── comparateur.html.twig
```

## Évolutions futures possibles

- [ ] Système d'inscription en ligne
- [ ] Chatbot d'orientation IA
- [ ] Notifications pour dates limites
- [ ] Forum communautaire
- [ ] Témoignages vidéo d'anciens étudiants
- [ ] API REST pour applications mobiles
- [ ] Système de favoris
- [ ] Export PDF des recommandations
- [ ] Statistiques détaillées pour les écoles
- [ ] Module de gestion administrative pour les écoles

## Licence

Propriétaire
