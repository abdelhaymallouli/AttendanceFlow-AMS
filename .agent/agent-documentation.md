# SoliQuiz Agent System — Comprehensive Documentation

## 1. System Overview

SoliQuiz is a **Laravel-based QCM (Quiz) management platform** for Solicode training center. The agent system is a configuration-driven framework that generates the full application through three core skills (architect, builder, developer), six specialized sub-skills, and 15 triggered workflows. It produces a three-role platform (Admin, Formateur, Student) with hierarchical pédagogie management, timed QCM passation, /20-scale scoring, and role-based dashboards with KPIs.

---

## 2. Per-Agent Spec Sheets

### 2.1 Core Skill: `soliquiz-architect`

| Field | Detail |
|-------|--------|
| **Name** | soliquiz-architect |
| **Purpose** | Database architecture & Eloquent models |
| **Tech Stack** | PHP 8.4, Laravel 12.x, MySQL 8.0, Spatie Permission, Laravel Sanctum |
| **Trigger** | Always-on (referenced by workflows) |
| **Input** | Workflow context (model specs, migration requirements) |
| **Output** | Migration files, Model classes with relationships, FK constraints |
| **Workflow** | 14 migration tables in order: users → classes → seances → ua → competences → qcms → competence_qcm → questions → options → tentatives → reponses → choix_reponses → Spatie tables → Sanctum tokens |
| **State** | Stateless (generates static files) |
| **Error Handling** | Not specified; relies on Laravel migration rollback |
| **Config** | `type_profil` enum, `decimal(5,1)` scores, `HasRoles`/`HasApiTokens` traits |

### 2.2 Core Skill: `soliquiz-builder`

| Field | Detail |
|-------|--------|
| **Name** | soliquiz-builder |
| **Purpose** | Business logic layer (services, scoring, passation) |
| **Tech Stack** | PHP 8.4, Laravel 12.x, MySQL 8.0 |
| **Trigger** | Always-on (referenced by workflows) |
| **Input** | Workflow context (service requirements) |
| **Output** | 9 service classes (User, Dashboard, Classe, Seance, Qcm, QcmPublic, Passation, Etudiant, Resultat) |
| **Workflow** | Thin Controller → Service → Transactional DB operations → View/Response |
| **State** | Stateless (stateless services injected via DI) |
| **Error Handling** | `DB::transaction()` with rollback on failure; `findOrFail()` for 404s; custom validation via FormRequests |
| **Config** | /20 scale scoring algorithm; `statut` enum (brouillon/publie/clos) |

### 2.3 Core Skill: `soliquiz-developer`

| Field | Detail |
|-------|--------|
| **Name** | soliquiz-developer |
| **Purpose** | Frontend (Blade templates, Alpine.js, Tailwind) |
| **Tech Stack** | Blade, Alpine.js 3.x, Tailwind CSS 3.x, Vite, Lucide Icons |
| **Trigger** | Always-on (referenced by workflows) |
| **Input** | Workflow context (page/component requirements) |
| **Output** | Blade layout, components, Alpine.js controllers, CSS |
| **Workflow** | Atomic Design (atoms → molecules → organisms → pages) |
| **State** | Client-side: Alpine.js stores, `localStorage` for passation auto-save |
| **Error Handling** | Alpine.js validation, toast notifications (auto-dismiss 4s), disabled buttons |
| **Config** | Ocean Teal primary scheme; score color conventions (emerald >=14/20, amber 10-13/20, rose <10/20) |

### 2.4 Specialized Skills (6 sub-skills)

| Skill | Domain | Capabilities | Dependencies |
|-------|--------|-------------|-------------|
| `passation-service` | Attempt lifecycle | `demarrer()`, `soumettre()`, `calculerScore()`, timer management, auto-save | `soliquiz-architect` (models), `soliquiz-builder` (services) |
| `qcm-engine` | QCM CRUD | Transactional creation, question/option management, state machine (draft/published/archived) | `soliquiz-architect` (models) |
| `pedagogie-manager` | Pédagogie tree | Seance/UA/Competence CRUD, hierarchical tree display, expand/collapse | `soliquiz-architect` (models) |
| `classe-manager` | Class management | Classe CRUD, formateur assignment, student enrollment, class stats | `soliquiz-architect` (models) |
| `dashboard-analytics` | KPIs & dashboards | Admin/Formateur/Student KPI calculation, role-specific widgets | `soliquiz-builder` (services) |
| `developpeur-front` | Frontend integration | Mockup-to-code transformation, asset optimization, clean code | `soliquiz-developer` (foundation) |

