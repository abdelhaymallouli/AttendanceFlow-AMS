# 📊 Diagramme de Classe & Architecture Technique : AttendanceFlow-AMS

Ce document présente l'architecture technique détaillée et le diagramme de classes du système de gestion des absences (AMS). L'architecture est pensée autour des principes de **Microservices**, propulsée par **Laravel**, sécurisée avec **Spatie Permission**, et dotée d'un front-end interactif usant de **Tailwind CSS** et **Alpine.js**.

## 🏗️ Architecture Globale (Microservices & Frontend)

- **Frontend (UI Layer)** : Construit en Blade avec un design système en **Tailwind CSS** pour l'interface réactive, et **Alpine.js** pour l'interactivité légère côté client.
- **Microservices (Backend / API Layer)** : 
  - **Auth & IAM Service** : Gère l'authentification et les autorisations (intégré avec Spatie).
  - **Academic Service** : Gère les filières, groupes et sessions.
  - **Attendance Service** : Gère les pointages d'absences et les justifications.
- **Base de données** : Relations inter-services modélisées.

## 📌 Diagramme de Classe détaillé

```mermaid
classDiagram
    %% Spatie / IAM Package
    namespace IAM_Auth_Service {
        class User {
            +int id
            +string name
            +string email
            +string password
            +login()
            +logout()
            +hasRole(role)
            +hasPermissionTo(permission)
        }
        
        class Role {
            +int id
            +string name
            +string guard_name
        }
        
        class Permission {
            +int id
            +string name
            +string guard_name
        }
    }

    %% Academic Package
    namespace Academic_Service {
        class StudentProfile {
            +int id
            +int user_id
            +string matricule
            +string photo
            +int group_id
        }

        class TeacherProfile {
            +int id
            +int user_id
            +string specialty
        }

        class Group {
            +int id
            +string name
            +int filiere_id
        }

        class Filiere {
            +int id
            +string name
            +string code
        }

        class Session {
            +int id
            +time start_time
            +time end_time
            +string type
            +int group_id
            +int teacher_id
        }
    }

    %% Attendance Package
    namespace Attendance_Service {
        class AttendanceRecord {
            +int id
            +int student_id
            +int session_id
            +enum status
            +date date
            +mark()
            +updateStatus()
        }

        class Justification {
            +int id
            +int attendance_record_id
            +string reason
            +string file_path
            +enum status
            +date submitted_at
            +approve()
            +reject()
        }
    }

    %% Relations Spatie (IAM)
    User "*" -- "*" Role : hasRoles
    Role "*" -- "*" Permission : hasPermissions
    User "*" -- "*" Permission : hasDirectPermissions

    %% User to Profiles (One to One)
    User "1" -- "0..1" StudentProfile : extends
    User "1" -- "0..1" TeacherProfile : extends

    %% Academic Relations
    StudentProfile "*" -- "1" Group : belongsTo
    Group "*" -- "1" Filiere : belongsTo
    Session "*" -- "1" Group : scheduled for
    Session "*" -- "1" TeacherProfile : assigned to

    %% Attendance Relations
    AttendanceRecord "*" -- "1" StudentProfile : has
    AttendanceRecord "*" -- "1" Session : corresponds to
    
    AttendanceRecord "1" -- "0..1" Justification : justified by
```

## 🛠️ Choix Technologiques

1. **Laravel (Core & API)** : 
   - Utilisation d'Eloquent ORM pour la modélisation des entités décrites ci-dessus.
   - Les relations complexes (comme `User` avec `Role`, de Many-to-Many via pivot partagés par Spatie) sont nativement supportées.
2. **Spatie Laravel Permission** :
   - L'attribut `role` string basique est remplacé par le modèle relationnel Spatie.
   - Permet une flexibilité maximale où l'Admin, le Teacher et le Student sont de simples `Users` auxquels un `Role` est assigné via la base de données sans redondance structurelle stricte de classe.
3. **Approche Microservices / Modulaire** :
   - Modélisé via les `namespaces` sur le diagramme pour isoler l'identité (`IAM_Auth_Service`), la scolarité (`Academic_Service`) et les présences (`Attendance_Service`). Ces domaines peuvent être de simples modules d'une application monolithique avec Laravel Modules ou de vrais microservices.
4. **Alpine.js & TailwindCSS** :
   - Ils n'apparaissent pas sur le diagramme de *classe du domaine backend* présenté ci-dessus car ils gèrent la **couche Vue**. 
   - Les composants Alpine invoqueront des APIs Laravel ou masqueront/afficheront des éléments UI (Tailwind classes) basé sur les Permissions Spatie réinjectées en variables Blade.
