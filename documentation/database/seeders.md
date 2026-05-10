# Documentation des Seeders et Factories : AttendanceFlow-AMS

Ce document explique comment les données de test et les données initiales sont gérées dans l'application pour garantir un environnement de développement stable et reproductible.

## 🏭 Factories (Usines de modèles)

Les factories sont utilisées pour générer des données aléatoires mais cohérentes pour les tests et le développement. Elles se trouvent dans `database/factories`.

### Principes de conception :
- **Relations automatiques** : Les factories utilisent d'autres factories pour les clés étrangères (ex: `SessionFactory` appelle `Module::factory()`), garantissant l'intégrité des données générées.
- **Données Faker** : Utilisation de la bibliothèque Faker pour des noms, emails et dates réalistes.
- **Flexibilité** : Possibilité de définir des états spécifiques (ex: utilisateur avec un email non vérifié).

---

## 💾 Seeders (Semeurs de données)

Les seeders permettent de remplir la base de données de manière structurée. Ils sont situés dans `database/seeders`.

### 🛠️ Structure du Seeding
Le `DatabaseSeeder` orchestre l'appel des autres seeders dans l'ordre suivant pour respecter les contraintes d'intégrité :

1. **`RolesAndPermissionsSeeder`** : 
   - Initialise les rôles de base via Spatie (`admin`, `teacher`, `student`).
   - **Solidité** : Utilise `firstOrCreate` pour être **idempotent** (ne crée pas de doublons si relancé).
2. **`UserSeeder`** : 
   - Crée les comptes administrateurs et professeurs fixes pour l'accès initial au système.
   - Assigne automatiquement les rôles correspondants.
3. **`CsvSeeder`** : 
   - Importe les données métier réelles (filières, groupes, modules, sessions) à partir des fichiers CSV situés dans `database/data`.
   - **Solidité** : Utilise `updateOrInsert` basé sur les clés uniques pour permettre la mise à jour des données sans erreurs de duplication.

### 📁 Données CSV (`database/data`)
Les fichiers suivants servent de source de vérité pour le peuplement initial :
- `filieres.csv` : Liste des départements.
- `groups.csv` : Liste des classes.
- `modules.csv` : Catalogue des matières.
- `users.csv` : Liste massive d'utilisateurs (étudiants/profs).
- `student_profiles.csv` / `teacher_profiles.csv` : Détails des profils.
- `sessions.csv` : Emploi du temps initial.

---

## 🚀 Utilisation

### Réinitialisation complète
Pour effacer la base et tout recommencer proprement :
```bash
php artisan migrate:fresh --seed
```

### Mise à jour des données
Grâce à l'idempotence des seeders, vous pouvez simplement relancer la commande suivante pour intégrer de nouveaux changements dans les CSV :
```bash
php artisan db:seed
```