### 2.5 Rules Agents (always-on)

| Rule | Type | Content |
|------|------|---------|
| `master_instructions.md` | System | SPA via Alpine.js, Blade components, Service Layer, Eloquent-only, no double-hashing, decimal scores, French comments, Tailwind-first, Lucide icons |
| `atomic_design.md` | System | Three-tier component architecture (atoms/molecules/organisms) |
| `qcm_builder.md` | Component | Multi-step wizard (metadata -> questions -> review) with Alpine.js state |
| `pedagogie_tree.md` | Component | Hierarchical tree display, expand/collapse, CRUD modals |
| `passation_interface.md` | Component | Full-screen passation, timer, navigation, auto-save |
| `service_layer.md` | Data | Thin Controller / Rich Service pattern, service registry |
| `qcm_schema.md` | Data | Entity relationships, migration order, key constraints |
| `access_control.md` | Roles | Three-role hierarchy, Spatie setup, middleware, helpers |
| `identity.md` | Visual | Brand colors, typography, component patterns, responsive breakpoints |
| `optimisation-tokens.md` | System | Token optimization: no pleasantries, lazy reading, diff-only output |
| `stack-technique.md` | Resource | Full tech stack, directory structure, technical prohibitions |

### 2.6 Workflow Agents (trigger-based)

| Trigger | Module | Description |
|---------|--------|-------------|
| `/install` | Shared | Laravel project creation, DB setup, dependency install, Tailwind/Alpine config |
| `/phase1-auth` | Shared | Authentication scaffolding, LoginController, User model, routes, login view |
| `/api-mobile` | Shared | RESTful API (Sanctum), Auth/QCM/Tentative controllers, JSON response format |
| `/admin-dashboard` | Admin | KPI cards, recent activity, top QCMs |
| `/admin-users` | Admin | User CRUD, role assignment, search/filter, pagination |
| `/admin-pedagogie` | Admin | Pédagogie tree view, Seance/UA/Competence CRUD |
| `/admin-classes` | Admin | Card-based class management, formateur assignment |
| `/formateur-dashboard` | Formateur | KPI cards (classes, students, QCMs), class list, recent QCMs |
| `/formateur-biblio` | Formateur | QCM library with search/filter, publish/unpublish toggle |
| `/formateur-create-qcm` | Formateur | 3-step QCM creation wizard with Alpine.js state |
| `/formateur-resultats` | Formateur | Cohort analytics, QCM filter, stats cards, attempt table, CSV export |
| `/student-dashboard` | Student | KPI cards, in-progress section, recent attempts |
| `/student-biblio` | Student | Tabbed QCM library (A Faire/En Cours/Termines), search/filter |
| `/student-passation` | Student | Timed QCM interface with auto-save, timer, navigation |
| `/student-resultats` | Student | Score display, pass/fail, per-question breakdown, retry option |

---

## 3. Workflow Diagram (Text-Based)

