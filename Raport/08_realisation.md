# 💻 Réalisation

## Architecture 3-Tiers (MVC)

L'application **AttendanceFlow-AMS** est structurée selon le patron **Modèle-Vue-Contrôleur** en trois couches distinctes, garantissant une séparation claire des responsabilités et une maintenabilité optimale.

---

### Couche 1 : Présentation (Interface Utilisateur)

| Plateforme | Technologie | Rôle |
|------------|-------------|------|
| **Application Mobile** | Blade + Tailwind CSS + Alpine.js + NativePHP | Interface mobile native Android pour le terrain (formateurs, étudiants) |
| **API (Headless)** | Laravel JSON Response | Aucune UI — fournit les données au client mobile via REST |

**Détail des écrans mobiles réalisés :**

| Écran | Fichier Vue | Fonctionnalité |
|-------|-------------|----------------|
| Hub | `mobile/hub.blade.php` | Portail de sélection de rôle (Admin / Formateur / Étudiant) |
| Liste des sessions | `mobile/sessions/index.blade.php` | Dashboard formateur — statistiques, liste des sessions, justificatifs en attente |
| Détail session | `mobile/sessions/show.blade.php` | Revue des présences pour une session donnée |
| Pointage Flash | `mobile/attendance/flash.blade.php` | Saisie rapide des présences (actions groupées + par étudiant) |
| Dashboard Admin | `mobile/admin/dashboard.blade.php` | Statistiques globales, justificatifs en attente de validation |
| Dashboard Étudiant | `mobile/student/dashboard.blade.php` | Assiduité personnelle, liste des absences, suivi hebdomadaire |
| Layout global | `mobile/layouts/app.blade.php` | Barre supérieure fixe, navigation bottom, safe-area insets |

**Stack UI :**
- **Tailwind CSS** : Design atomique responsive, mobile-first
- **Alpine.js** : Interactivité dynamique côté client (compteurs, états)
- **Lucide Icons** : Iconographie premium
- **NativePHP** : Compilation native Android (package `com.solicode.attendanceFlow`)

---

### Couche 2 : Application (Logique Métier)

**Projet API** — `AttendanceFlow-AMS/` (Laravel 12)

| Controller | Endpoints | Responsabilité |
|------------|-----------|----------------|
| `AuthController` | `POST /api/login`, `GET /api/me`, `POST /api/logout` | Authentification via Sanctum (token-based), gestion de session |
| `AcademicController` | `GET /api/academic/filieres`, `/groups`, `/modules`, `/sessions`, `/sessions/teacher/{id}`, `/session/{id}` | Données académiques en lecture seule avec eager loading |
| `AttendanceController` | `GET /api/attendance/student/{id}`, `/session/{id}`, `POST /api/attendance/record` | CRUD des présences, enregistrement par lot avec `updateOrCreate` (idempotent) |
| `JustificationController` | `GET /api/justifications/pending`, `POST /api/justifications/submit` | Gestion des justificatifs (upload fichier, workflow pending/accepted/rejected) |
| `StatsController` | `GET /api/stats/admin`, `/stats/student/{id}` | Agrégations temps réel pour dashboards |

**Projet Mobile** — `AttendanceFlow-AMS-Mobile/` (Laravel 13 + NativePHP)

| Controller | Routes | Responsabilité |
|------------|--------|----------------|
| `SessionController` | `GET /mobile/teacher`, `/mobile/session/{id}`, `/mobile/flash/{id}` | Récupération sessions via API, rendu des vues mobile |
| `AttendanceController` | `POST /mobile/attendance/record` | Validation formulaire, appel API pour enregistrement |
| `AdminController` | `GET /mobile/admin` | Dashboard admin (stats + justificatifs) |
| `StudentController` | `GET /mobile/student/{id?}` | Dashboard étudiant (stats + historique) |

**Service d'intégration :**
- `ApiService` : Client HTTP Laravel consommant l'API REST (`config('services.ams.url')`)
- Communication via JSON, authentification par token Sanctum

---

### Couche 3 : Données (Stockage & Modèles)

**Base de données MySQL** — 16 tables assurant l'intégrité référentielle :

