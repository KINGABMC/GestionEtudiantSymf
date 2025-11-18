# Guide de Test - Système d'Avis

## 🧪 Scénarios de test

### 1. Consulter les avis

#### Test 1.1: Voir les avis d'une école
1. Aller à `/orientation/ecoles`
2. Cliquer sur une école (ex: ESP)
3. Scroller vers le bas
4. **Vérifier:** Section "Avis et témoignages" visible avec avis récents
5. **Vérifier:** Note globale affichée avec étoiles
6. Cliquer "Voir tous les avis"
7. **Vérifier:** Page `/avis/ecole/{id}` avec tous les avis

#### Test 1.2: Voir les avis d'une filière
1. Aller à `/orientation/filieres`
2. Cliquer sur une filière (ex: Génie Informatique)
3. Scroller vers le bas
4. **Vérifier:** Note globale affichée (si avis existe)
5. Cliquer "Voir les avis"
6. **Vérifier:** Page `/avis/filiere/{id}` affichée

### 2. Ajouter un avis

#### Test 2.1: Ajouter un avis sans connexion
1. Aller à `/orientation/ecole/{id}` ou `/orientation/filiere/{id}`
2. Scroller vers le bas
3. **Vérifier:** Message "Connectez-vous pour ajouter un avis"
4. Cliquer sur "Connectez-vous"
5. **Vérifier:** Redirect vers page login

#### Test 2.2: Ajouter un avis connecté (école)
1. Se connecter
2. Aller à `/avis/ecole/{id}/ajouter`
3. **Vérifier:** Formulaire affiché avec:
   - Selection de note (boutons radio avec étoiles)
   - Champ auteur vide
   - Textarea commentaire vide
4. Sélectionner note: 4 étoiles
5. Entrer auteur: "Jean Dupont"
6. Entrer commentaire: "Très bonne école, excellents professeurs"
7. Cliquer "Soumettre mon avis"
8. **Vérifier:** Flash "Votre avis a été soumis..."
9. **Vérifier:** Redirect vers détail école
10. **Vérifier:** Nouvel avis visible dans la liste

#### Test 2.3: Ajouter un avis anonyme (filière)
1. Se connecter
2. Aller à `/avis/filiere/{id}/ajouter`
3. Sélectionner note: 3 étoiles
4. Laisser auteur vide
5. Entrer commentaire: "Formation intéressante mais peut être améliorée"
6. Cliquer "Soumettre mon avis"
7. **Vérifier:** Flash succès
8. **Vérifier:** Avis affiche "Utilisateur anonyme"

#### Test 2.4: Validation formulaire
1. Aller à `/avis/ecole/{id}/ajouter`
2. Ne rien sélectionner
3. Cliquer "Soumettre"
4. **Vérifier:** Message d'erreur pour note
5. Remplir commentaire avec > 1000 chars
6. **Vérifier:** Erreur validation
7. Sélectionner note = 2
8. Commentaire = texte court
9. **Vérifier:** Soumission acceptée

### 3. Affichage des avis

#### Test 3.1: Affichage détail école
1. Aller à `/orientation/ecole/{id}`
2. **Vérifier:** Section avis affiche:
   - Jusqu'à 3 derniers avis
   - Étoiles pour chaque note
   - Nom auteur
   - Date publication
   - Commentaire tronqué
3. **Vérifier:** Bouton "Voir tous les avis"
4. **Vérifier:** Bouton "Ajouter un avis" (si connecté)

#### Test 3.2: Affichage détail filière
1. Aller à `/orientation/filiere/{id}`
2. **Vérifier:** Note globale affichée si avis existent
3. **Vérifier:** Carte "Note globale" avec:
   - Étoiles
   - Note moyenne
   - Compteur d'avis
4. **Vérifier:** Boutons d'action

#### Test 3.3: Pagination liste avis
1. Aller à `/avis/ecole/{id}` ou `/avis/filiere/{id}`
2. Avoir > 10 avis
3. **Vérifier:** Pagination 10 par page
4. Cliquer page 2
5. **Vérifier:** Avis 11-20 affichés
6. Liens pagination actifs

### 4. Supprimer un avis

#### Test 4.1: Supprimer son avis
1. Être connecté
2. Aller liste avis
3. Trouver son avis
4. **Vérifier:** Bouton supprimer visible
5. Cliquer bouton "X" rouge (supprimer)
6. Confirmer dialog "Êtes-vous sûr?"
7. **Vérifier:** Flash "Votre avis a été supprimé"
8. **Vérifier:** Avis disparu de la liste

#### Test 4.2: Impossible de supprimer avis d'un autre
1. Être connecté (utilisateur A)
2. Aller liste avis (écrite par utilisateur B)
3. **Vérifier:** Pas de bouton supprimer

#### Test 4.3: Admin peut supprimer tout avis
1. Se connecter en tant qu'admin
2. Aller liste avis
3. **Vérifier:** Bouton supprimer visible pour tous les avis
4. Supprimer avis
5. **Vérifier:** Suppression fonctionne

### 5. Calcul de la moyenne

