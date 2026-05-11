# 💻 Réalisation

## 1. Architecture Implémentée

L'application **AttendanceFlow-AMS** suit une architecture **MVC (Modèle-Vue-Contrôleur)** couplée à une **architecture 3-Tiers**, renforcée par un **Service Pattern** pour le découplage de la logique métier.

### Couches de l'Application

```
┌─────────────────────────────────────────────┐
│  Couche Présentation (Blade + Alpine.js)     │
│  Tailwind CSS / Preline UI / Lucide Icons    │
├─────────────────────────────────────────────┤
│  Couche Application (Laravel 12)             │
│  Contrôleurs → Services → Repositories       │
├─────────────────────────────────────────────┤
│  Couche Données (MySQL)                      │
│  Eloquent ORM + Migrations + Seeders         │
└─────────────────────────────────────────────┘
```

### Service Pattern

La logique métier est encapsulée dans 6 services spécialisés héritant d'une classe `BaseService` commune avec logging et télémetrie :

| Service | Responsabilité |
|---------|----------------|
| `IdentityService` | Authentification, gestion des rôles |
| `AcademicService` | Gestion des filières, groupes, modules, profils |
| `AttendanceService` | Pointage individuel et groupé des présences |
| `JustificationService` | Cycle de vie des justificatifs (soumission → validation) |
| `SchedulingService` | Planification des sessions avec détection de conflits |
| `ReportingService` | Statistiques et analytics d'assiduité |

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## 2. Modules Fonctionnels

### 🛡️ Module Authentification
- Login sécurisé avec validation d'email/mot de passe
- Redirection intelligente basée sur le rôle (Admin → `/admin/dashboard`, Formateur → `/teacher/dashboard`, Étudiant → `/student/dashboard`)
- Déconnexion avec invalidation de session et régénération de token
- Comptes de démonstration pré-initialisés avec rôles et permissions (Spatie Laravel-Permission)

### 📊 Dashboard Administrateur
- 4 cartes statistiques : total étudiants, enseignants, justificatifs en attente, taux de présence global
- Accès rapide aux fonctionnalités : nouvelle session, pointage, rapports
- Fil d'activité récent (pointages et justifications)

### 📋 Gestion des Sessions (CRUD)
- Création, modification, suppression et liste des sessions académiques
- Validation des données : module, formateur, groupe, type de séance (CM/TD/TP), créneaux horaires
- Détection automatique des conflits de planning via `SchedulingService`
- Filtrage par date avec sélecteur de calendrier

### ✅ Pointage des Présences
- Sélection rapide des étudiants avec barre de recherche
- Statuts : Présent, Absent, Retard (avec saisie du motif)
- Statistiques en temps réel : taux de présence, nombre d'absents/retards
- Actions groupées (marquer tous présents/absents)
- Interface responsive adaptée au mobile

### 📄 Gestion des Justificatifs
- **Formateur/Admin :** Visualisation des justificatifs soumis, filtre par statut (en attente/accepté/refusé), approbation ou rejet en un clic
- **Étudiant :** Soumission numérique avec upload de fichier (PDF/JPG, max 10MB), historique des demandes
- Mise à jour automatique du statut d'absence lors de l'acceptation d'un justificatif