| Table | Entité | Relations clés |
|-------|--------|----------------|
| `users` | Utilisateurs | Polymorphique vers StudentProfile / TeacherProfile |
| `filieres` | Filières | `1:N` → Groupes |
| `groups` | Groupes | `N:1` → Filière, `1:N` → StudentProfiles, `1:N` → Sessions |
| `modules` | Modules | `1:N` → Sessions |
| `teacher_profiles` | Profils formateurs | `1:1` → User, `1:N` → Sessions |
| `student_profiles` | Profils étudiants | `1:1` → User, `N:1` → Groupe, `1:N` → AttendanceRecords, `1:N` → Justifications |
| `academic_sessions` | Sessions académiques | `N:1` → Module, TeacherProfile, Groupe |
| `attendance_records` | Présences | `N:1` → StudentProfile, Session (statut : présent/absent/retard/justifié) |
| `justifications` | Justificatifs | `N:1` → StudentProfile (statut : pending/accepted/rejected) |
| `module_teacher_group` | Pivot triple | Lien Many-to-Many entre formateurs, modules et groupes |
| `roles` / `permissions` | RBAC | Spatie Permission — rôles : admin, teacher, student |
| `personal_access_tokens` | Tokens API | Sanctum — authentification mobile sécurisée |

**Modèles Eloquent (9) :**
`User`, `Filiere`, `Group`, `Module`, `TeacherProfile`, `StudentProfile`, `Session`, `AttendanceRecord`, `Justification`

**Relations principales :**
```
Filiere → Groups → StudentProfiles → AttendanceRecords
                                       → Justifications
Modules → Sessions → AttendanceRecords
TeacherProfiles → Sessions
          ↕ (module_teacher_group)
Modules ↔ Groups
```

---

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## Réalisation par Sprints (Méthodologie Scrum)

Le développement a été structuré en **Sprints de deux semaines** avec des rôles Scrum (Product Owner, Scrum Master, Équipe de Développement) et les événements associés (Sprint Planning, Daily Stand-up, Sprint Review, Sprint Retrospective).

---

### Sprint 0 : Développement Mobile (30 Mars — 03 Avril 2026)

**Objectif :** Créer l'application mobile native comme interface principale de terrain.

**Exigences adressées :**
- Interface mobile-first pour le pointage rapide des formateurs
- Hub de navigation par rôle (Admin, Formateur, Étudiant)
- Intégration avec l'API backend via `ApiService`

**Tâches réalisées :**
- Mise en place du projet Laravel 13 avec NativePHP
- Création de 7 vues mobiles responsives (Tailwind CSS, Alpine.js)
- Développement du `ApiService` pour la consommation de l'API REST
- Configuration NativePHP (Android SDK, permissions, orientation portrait)
- Layout avec navigation bottom et safe-area insets
- Routes web sous préfixe `/mobile`

**Livrables :**
- Application mobile compilable en APK Android
- Interface Hub (sélection de rôle)
- Vues Formateur (sessions, pointage flash)
- Vues Admin (dashboard, justificatifs)
- Vues Étudiant (assiduité personnelle)

---

### Sprint 1 Partie 1 : API Publique & Pointage (06 Avril — 10 Avril 2026)

**Objectif :** Développer l'API REST backend pour les fonctionnalités de base (auth, sessions, pointage).

**Exigences adressées :**
- HMW 1 : Éliminer le transfert physique des fiches papier
- HMW 2 : Valider chaque session en moins de 30 secondes

**Tâches réalisées :**
- Installation et configuration de Laravel 12
- Mise en place de Sanctum pour l'authentification API token-based
- Création des migrations et modèles de base (users, filieres, groups, modules, sessions)
- Développement de `AuthController` (login, logout, me)
- Développement de `AcademicController` (endpoints GET pour données académiques)
- Développement de `AttendanceController` (enregistrement par session, idempotent)
- Configuration des routes API (15 endpoints RESTful)

**Livrables :**
- API REST fonctionnelle avec authentification
- Endpoints de pointage avec `updateOrCreate` (pas de doublons)
- Routes protégées par middleware `auth:sanctum`

---

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