#### Test 5.1: Moyenne recalculée
1. Ecole sans avis: moyenne = 0
2. Ajouter 1 avis: 4 étoiles
3. **Vérifier:** Moyenne = 4.0
4. Ajouter 2ème avis: 5 étoiles
5. **Vérifier:** Moyenne = 4.5
6. Ajouter 3ème avis: 3 étoiles
7. **Vérifier:** Moyenne = 4.0 (arrondi)

### 6. Navigation globale

#### Test 6.1: Breadcrumbs
1. Sur page ajouter avis pour école
2. **Vérifier:** Breadcrumb:
   - Accueil (lien)
   - Détail école (lien)
   - Ajouter un avis (actif)
3. Cliquer "Accueil"
4. **Vérifier:** Retour accueil

#### Test 6.2: Boutons retour
1. Sur page liste avis école
2. Cliquer "Retour à l'école"
3. **Vérifier:** Retour vers `/orientation/ecole/{id}`
4. Sur page liste avis filière
5. Cliquer "Retour à la filière"
6. **Vérifier:** Retour vers `/orientation/filiere/{id}`

---

## 🔍 Cas limites à tester

### Test L1: Caractères spéciaux
1. Ajouter avis avec: `<script>alert('XSS')</script>`
2. **Vérifier:** Rendu échappé (pas d'exécution)

### Test L2: Très long commentaire
1. Entrer commentaire de 1000 caractères exactement
2. **Vérifier:** Accepté
3. Entrer 1001 caractères
4. **Vérifier:** Rejeté

### Test L3: Spécial unicode
1. Ajouter avis en caractères arabes/chinois
2. **Vérifier:** Affichage correct

### Test L4: Pas de commentaire + auteur
1. Sélectionner juste la note
2. Entrer auteur
3. Laisser commentaire vide
4. **Vérifier:** Accepté

### Test L5: Accès direct URL
1. Essayer `/avis/ecole/9999` (école inexistante)
2. **Vérifier:** 404
3. Essayer `/avis/filiere/9999` (filière inexistante)
4. **Vérifier:** 404

---

## 📊 Tests de performance

### Test P1: Affichage avec beaucoup d'avis
1. Ajouter 100+ avis à une école
2. Charger `/avis/ecole/{id}`
3. **Vérifier:** Page répond < 2s
4. Pagination fonctionne

### Test P2: Pagination rapide
1. Naviguer entre pages rapidement
2. **Vérifier:** Pas d'erreur 500
3. Pas de duplication avis

---

## 🔐 Tests de sécurité

### Test S1: CSRF protection
1. Obtenir formulaire
2. Supprimer token CSRF
3. Soumettre formulaire
4. **Vérifier:** Erreur CSRF

### Test S2: Suppression protégée
1. Copier URL suppression d'un avis
2. Se connecter en tant qu'autre utilisateur
3. Accéder URL suppression
4. **Vérifier:** Accès refusé

### Test S3: SQL injection
1. Auteur: `'; DROP TABLE avis; --`
2. **Vérifier:** Pas de suppression table
3. Avis créé avec texte literal

### Test S4: XSS réfléchi
1. Commentaire: `<img src=x onerror=alert('XSS')>`
2. **Vérifier:** Pas d'exécution JS
3. Texte échappé affichée

---

## 🎯 Checklist finale

- [ ] Avis créés et affichés correctement
- [ ] Note globale calculée
- [ ] Pagination fonctionne
- [ ] Suppression sécurisée
- [ ] Validation des données
- [ ] Pas de XSS/CSRF
- [ ] Breadcrumbs corrects
- [ ] Responsive design OK
- [ ] Erreurs 404 appropriées
- [ ] Performance acceptable

---

## 🚀 Commandes utiles

```bash
# Voir tous les avis
php bin/console doctrine:query:sql "SELECT * FROM avis"

# Compter avis par école
php bin/console doctrine:query:sql "SELECT ecole_id, COUNT(*) FROM avis GROUP BY ecole_id"

# Vider les avis
php bin/console doctrine:query:sql "DELETE FROM avis"

# Recharger fixtures
php bin/console doctrine:fixtures:load --purge-with-truncate
```

---

## 📋 Résumé routes à tester

| Route | Méthode | Description |
|-------|---------|-------------|
| `/avis/ecole/{id}/ajouter` | GET | Formulaire ajout |
| `/avis/ecole/{id}/ajouter` | POST | Création avis |
| `/avis/ecole/{id}` | GET | Liste avis école |
| `/avis/filiere/{id}/ajouter` | GET | Formulaire ajout |
| `/avis/filiere/{id}/ajouter` | POST | Création avis |
| `/avis/filiere/{id}` | GET | Liste avis filière |
| `/avis/{id}/supprimer` | POST | Suppression avis |

---

## 📸 Points clés à vérifier

1. **Note affichée correctement** - Étoiles pleines et vides
2. **Moyenne recalculée** - Après ajout/suppression
3. **Redirect appropriée** - Vers la bonne page
4. **Flash messages** - Succès/erreur visibles
5. **Permissions** - Suppression protégée
6. **Affichage** - Auteur, date, commentaire
7. **Mobile** - Design responsive
8. **Performance** - Pas de lag
