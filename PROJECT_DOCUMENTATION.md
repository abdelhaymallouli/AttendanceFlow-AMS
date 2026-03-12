# 🎓 AttendanceFlow-AMS: Project Documentation

This document provides a comprehensive overview of the **AttendanceFlow-AMS** project, including its structure, functional use cases, and technical data model.

---

## 📁 File & Folder Structure

The project is organized to separate analysis, design, and implementation prototypes.

### 🏛️ Root Directory
*   `Analyse/`: Contains all functional and technical analysis documents.
*   `Presentation/`: Holds presentation materials and visual assets.
*   `Raport/`: Detailed final project reports.
*   `maquete/`: High-fidelity web UI prototypes (HTML/CSS).
*   `maquete-mobile/`: Mobile-first UI prototypes for field operations.
*   `README.md`: Project introduction and vision.

### 🔍 Analysis (`Analyse/`)
*   `cas_utilisation/`: UML Use Case diagrams (Global, Sprint 1, Sprint 2) for Web and Mobile.
*   `diagramme_de_classe/`: Data model and class architecture.
*   `définition_de_problème/`: Synthesis of core challenges and "How Might We" questions.
*   `Empathie/`: Mind maps and persona studies.

---

## 🎭 Use Case Diagrams

AttendanceFlow-AMS serves three primary personas: **Administrators**, **Trainers**, and **Students**.

### 🌐 Global Web Platform
Focuses on administrative management, reporting, and justification validation.

```mermaid
graph LR
    subgraph "Plateforme Web (AttendanceFlow-AMS)"
        UC_W01(S'authentifier)
        UC_W03(Visualiser le Dashboard)
        UC_W05(Gérer les justificatifs)
        UC_W05a(Valider / Réfuter un justificatif)
        UC_W02(Gérer les comptes & structures)
        UC_W_ABS(Gestion avancée des absences)
        UC_W07(Exporter les rapports)
        UC_W04(Soumettre un justificatif)
        UC_W06(Consulter l'historique)
    end

    Admin((Administrateur))
    Trainer((Formateur))
    Student((Étudiant))

    Admin --> UC_W03
    Admin --> UC_W02
    Admin --> UC_W05
    Admin --> UC_W_ABS

    Trainer --> UC_W03
    Trainer --> UC_W_ABS
    Trainer --> UC_W05

    Student --> UC_W03
    Student --> UC_W04
    Student --> UC_W06

    UC_W03 -.-> |include| UC_W01
    UC_W04 -.-> |include| UC_W01
    UC_W05 -.-> |include| UC_W01
    UC_W02 -.-> |include| UC_W01
    UC_W06 -.-> |include| UC_W01
    UC_W_ABS -.-> |include| UC_W01

    UC_W05a -.-> |extend| UC_W05
    UC_W07 -.-> |extend| UC_W03
```

### 📱 Global Mobile Application
Optimized for rapid field operations (attendance marking and quick consultation).

```mermaid
graph LR
    subgraph "Application Mobile (AttendanceFlow-AMS)"
        UC_M01(S'authentifier)
        UC_M02(Pointer les absences - Flash)
        UC_M02a(Saisir motif de retard)
        UC_M06(Consulter ses absences)
        UC_M08(Vérifier statut de session)
    end

    Trainer((Formateur))
    Admin((Administrateur))
    Student((Étudiant))

    Trainer --> UC_M01
    Trainer --> UC_M02
    Trainer --> UC_M02a

    Admin --> UC_M01
    Admin --> UC_M02

    Student --> UC_M01
    Student --> UC_M06
    Student --> UC_M08

    UC_M02a -.-> |extend| UC_M02
```

---

## 📊 Class Diagram

The system uses a modular architecture separating Identity (IAM), Academic structure, and Attendance tracking.

```mermaid
classDiagram
    namespace IAM_Auth_Service {
        class User {
            +int id
            +string name
            +string email
            +login()
            +hasRole()
        }
        class Role {
            +int id
            +string name
        }
        class Permission {
            +int id
            +string name
        }
    }

    namespace Academic_Service {
        class StudentProfile {
            +int id
            +string matricule
            +int group_id
        }
        class TeacherProfile {
            +int id
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
        }
        class Module {
            +int id
            +string code
            +string name
            +float coefficient
        }
        class Session {
            +int id
            +time start_time
            +time end_time
            +float duration_hours
            +int group_id
            +int teacher_id
            +int module_id
        }
    }

    namespace Attendance_Service {
        class AttendanceRecord {
            +int id
            +int student_id
            +int session_id
            +enum status
            +date date
        }
        class Justification {
            +int id
            +int student_id
            +string reason
            +date start_date
            +date end_date
            +enum status
        }
    }

    %% Relationships
    User "1" -- "0..1" StudentProfile : has
    User "1" -- "0..1" TeacherProfile : has
    User "*" -- "*" Role : assigned
    Role "*" -- "*" Permission : grants

    StudentProfile "*" -- "1" Group : belongs to
    Group "*" -- "1" Filiere : part of
    
    TeacherProfile "*" -- "*" Module : teaches
    TeacherProfile "*" -- "*" Group : manages
    
    Session "*" -- "1" Group : for
    Session "*" -- "1" TeacherProfile : taught by
    Session "*" -- "1" Module : focused on

    AttendanceRecord "*" -- "1" StudentProfile : for
    AttendanceRecord "*" -- "1" Session : in
    
    StudentProfile "1" -- "*" Justification : provides
```

---

## 🗄️ Implémentation de la Base de Données

Le projet utilise une architecture de base de données structurée pour gérer les utilisateurs, les profils académiques et le suivi des présences.

### Système de Test et Seeding (CSV)
Pour faciliter le développement et les tests, un système de seeding basé sur des fichiers CSV a été mis en œuvre. 

*   **Fichiers de données** : Situés dans `database/data/`.
*   **Guide de Test** : Pour les instructions détaillées sur la migration et le seeding, consultez le [DATABASE_TESTING_GUIDE.md](DATABASE_TESTING_GUIDE.md).
*   **Commande principale** : `php artisan db:seed --class=CsvSeeder`

---

## 🏗️ Architecture Modulaire (Services)

Le projet adopte une architecture orientée services (Domain-Driven Design) pour garantir la modularité et l'évolutivité. 

Pour une conception détaillée de cette architecture en services indépendants (ex: `AcademicService`, `AttendanceService`, `IdentityService`), veuillez consulter le document :
*   [**Conception de l'Architecture des Services**](ARCHITECTURE_DESIGN.md)

---

## 🛠️ Tech Stack

*   **Backend**: Laravel 12 (PHP 8.2+)
*   **Frontend**: Blade, Tailwind CSS, Alpine.js
*   **Database**: MySQL
*   **Permissions**: Spatie Laravel-Permission
*   **Methodology**: Agile (Scrum), Design Thinking