### Sprint 1 Partie 2 : Administration & RBAC (20 Avril — 24 Avril 2026)

**Objectif :** Ajouter la gestion des rôles, des justificatifs et des statistiques.

**Exigences adressées :**
- HMW 3 : Dématérialiser l'approbation des justificatifs
- Accès par rôles (Admin, Formateur, Étudiant)

**Tâches réalisées :**
- Installation et configuration de Spatie Laravel-Permission
- Création des rôles (admin, teacher, student) et permissions associées
- Développement de `JustificationController` (soumission, upload fichier, workflow validation)
- Développement de `StatsController` (agrégations dashboard)
- Création des modèles restants (TeacherProfile, StudentProfile, AttendanceRecord, Justification)
- Migration de la table pivot `module_teacher_group`
- Création des seeders (CSVSeeder) pour données de test réalistes
- Insertion des données de démonstration (utilisateurs, sessions, profils)

**Livrables :**
- RBAC complet avec 3 rôles
- Workflow justificatifs (soumission → pending → accepted/rejected)
- Upload fichiers (PDF/JPG/PNG, max 2MB) stockés dans `storage/app/public/justifications/`
- Dashboard statistiques temps réel
- Jeu de données de test (CSV)

---

### Sprint 2 : Fonctions Avancées & Dashboard (11 Mai — 15 Mai 2026)

**Objectif :** Enrichir l'application avec des fonctionnalités avancées de reporting et de validation.

**Tâches réalisées :**
- Rafraîchissement du dashboard administratif avec indicateurs visuels (code couleur)
- Affinage du workflow de validation des justificatifs (approbation/rejet)
- Amélioration des exports de données (préparation pour export Excel/PDF)
- Implémentation des alertes de dépassement de quota d'absences
- Optimisation des requêtes avec eager loading pour les dashboards
- Tests et corrections sur les deux projets (API + Mobile)

**Livrables :**
- Dashboard administratif interactif avec métriques clés
- Workflow validation justificatif complet (visualisation + décision)
- Alertes automatisées pour absences répétées
- Performance optimisée (jointures Eloquent, cache)

---

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## Diagrammes de Réalisation

### Diagramme de Séquence — Flux de Pointage

Le flux de pointage mobile suit ce parcours :
1. Formateur s'authentifie (Sanctum token)
2. Sélectionne une session depuis la liste mobile
3. Lance l'écran "Flash" — visualisation des étudiants du groupe
4. Marque les présences (présent/absent/retard) individuellement ou par lot
5. Validation → `POST /api/attendance/record` → `updateOrCreate`
6. Données immédiatement disponibles sur le dashboard Admin

### Diagramme de Déploiement

```
[Application Mobile (NativePHP)] ←→ HTTP/JSON
        ↓
[API REST (Laravel 12)] ←→ MySQL (Attendances, Users, Sessions)
        ↓
[Stockage Fichiers] Justificatifs PDF/images
```

---

## Outils de Développement & Gestion

| Outil | Utilisation |
|-------|-------------|
| **Visual Studio Code** | IDE principal |
| **Git / GitHub** | Gestion de versions et collaboration |
| **Composer** | Gestion des dépendances PHP |
| **NPM / Vite** | Build des assets frontend |
| **PlantUML** | Diagrammes de classes et cas d'utilisation |
| **Postman** | Tests des endpoints API |

---

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## Bilan des Sprints

| Sprint | Statut | Jalons Clés |
|--------|--------|-------------|
| **Sprint 0** — Mobile | ✅ Complété | Application native Android, 7 écrans mobiles, ApiService |
| **Sprint 1 (Partie 1)** — API Publique | ✅ Complété | Auth Sanctum, CRUD sessions/pointage, endpoints académiques |
| **Sprint 1 (Partie 2)** — API Admin | ✅ Complété | RBAC Spatie, justificatifs, stats, seeders CSV |
| **Déploiement & Installation** | ✅ Complété | Configuration serveur, migrations, mise en production |
| **Sprint 2** — Fonctions Avancées | ✅ Complété | Dashboard, workflow validation, alertes, optimisations |
| **Réseau** | ✅ Complété | Infrastructure réseau, DNS, SSL, hébergement |