```
                        +--------------------------+
                        |    Shared Workflows       |
                        |  /install -> /phase1-auth  |----> Auth System
                        |  /api-mobile              |----> API Routes
                        +----------+---------------+
                                   |
                                   v
    +----------------+----------------------+------------------+
    |                |                      |                  |
    v                v                      v                  v
+---------+   +----------+   +-----------------+   +-----------------+
| ADMIN   |   | ADMIN    |   | FORMATEUR        |   | STUDENT         |
| Module  |   | Module   |   | Module           |   | Module          |
+---------+   +----------+   +-----------------+   +-----------------+
|/admin-  |   |/admin-   |   |/formateur-       |   |/student-        |
|dashboard|   |users/    |   |dashboard         |   |dashboard        |
|         |   |pedagogie/|   |/formateur-biblio |   |/student-biblio  |
|         |   |classes   |   |/formateur-       |   |/student-        |
|         |   |          |   |create-qcm        |   |passation        |
|         |   |          |   |/formateur-       |   |/student-        |
|         |   |          |   |resultats         |   |resultats        |
+----+----+   +----+-----+   +--------+--------+   +--------+--------+
     |             |                  |                      |
     +-------------+------------------+----------------------+
                                   |
                                   v
                    +-----------------------------+
                    |      Skill Layer            |
                    |                              |
         +----------+----------+                  |
         v          v          v                  |
   +---------+ +--------+ +----------+            |
   |architect| | builder| |developer |            |
   |(DB/     | |(Logic/ | |(UI/      |            |
   | Models) | |Services)| |Frontend) |            |
   +----+----+ +---+----+ +----+-----+            |
        |          |           |                   |
        v          v           v                   |
   +-------------------------------------+         |
   |      Rule Layer (always-on)         |<--------+
   | master_instructions, atomic_design, |
   | service_layer, access_control,      |
   | visual_identity, optimisation       |
   +-------------------------------------+
```

### Data Flow Between Agents

```
User triggers /command
    |
    v
Workflow reads Dependencies (skills + rules)
    |
    +---> Loads Skill(s) for implementation logic
    +---> Loads Rule(s) for conventions/constraints
    |
    v
Skill generates code per its domain:
    +-- architect -> migrations, models, relationships
    +-- builder -> services, scoring, transactions
    +-- developer -> Blade, Alpine.js, Tailwind
        |
        v
Workflow produces Validation Checklist (tracked output)
```

### Inter-Agent Communication

| From | To | Mechanism |
|------|----|-----------|
| Workflow | Skills | Explicit `Dependencies:` in frontmatter |
| Workflow | Rules | Explicit `Dependencies:` referencing rules |
| `soliquiz-builder` | `soliquiz-architect` | Imports Eloquent models from architect |
| `soliquiz-developer` | `soliquiz-builder` | Consumes service data via `@json()` in views |
| `passation-service` | `soliquiz-builder` | Extends `PassationService` logic |
| `qcm-engine` | `soliquiz-builder` | Extends `QcmService` logic |
| `dashboard-analytics` | `soliquiz-builder` | Uses `DashboardService` KPI methods |
| `classe-manager` | `soliquiz-builder` | Uses `ClasseService` |
| `pedagogie-manager` | `soliquiz-builder` | Uses `SeanceService` |
| `developpeur-front` | `soliquiz-developer` | Consumes generated UI patterns |

---

## 4. Stack Summary

| Layer | Technology | Version | Purpose |
|-------|-----------|---------|---------|
| **Runtime** | PHP | 8.4+ | Server-side execution |
| **Framework** | Laravel | 12.x | MVC architecture, Eloquent ORM |
| **Database** | MySQL | 8.0 | Relational data store |
| **Auth (Web)** | Laravel Session | -- | Browser-based authentication |
| **Auth (API)** | Laravel Sanctum | -- | Token-based mobile API auth |
| **Permissions** | Spatie Laravel Permission | -- | Role-based access control |
| **Templating** | Blade | -- | Server-side views |
| **CSS** | Tailwind CSS | 3.x | Utility-first styling (Ocean Teal) |
| **JS** | Alpine.js | 3.x | Reactive UI (SPA-like) |
| **Icons** | Lucide | -- | SVG icon set |
| **Build** | Vite | -- | Asset bundling & HMR |
| **Scoring** | Custom algorithm | -- | /20 scale, `decimal(5,1)` |
| **Clients** | Web (Blade+Alpine) + Mobile (REST API) | -- | Dual delivery |

---

## 5. Dependency Map

### Agent Dependencies

