# Documentation de la Base de Données : AttendanceFlow-AMS

Ce document détaille la structure physique de la base de données de l'application AttendanceFlow-AMS, reflétant l'état actuel des migrations Laravel. Cette architecture supporte le modèle de "Microservices" logique décrit dans l'analyse de conception.

## 🏗️ Architecture Globale

La base de données est structurée autour de trois domaines principaux :
1. **IAM & Auth Service** : Authentification et gestion des rôles/permissions.
2. **Academic Service** : Gestion de la scolarité (Filières, Groupes, Modules, Profils, Emploi du temps).
3. **Attendance Service** : Suivi des présences et justifications des absences.

---

## 📚 1. IAM & Authentication

La sécurité est gérée par les fonctionnalités natives de Laravel et le package `Spatie/Laravel-Permission`.

### `users`
Table principale d'authentification pour tous les acteurs du système.
- `id` (PK)
- `name` (string) : Nom complet.
- `email` (string, unique) : Email de connexion.
- `password` (string) : Mot de passe haché.
- `email_verified_at` (timestamp, nullable)

### Tables Spatie Permission
Ces tables gèrent le RBAC (Role-Based Access Control).
- **`roles`** : `id`, `name`, `guard_name` (Ex: Admin, Teacher, Student).
- **`permissions`** : `id`, `name`, `guard_name` (Ex: mark attendance, view reports).
- **`model_has_roles`** : Table pivot liant un `User` à un `Role`.
- **`role_has_permissions`** : Table pivot liant un `Role` à une `Permission`.

---

## 🎓 2. Academic Service

Ce module gère toute la structure académique de l'établissement.

### `filieres`
Les branches ou filières d'études.
- `id` (PK)
- `name` (string) : Nom complet (ex: Développement Informatique).
- `code` (string, unique) : Code court (ex: DEV).

### `groups`
Les classes ou groupes d'étudiants.
- `id` (PK)
- `name` (string) : Nom du groupe (ex: DEV101).
- `filiere_id` (FK) : Référence la table `filieres`.

### `modules`
Les matières ou modules enseignés.
- `id` (PK)
- `name` (string) : Nom complet du module.
- `code` (string, unique) : Code d'identification.
- `coefficient` (float) : Importance du module (Défaut: 1.0).

### Profils Utilisateurs
Le système utilise un modèle hybride où les informations spécifiques au rôle sont stockées dans des profils liés au compte `users`.

#### `student_profiles`
- `id` (PK)
- `user_id` (FK) : Référence `users` (Cascade On Delete).
- `matricule` (string, unique) : Numéro d'étudiant unique.
- `group_id` (FK) : Référence `groups`. Détermine la classe de l'étudiant.

#### `teacher_profiles`
- `id` (PK)
- `user_id` (FK) : Référence `users` (Cascade On Delete).
- `specialty` (string, nullable) : Spécialité ou département d'enseignement.

### `module_teacher_group` (Affectations Pédagogiques)
Une table pivot ternaire très puissante qui répond à la question : *Quel professeur enseigne quel module à quel groupe ?*
- `id` (PK)
- `module_id` (FK)
- `teacher_profile_id` (FK)
- `group_id` (FK)

### `academic_sessions` (Séances / Emploi du temps dynamique)
Représente un cours programmé à un instant T. Nommée `academic_sessions` pour éviter un conflit avec la table `sessions` web de Laravel.
- `id` (PK)
- `module_id` (FK) : La matière enseignée.
- `teacher_profile_id` (FK) : Le professeur qui donne le cours.
- `group_id` (FK) : Le groupe concerné.
- `start_time` (timestamp) : Heure de début.
- `end_time` (timestamp) : Heure de fin.
- `duration_hours` (float) : Durée calculée du cours (Défaut: 2.5).
- `type` (string) : Format du cours (ex: `CM` pour Cours Magistral, `TD`, `TP`).

---

## ⏱️ 3. Attendance Service

Le cœur métier de l'application : le suivi de l'assiduité.

### `attendance_records` (Pointages)
Stocke l'état de présence d'un étudiant spécifique lors d'une session spécifique.
- `id` (PK)
- `student_profile_id` (FK) : L'étudiant pointé.
- `session_id` (FK) : Référence `academic_sessions`. Le cours concerné.
- `status` (enum) : L'état du pointage (`present`, `absent`, `late`, `justified`). Défaut: `present`.
- `date` (date) : Raccourci pour requêtes rapides (dérivé du start_time de la session).

### `justifications` (Justificatifs d'absence)
Gestion des documents (certificats médicaux, convocations) soumis par les étudiants pour justifier des absences sur une plage de dates.
- `id` (PK)
- `student_profile_id` (FK) : L'étudiant concerné.
- `reason` (text) : Motif de l'absence.
- `file_path` (string, nullable) : Chemin (storage) vers le fichier uploadé comme preuve.
- `start_date` (date) : Date de début de l'absence à justifier.
- `end_date` (date) : Date de fin de l'absence à justifier.
- `status` (enum) : État de la demande (`pending`, `accepted`, `rejected`). Défaut: `pending`.
- `submitted_at` (timestamp) : Date et heure d'envoi.

---
*Note : Cette documentation reflète l'état du dossier `database/migrations` au dernier audit.*
