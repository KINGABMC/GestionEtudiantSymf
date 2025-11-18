# Système d'Avis - Documentation

## Vue d'ensemble

Le système d'avis permet aux utilisateurs de partager leur expérience avec les écoles et les filières. C'est un élément clé de la communauté pour aider les futurs bacheliers à prendre des décisions éclairées.

## Fonctionnalités

### 1. Ajouter un avis

Les utilisateurs authentifiés peuvent ajouter des avis sur:
- Les écoles
- Les filières

**Routes:**
- `POST /avis/ecole/{id}/ajouter` - Ajouter un avis pour une école
- `POST /avis/filiere/{id}/ajouter` - Ajouter un avis pour une filière

**Formulaire:**
- **Note** (obligatoire): 1-5 étoiles
- **Nom de l'auteur** (optionnel): Peut rester anonyme
- **Commentaire** (optionnel): Jusqu'à 1000 caractères

**Validation:**
- Note requise et entre 1 et 5
- Commentaire maximum 1000 caractères
- Aucune injection HTML (Twig auto-échappe)

### 2. Consulter les avis

**Pages de consultation:**
- `GET /avis/ecole/{id}` - Liste tous les avis pour une école
- `GET /avis/filiere/{id}` - Liste tous les avis pour une filière

**Pagination:** 10 avis par page

**Affichage:**
- Note moyenne globale (affichée en étoiles)
- Tous les avis publiés et vérifiés
- Nom de l'auteur ou "Utilisateur anonyme"
- Date de publication
- Commentaire complet

### 3. Affichage intégré

Les avis s'affichent aussi sur:
- Pages de détail d'école: Les 3 derniers avis + note globale
- Pages de détail de filière: Note globale si disponible

### 4. Supprimer un avis

**Route:** `POST /avis/{id}/supprimer`

**Permissions:**
- L'auteur de l'avis
- Les administrateurs

**Protection:** Token CSRF requis

## Modèle de données

### Entité Avis

```php
class Avis {
    int $id;
    int $note;              // 1-5
    string $commentaire;    // max 1000 chars
    string $auteur;         // optionnel, anonyme possible
    bool $estVerifie;       // vérifié par modération
    bool $estPublie;        // publié ou en attente
    DateTimeImmutable $createdAt;
    Ecole $ecole;           // nullable
    Filiere $filiere;       // nullable
    User $user;             // nullable
}
```

### Note moyenne

La note moyenne est calculée automatiquement:
```php
public function getMoyenneAvis(): float {
    // Moyenne de toutes les notes des avis publiés
}
```

## Sécurité

### Authentification
- Seuls les utilisateurs authentifiés peuvent ajouter des avis
- Les avis anonymes ne stockent pas l'ID utilisateur dans le commentaire

### Modération
- Tous les avis sont publiés par défaut (`estPublie = true`)
- Les avis peuvent être marqués comme vérifiés (`estVerifie = true`)
- Les avis sont validés lors de la soumission

### Protection CSRF
- Token CSRF requis pour soumettre un formulaire
- Token CSRF requis pour supprimer un avis

### Suppression
- Seul l'auteur ou un administrateur peut supprimer un avis
- Vérification `isGranted('ROLE_ADMIN')`

## Formulaire (AvisType)

### Champs

**Note** (radio buttons visuels)
```
⭐ 1 - Très mauvais
⭐⭐ 2 - Mauvais
⭐⭐⭐ 3 - Moyen
⭐⭐⭐⭐ 4 - Bon
⭐⭐⭐⭐⭐ 5 - Excellent
```

**Auteur** (TextType)
- Optionnel
- Placeholder: "Ex: Marie Diop"
- Utilisé pour signature publique

**Commentaire** (TextareaType)
- Optionnel
- 5 lignes d'affichage
- Max 1000 caractères
- Placeholder avec conseils

### Validation
- Note: requise, entre 1 et 5 (Range constraint)
- Commentaire: max 1000 caractères (Length constraint)

## Contrôleur (AvisController)

### Méthodes principales

#### `ajouterAvisEcole(int $id, Request $request): Response`
- Crée un nouvel avis pour une école
- Récupère l'utilisateur authentifié
- Redirige vers la page de détail de l'école

#### `ajouterAvisFiliere(int $id, Request $request): Response`
- Crée un nouvel avis pour une filière
- Récupère l'utilisateur authentifié
- Redirige vers la page de détail de la filière

#### `listeAvisEcole(int $id, Request $request): Response`
- Affiche tous les avis publiés pour une école
- Pagination: 10 par page
- Calcule la note globale

#### `listeAvisFiliere(int $id, Request $request): Response`
- Affiche tous les avis publiés pour une filière
- Pagination: 10 par page
- Calcule la note globale

#### `supprimerAvis(int $id, Request $request): Response`
- Supprime un avis
- Vérifie les permissions (auteur ou admin)
- Redirige vers la page d'origine

## Templates

### `avis/form.html.twig`
Formulaire d'ajout d'avis avec:
- Sélection visuelle de la note (boutons radio)
- Champ auteur optionnel
- Textarea pour le commentaire
- Conseils sur la rédaction
- Boutons d'action

### `avis/liste_ecole.html.twig`
Liste complète des avis pour une école:
- En-tête avec note globale et compteur
- Bouton pour ajouter un avis
- Liste d'avis avec pagination
- Affichage de chaque avis

### `avis/liste_filiere.html.twig`
Liste complète des avis pour une filière:
- En-tête avec note globale et compteur
- Bouton pour ajouter un avis
- Liste d'avis avec pagination
- Affichage de chaque avis

## Affichage des étoiles

```twig
{% for i in 1..note %}
    <i class="bi bi-star-fill"></i>
{% endfor %}
{% for i in 1..(5-note) %}
    <i class="bi bi-star"></i>
{% endfor %}
```

## Routes

| Méthode | Route | Nom | Description |
|---------|-------|-----|-------------|
| GET/POST | /avis/ecole/{id}/ajouter | app_avis_ecole_add | Ajouter avis école |
| GET/POST | /avis/filiere/{id}/ajouter | app_avis_filiere_add | Ajouter avis filière |
| GET | /avis/ecole/{id} | app_avis_ecole_liste | Lister avis école |
| GET | /avis/filiere/{id} | app_avis_filiere_liste | Lister avis filière |
| POST | /avis/{id}/supprimer | app_avis_delete | Supprimer avis |

## Intégration aux pages existantes

### Page détail école
- Affiche les 3 derniers avis
- Bouton "Voir tous les avis"
- Bouton "Ajouter un avis" (si connecté)

### Page détail filière
- Affiche la note globale
- Bouton "Ajouter un avis" (si connecté)
- Bouton "Voir les avis" (si avis disponibles)

## Améliorations futures

- [ ] Tri des avis (récents, utiles, notes)
- [ ] Système de "utile/pas utile"
- [ ] Filtrage par note
- [ ] Recherche textuelle dans les avis
- [ ] Signalement d'avis inappropriés
- [ ] Images/screenshots dans les avis
- [ ] Réponses aux avis (admin)
- [ ] Badges "Ancien élève vérifié"
- [ ] Statistiques détaillées par catégorie
- [ ] Export PDF des avis

## Tests unitaires recommandés

- Création d'avis valide
- Validation des contraintes
- Suppression par auteur
- Suppression par admin
- Accès non autorisé
- Pagination des avis
- Calcul de la moyenne
