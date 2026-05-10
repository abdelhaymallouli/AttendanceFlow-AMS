# 🎓 AttendanceFlow-AMS: Système de Gestion des Absences "Direct-to-System"

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-v4.0-06B6D4?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js)](https://alpinejs.dev)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://www.php.net)

**AttendanceFlow-AMS** est une solution numérique premium conçue pour transformer la gestion des absences en milieu académique. En éliminant le "pont humain" entre le support papier et les bases de données Excel, le système offre un flux de travail instantané, précis et transparent pour tous les acteurs de la formation.

---

## 🔍 1. Vision & Analyse (Design Thinking)

Le projet est né d'un constat simple : la gestion traditionnelle des absences est lente, sujette aux erreurs et frustrante. Nous avons appliqué une méthodologie **Design Thinking** pour redéfinir ce processus.

### 🚩 Énoncé du Problème
Le goulot d'étranglement réside dans la transition inefficace du support papier vers la saisie manuelle. Ce processus archaïque génère une surcharge logistique et un décalage d'information critique (Data Lag).

### 👥 Personas
- **Madame Hannane (Administration)** : Souhaite passer de la "saisie" à la "validation stratégique". Elle a besoin d'un dashboard de monitoring global.
- **Imane Bouziane (Formatrice)** : Utilise son smartphone pour pointer une classe en moins de 30 secondes par session (9h-11h, 11h-14h, 14h-17h).
- **Anouar Benyakhelef (Étudiant)** : Cherche une transparence totale sur son assiduité et la possibilité de soumettre ses justificatifs numériquement.

---

## 🛠️ 2. Stack Technique "State-of-the-Art"

Le projet utilise les dernières technologies du Web moderne pour garantir performance et évolutivité :

- **Backend Logic** : **Laravel 12** (PHP 8.2+) exploitant le **Service Pattern** pour un découplage total de la logique métier.
- **Frontend UI** : **Tailwind CSS v4** (Next-gen features) pour un design atomique et **Alpine.js** pour l'interactivité légère.
- **UI Components** : **Preline UI** & **Lucide Icons** pour une expérience utilisateur premium et consistante.
- **Sécurité & RBAC** : **Spatie Laravel Permission** pour une gestion granulaire des accès (Admin, Teacher, Student).
- **Tooling** : **Vite** pour le bundling ultra-rapide et **Concurrently** pour un environnement de développement optimisé.

---

## 🏗️ 3. Architecture & Services

L'application est structurée autour de services spécialisés (SOLID principles) :

- **Identity Service** : Gestion sécurisée des comptes et des profils hybrides (Student/Teacher).
- **Academic Service** : Modélisation de la structure scolaire (Filières, Groupes, Modules, Sessions).
- **Attendance Service** : Moteur de pointage en temps réel avec gestion des états (`present`, `absent`, `late`, `justified`).
- **Justification Service** : Workflow numérique de soumission et d'approbation des preuves (certificats médicaux).
- **Reporting Service** : Moteur d'analytics pour la détection des étudiants "At-Risk" et génération de rapports.

---

## 📅 4. Roadmap & Planification

| Phase | Milestone | État |
| :--- | :--- | :--- |
| **Week 0** | Mobile Development (Concept) | ✅ Terminé |
| **Week 1-3** | Sprint 1 : Core Web (Admin & Public) | 🚀 En cours |
| **Week 4** | Deployment & Installation | 📅 À venir |
| **Week 6-8** | Sprint 2 : Fonctions Avancées & Réseau | 📅 À venir |

---

## 🚦 5. Installation & Configuration

```bash
# 1. Cloner le projet
git clone https://github.com/abdelhaymallouli/AttendanceFlow-AMs.git

# 2. Installer les dépendances Backend
composer install

# 3. Installer les dépendances Frontend
npm install

# 4. Configuration Environnement
cp .env.example .env
php artisan key:generate

# 5. Base de données
# Assurez-vous d'avoir configuré votre DB dans le fichier .env
php artisan migrate --seed

# 6. Lancement (Dev mode)
# Utilise concurrently pour lancer artisan serve et vite en parallèle
npm run dev
```

---

## 📂 6. Structure du Dépôt

- `📂 Analyse/` : Documents de conception (HMW, Cas d'utilisation, Diagrammes).
- `📂 AttendanceFlow-AMS/` : Application principale Laravel.
- `📂 AttendanceFlow-AMS-Mobile/` : Preuve de concept mobile (Sprint 0).
- `📂 documentation/` : Wiki technique détaillé (Schema DB, Logiciel, Views).

---
*Développé avec ❤️ par Abdelhay Mallouli dans le cadre d'un Projet de Fin de Formation.*


## What's Changed                                                                                                                                                                                     
                                                                                                                        LSP   ill activate as files are read       ▼ Modified Files                        
     ### Dashboard (`teacher/dashboard.blade.php`)                                                                      LSPs w                                     Analyse/cas_utilisation/mobile/glo+1    
     - Redesigned to match `maquete/teacher-dashboard.html` and Preline design                                                fied Files                            Analyse/cas_utilisation/web/global+5   
       system                                                                                                           ▼ Modise/cas_utilisation/mobile/glo+1   
     - Gradient current-session banner with pulse dot indicator                                                         Analys────────────────────────────+1────   Analyse/cas_utilisation/web/sprint+3         - 4 stat cards: Total Students, Present Today, Absent Today, Pending                                               Analyse/cas_utilisation/web/global+5       AttendanceFlow-AMS/app/Http/Co+31 -1    
       Justifications                                                                                                   Analyse/cas_utilisation/web/sprint+3       AttendanceFlow-AMS/app/Http/C+95 -13    
     - Quick Actions panel (Take Attendance, View Sessions, Export Report, etc.)                                        AttendanceFlow-AMS/app/Http/Co+31 -1       AttendanceFlow-AMS/app/Http/Contr+89    
     - My Classes Overview with per-group attendance rates (color-coded)                                                AttendanceFlow-AMS/app/Http/C+95 -13       AttendanceFlow-AMS/app/Models/Sess+5    
     - Session list view with status-colored rows (blue=active, green=completed,
        gray=upcoming)                                                                                                   LSP   ill activate as files are read       ▼ Modified Files                        
     - Dynamic recent activity feed from DB (last 5 attendance + last 3                                                 LSPs w                                     Analyse/cas_utilisation/mobile/glo+1    
       justifications)                                                                                                        fied Files                            Analyse/cas_utilisation/web/global+5   
     - Bugfix: sessions filtered to `whereDate('start_time', today())` —                                                ▼ Modise/cas_utilisation/mobile/glo+1   
       previously showed ALL sessions regardless of date                                                                Analys────────────────────────────+1────   Analyse/cas_utilisation/web/sprint+3                                                                                                                            Analyse/cas_utilisation/web/global+5       AttendanceFlow-AMS/app/Http/Co+31 -1    
     ### Attendance Entry (index + show)                                                                                Analyse/cas_utilisation/web/sprint+3       AttendanceFlow-AMS/app/Http/C+95 -13    
     - **index**: Session picker with date filter — was completely broken                                               AttendanceFlow-AMS/app/Http/Co+31 -1       AttendanceFlow-AMS/app/Http/Contr+89    
       (`attendanceApp` function undefined). Now works: Alpine component stores                                         AttendanceFlow-AMS/app/Http/C+95 -13       AttendanceFlow-AMS/app/Models/Sess+5    
       sessions, date change redirects to `?date=YYYY-MM-DD`  
        - Radio buttons with `peer-checked:` CSS (replaces Alpine @click +                                                                                                                                  
         hidden inputs)                                                                                                 LSP   ill activate as files are read       ▼ Modified Files                        
       - `<x-student-attendance-row>` component (shared with admin)                                                     LSPs w                                     Analyse/cas_utilisation/mobile/glo+1    
       - Inline Alpine `x-data` with DOM-based stats and search                                                               fied Files                            Analyse/cas_utilisation/web/global+5   
       - Removed broken `$studentsData` reference                                                                       ▼ Modise/cas_utilisation/mobile/glo+1   
       - Removed custom `@push('scripts')` and `<style>` block                                                          Analys────────────────────────────+1────   Analyse/cas_utilisation/web/sprint+3           - Removed toast notification (wasn't in admin version)                                                           Analyse/cas_utilisation/web/global+5       AttendanceFlow-AMS/app/Http/Co+31 -1    
                                                                                                                        Analyse/cas_utilisation/web/sprint+3       AttendanceFlow-AMS/app/Http/C+95 -13    
     ### Self-Service Session Creation (new)                                                                            AttendanceFlow-AMS/app/Http/Co+31 -1       AttendanceFlow-AMS/app/Http/Contr+89    
     - `Teacher\SessionController` with `create()` and `store()` methods                                                AttendanceFlow-AMS/app/Http/C+95 -13       AttendanceFlow-AMS/app/Models/Sess+5    
     - Module + group dropdowns filtered to teacher's assigned pairs via
         `module_teacher_group` pivot                                                                                                                                                                        
     - Pivot validation on store (rejects unassigned combinations)                                                      LSP   ill activate as files are read       ▼ Modified Files                        
     - SchedulingService conflict detection                                                                             LSPs w                                     Analyse/cas_utilisation/mobile/glo+1    
     - Alpine.js duration calculator on the form                                                                              fied Files                            Analyse/cas_utilisation/web/global+5   
                                                                                                                        ▼ Modise/cas_utilisation/mobile/glo+1   
     ### Bug Fixes                                                                                                      Analys────────────────────────────+1────   Analyse/cas_utilisation/web/sprint+3         - `SQLSTATE[42S22]: Unknown column 'groups'`: `pluck('groups')` →                                                  Analyse/cas_utilisation/web/global+5       AttendanceFlow-AMS/app/Http/Co+31 -1    
       `pluck('groups.id')` in `DashboardController`                                                                    Analyse/cas_utilisation/web/sprint+3       AttendanceFlow-AMS/app/Http/C+95 -13    
     - `attendanceApp is not defined`: defined the missing Alpine function in                                           AttendanceFlow-AMS/app/Http/Co+31 -1       AttendanceFlow-AMS/app/Http/Contr+89    
       `index.blade.php`                                                                                                AttendanceFlow-AMS/app/Http/C+95 -13       AttendanceFlow-AMS/app/Models/Sess+5    
     - `$studentsData` undefined: removed from `show.blade.php` (DOM-based
          approach)                                                                                                                                                                                           
                                                                                                                        LSP   ill activate as files are read       ▼ Modified Files                        
     ### Seed Data                                                                                                      LSPs w                                     Analyse/cas_utilisation/mobile/glo+1    
     - New `database/data/module_teacher_group.csv` with 27 pivot rows                                                        fied Files                            Analyse/cas_utilisation/web/global+5   
     - Expanded users from 10→24, sessions updated to May 11–15 2026                                                    ▼ Modise/cas_utilisation/mobile/glo+1   
     - Uncommented attendance_records and justifications CSV seeding                                                    Analys────────────────────────────+1────   Analyse/cas_utilisation/web/sprint+3         - Added `seedModuleTeacherGroup()` to `CsvSeeder`                                                                  Analyse/cas_utilisation/web/global+5       AttendanceFlow-AMS/app/Http/Co+31 -1    
                                                                                                                        Analyse/cas_utilisation/web/sprint+3       AttendanceFlow-AMS/app/Http/C+95 -13    
     ### Routes Added                                                                                                   AttendanceFlow-AMS/app/Http/Co+31 -1       AttendanceFlow-AMS/app/Http/Contr+89    
     - `GET /teacher/sessions/create` → `teacher.sessions.create`                                                       AttendanceFlow-AMS/app/Http/C+95 -13       AttendanceFlow-AMS/app/Models/Sess+5    
     - `POST /teacher/sessions` → `teacher.sessions.store` 