```
workflows/admin/dashboard
  +-- skill: dashboard-analytics
  +-- rules: service_layer, access_control

workflows/admin/gestion-utilisateurs
  +-- skill: classe-manager
  +-- rules: access_control, service_layer

workflows/admin/pedagogie
  +-- skill: pedagogie-manager
  +-- rules: pedagogie_tree, qcm_schema

workflows/admin/classes
  +-- skill: classe-manager
  +-- rules: service_layer, access_control

workflows/formateur/dashboard
  +-- skill: dashboard-analytics
  +-- rules: service_layer

workflows/formateur/bibliotheque
  +-- skill: qcm-engine
  +-- rules: qcm_builder

workflows/formateur/creation-qcm
  +-- skill: qcm-engine
  +-- rules: qcm_builder

workflows/formateur/resultats
  +-- skills: passation-service, dashboard-analytics
  +-- rules: service_layer

workflows/student/bibliotheque
  +-- skill: qcm-engine
  +-- rules: qcm_schema

workflows/student/dashboard
  +-- skill: dashboard-analytics
  +-- rules: service_layer

workflows/student/passation
  +-- skill: passation-service
  +-- rules: passation_interface

workflows/student/resultats
  +-- skill: passation-service
  +-- rules: visual_identity

workflows/shared/api-mobile
  +-- skills: passation-service, qcm-engine
  +-- rules: access_control

workflows/shared/phase1-auth
  +-- rule: access_control
```

### External Service Dependencies

| Service | Used By | Purpose |
|---------|---------|---------|
| MySQL 8.0 | All workflows | Primary database |
| Composer | `/install` | PHP dependency manager |
| npm | `/install` | Node.js package manager |
| Packagist | `/install` | Laravel, Spatie, Sanctum packages |
| npm registry | `/install` | Tailwind, Alpine.js, Vite, Lucide |
| CDN (jsdelivr) | `soliquiz-developer` | Alpine.js CDN fallback |

---

## 6. Replication Guide

### To rebuild SoliQuiz from scratch in a different stack:

1. **Database layer** -- Replicate 14-table schema: users (with `type_profil` enum), classes (FK->users), seances->unite_apprentissages->competences->qcms->questions->options, plus tentatives->reponses->choix_reponses pivot. All scores `decimal(5,1)` on /20 scale. FK constraints with cascade deletes.

2. **Auth & Roles** -- Three roles (admin, formateur, etudiant). Use `type_profil` column as role discriminant. Web: session-based auth. API: token-based auth. Role-based route middleware.

3. **Service Layer** -- Implement 9 services (User, Dashboard, Classe, Seance, Qcm, QcmPublic, Passation, Etudiant, Resultat) following Thin Controller / Rich Service pattern. All business logic in services, not controllers.

4. **QCM Engine** -- Transactional creation (QCM + questions + options in one DB transaction). Three states: brouillon (editable), publie (visible), clos (locked). Question types: unique choice (QCU) and multiple choice (QCM).

5. **Passation System** -- Idempotent attempt start (resumes existing `en_cours`). Timer with auto-submit at 0. Auto-save to localStorage every 30s. Scoring: convert raw points to /20 scale with `decimal(5,1)`.

6. **Frontend** -- SPA-like shell (no full page reloads). Role-based navbars (admin=dark, formateur/student=light). Atomic Design components (atoms->molecules->organisms). Toast notifications (4s auto-dismiss). Score color convention: emerald >=14/20, amber 10-13/20, rose <10/20.

7. **KPIs** -- Admin: user/QCM/class counts, monthly attempts, average score. Formateur: managed class stats, own QCM stats, attempt analytics. Student: completed count, average, in-progress, success rate.

8. **Mobile API** -- RESTful endpoints: login (returns Sanctum token), QCM list (bucketed by status), start attempt (returns questions + timer), submit (scores + feedback), results (full breakdown).

### Key Architectural Decisions to Preserve

- **No SPA frameworks** (React/Vue/Angular) -- Blade + Alpine.js replaces them
- **No jQuery** -- Alpine.js covers all reactivity needs
- **No raw SQL** -- Eloquent-only for observable/cast integrity
- **No double hashing** -- `'password' => 'hashed'` cast handles it
- **Single role source** -- `type_profil` column mirrors Spatie role name
- **Decimal scores** -- Always `decimal(5,1)` on /20 scale
- **French UI** -- All labels, comments, and feedback in French