### 📈 Rapports et Analytics (Chart.js)
- 4 onglets : Vue d'ensemble, Tendances mensuelles, Par classe, Étudiants à risque
- Graphiques : courbe d'évolution mensuelle, répartition des statuts (donut), barres par jour de semaine
- Classement des groupes par taux de présence
- Détection des étudiants à risque (< 90% d'assiduité)

### 👥 Annuaire des Étudiants
- Recherche dynamique par nom/prénom
- Filtre par groupe
- Barres de progression d'assiduité par étudiant
- Pagination intégrée

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## 3. API RESTful

Une API complète a été développée avec **Laravel Sanctum** pour l'authentification par token, destinée à la future application mobile :

| Endpoint | Méthode | Description |
|----------|---------|-------------|
| `/api/login` | POST | Authentification et génération de token |
| `/api/logout` | POST | Révocation du token |
| `/api/me` | GET | Profil de l'utilisateur connecté |
| `/api/academic/filieres` | GET | Liste des filières |
| `/api/academic/groups` | GET | Liste des groupes |
| `/api/academic/sessions` | GET | Liste des sessions |
| `/api/attendance/session/{id}` | GET | Pointages d'une session |
| `/api/attendance/record` | POST | Enregistrement d'un pointage |
| `/api/justifications/pending` | GET | Justificatifs en attente |
| `/api/justifications/submit` | POST | Soumission d'un justificatif |
| `/api/stats/admin` | GET | Statistiques globales |

**Total : 17 endpoints** couvrant l'authentification, les données académiques, le pointage, les justificatifs et les statistiques.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## 4. Base de Données

### Schéma Relationnel

Le système repose sur **12 tables applicatives** (plus les tables Laravel et Spatie) :

| Table | Rôle | Relations clés |
|-------|------|----------------|
| `users` | Utilisateurs (tous les profils) | → teacher_profiles, student_profiles |
| `filieres` | Filières de formation | → groups |
| `groups` | Groupes d'étudiants | → filieres, student_profiles, academic_sessions |
| `modules` | Matières enseignées | → academic_sessions |
| `teacher_profiles` | Profils formateurs | → users, academic_sessions |
| `student_profiles` | Profils étudiants (avec matricule) | → users, groups, attendance_records, justifications |
| `module_teacher_group` | Pivot : affectation enseignant/matière/groupe | → modules, teacher_profiles, groups |
| `academic_sessions` | Sessions de cours | → modules, teacher_profiles, groups, attendance_records |
| `attendance_records` | Enregistrements de présence | → student_profiles, academic_sessions |
| `justifications` | Justificatifs d'absence | → student_profiles |
| `roles` / `permissions` | ACL (Spatie) | 3 rôles : admin, teacher, student |

### Données de Démonstration

10 fichiers CSV dans `database/data/` permettent de peupler l'application avec des données réalistes :
- 4 filières, 8 groupes, 12 modules
- 4 utilisateurs avec rôles + 40 étudiants
- Sessions pré-générées avec enregistrements de présence
- Justificatifs d'exemple

**Comptes de test :**

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | `admin@ams.com` | `password` |
| Administratrice | `hannane@ams.com` | `password` |
| Formatrice | `imane@ams.com` | `password` |
| Formateur | `ahmed@ams.com` | `password` |
| Étudiant | `student1@ams.com` | `password` |

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## 5. Stack Technologique

### Backend
- **Laravel 12** (PHP 8.2+) : Framework principal avec Eloquent ORM, routing, validation
- **Spatie Laravel-Permission** v7.2 : Gestion fine des rôles et permissions
- **Laravel Sanctum** v4.0 : API tokens pour l'authentification mobile
- **MySQL** : Base de données relationnelle

### Frontend
- **Tailwind CSS v4** : Design atomique utilitaire, build via Vite
- **Alpine.js** v3.15 : Réactivité côté client sans complexité (15 composants)
- **Preline UI** v4.1 : Composants UI prêts à l'emploi
- **Lucide Icons** : Bibliothèque d'icônes SVG
- **Chart.js** : Graphiques de reporting (via npm, bundle Vite)
- **Vite** v7 : Build tool haute performance (1766 modules transformés)

### Outils de Développement
- **IDE :** Visual Studio Code avec assistant IA Antigravity
- **Versionnement :** Git + GitHub
- **Conception UML :** PlantUML (diagrammes de classes, cas d'utilisation)
- **Base de données :** MySQL Workbench / phpMyAdmin

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## 6. Défis Techniques et Solutions

### 6.1 Détection de Conflits de Planning
**Problème :** Un formateur ou une salle ne doit pas être programmé sur deux sessions simultanément.

**Solution :** Implémentation dans `SchedulingService` d'une vérification des chevauchements horaires avant création de session, utilisant les contraintes temporelles en base de données.

### 6.2 Classes Tailwind Dynamiques
**Problème :** Les classes Tailwind construites dynamiquement (ex: `"text-$color-600"`) ne sont pas détectées par le scan de Tailwind et ne génèrent pas de CSS.

**Solution :** Utilisation de "class maps" complètes en PHP avec toutes les variantes possibles, garantissant que toutes les classes sont présentes dans le bundle de production.

### 6.3 Pointage en Temps Réel
**Problème :** Les statistiques de pointage doivent se mettre à jour instantanément lorsque l'utilisateur modifie le statut d'un étudiant.

**Solution :** Composants Alpine.js avec état réactif (`x-data`) qui recalculent les statistiques (présents, absents, retards, taux) à chaque modification, sans rechargement de page.

### 6.4 Sécurisation par Rôle
**Problème :** Chaque route doit être accessible uniquement par le rôle approprié.

**Solution :** Middleware personnalisé `role:admin|teacher|student` combiné aux permissions Spatie, avec redirection automatique après authentification via une méthode `authenticated()` dans le contrôleur de login.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## 7. Captures d'Écran

*Les captures d'écran ci-dessous montrent l'application en fonctionnement avec les données de démonstration.*

*(Insérer ici les captures d'écran de l'application en fonctionnement)*

| Écran | Description |
|-------|-------------|
| Dashboard Admin | Vue d'ensemble avec statistiques et accès rapides |
| Liste des Sessions | Calendrier des sessions avec filtre par date |
| Pointage | Marquer les présences avec recherche et stats en direct |
| Justificatifs | Liste des justificatifs avec actions d'approbation |
| Rapports | Graphiques d'analyse d'assiduité (Chart.js) |
| Dashboard Formateur | Session en cours et planning du jour |
| Dashboard Étudiant | Taux de présence et historique |

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## 8. Vue d'Ensemble du Code

### Statistiques du Projet

| Métrique | Valeur |
|----------|--------|
| Migrations | 14 (dont 3 Laravel core) |
| Modèles Eloquent | 9 |
| Contrôleurs | 17 (Web: 11, API: 5, Base: 1) |
| Services métier | 6 |
| Vues Blade | 33 |
| Composants Blade | 10 |
| Modules JS (Alpine) | 7 |
| Routes Web | 22 |
| Endpoints API | 17 |
| Fichiers CSV (seed) | 10 |
| Utilisateurs de démonstration | 44 (4 staff + 40 étudiants) |

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```
