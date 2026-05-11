# Documentation des Services (Couche Métier) : AttendanceFlow-AMS

Cette documentation décrit la couche de services qui encapsule toute la logique métier de l'application, suivant les principes SOLID pour garantir la maintenabilité et l'évolutivité.

## 🏗️ Architecture Globale

Tous les services héritent d'une classe abstraite `BaseService` qui fournit des fonctionnalités transversales :
- **Journalisation (Logging)** : Méthodes standardisées pour loguer les informations et les erreurs via `logInfo` et `logError`.
- **Télémétrie** : Chaque service définit son propre nom via `getServiceName()` pour un suivi précis.

---

## 🛠️ Analyse des Services

L'application est découpée en services par domaine fonctionnel :

### 1. `IdentityService`
Gère l'identité et la sécurité.
- **Responsabilités** : Authentification (`authenticate`), inscription des utilisateurs (`registerUser`), déconnexion (`logout`).
- **Idempotence** : Utilise les mécanismes de hachage sécurisés de Laravel (`Hash::make`).

### 2. `AcademicService`
Gère la hiérarchie scolaire et les profils.
- **Responsabilités** : Inscription des étudiants (`enrollStudent`), enregistrement des professeurs (`registerTeacher`), affectation pédagogique (`assignTeacherToModuleAndGroup`).
- **Gestion des Profils** : Assure la création ou mise à jour des profils liés aux comptes utilisateurs.

### 3. `AttendanceService`
Cœur métier du suivi des présences.
- **Responsabilités** : Pointage individuel (`markAttendance`), pointage de groupe/session (`bulkMarkAttendance`), récupération des présences par séance.

### 4. `SchedulingService`
Gère la planification temporelle.
- **Responsabilités** : Planification des séances (`scheduleSession`), détection des conflits d'emploi du temps pour les professeurs.
- **Logique Métier** : Inclut une détection automatique de chevauchement de créneaux pour un même enseignant.

### 5. `JustificationService`
Gère le cycle de vie des absences justifiées.
- **Responsabilités** : Soumission des documents (`submitJustification`), révision administrative (`reviewJustification`).
- **Automatisme** : Lors de l'acceptation d'une justification, le service transforme automatiquement les statuts `absent` en `justified` pour les séances comprises dans la période.

### 6. `ReportingService`
Génère des analyses et statistiques.
- **Responsabilités** : Calcul des taux de présence par groupe sur une période donnée via `generateGroupReport`.

---

## 📐 Principes SOLID Appliqués

| Principe | Application dans le projet |
| :--- | :--- |
| **S** (Single Responsibility) | Découpage par domaines fonctionnels clairs. Chaque service n'a qu'une seule raison de changer. |
| **O** (Open/Closed) | Utilisation de `BaseService` pour les comportements communs sans modifier les services individuels. |
| **L** (Liskov Substitution) | Les services peuvent être traités comme des instances de `BaseService` sans altérer le comportement du système. |
| **I** (Interface Segregation) | Les méthodes sont granulaires et répondent à des besoins spécifiques du domaine. |
| **D** (Dependency Inversion) | Les services agissent comme une barrière de protection entre les données (Models) et l'interface utilisateur (Controllers). |

---

## 🚀 Recommandations d'Amélioration

1. **Abstraction par Interfaces** : Définir des contrats (Interfaces) pour chaque service afin de faciliter le test unitaire et le remplacement de composants.
2. **DTO (Data Transfer Objects)** : Utiliser des DTO pour passer les données aux services au lieu de tableaux associatifs, afin de garantir le typage fort des paramètres.

