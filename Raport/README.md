# **Page De Garde** {#page-de-garde}

# **Table de matière** {#table-de-matière}

# **Liste des figures** {#liste-des-figures}

# 

# **Remerciement** {#remerciement}

Au terme de ce projet, je tiens à exprimer ma profonde gratitude et mes sincères remerciements à toutes les personnes qui ont contribué de près ou de loin à la réalisation de ce travail.

Mes remerciements s'adressent tout particulièrement à mon encadrant, **Monsieur ESSARRAJ Fouad**, pour sa disponibilité, sa patience et ses conseils précieux. Sa vision technique et son accompagnement constant ont été des piliers essentiels pour mener à bien ce projet de gestion d'assiduité.

Je tiens également à remercier l'ensemble de l'équipe pédagogique du centre **SoliCode** pour la qualité de la formation et l'environnement d'apprentissage stimulant qu'ils nous offrent au quotidien.

Enfin, je remercie ma famille et mes amis pour leur soutien indéfectible et leurs encouragements tout au long de mon parcours de formation.

# ***Introduction*** {#introduction}

Dans le cadre de notre formation à Solicode, le projet « Digitalisation de la gestion du Syndic » a été réalisé pour répondre à un besoin réel dans la gestion des immeubles résidentiels. Actuellement, le suivi des charges et des paiements se fait souvent de manière manuelle, ce qui rend l’organisation difficile et peu transparente. Cette situation peut entraîner des retards, des erreurs et des conflits entre les résidents et le syndic.

L’objectif principal de ce projet est de concevoir une plateforme web permettant de centraliser les informations de l’immeuble et d’assurer un meilleur suivi des paiements. La solution proposée vise à améliorer la communication entre les acteurs et à garantir une gestion plus structurée et efficace.

Pour atteindre ces objectifs, une démarche agile a été adoptée, combinant l’approche  Design thinking pour analyser les besoins des utilisateurs et la méthode Scrum pour organiser le développement par itérations successives.

*Contexte de projet*

La mise en place de la plateforme **AttendanceFlow-AMS** s'inscrit au cœur d'une réflexion globale sur l'amélioration des processus académiques et pédagogiques au sein de **SoliCode**. Cette section présente le contexte académique de formation, les enjeux qui ont motivé ce projet ainsi que le cadre d'apprentissage dans lequel il a été développé.

## **Objectif de Project**  {#objectif-de-project}

Le projet **AttendanceFlow-AMS** s'inscrit dans le cadre de la formation "Développement Mobile et Web" au sein de **SoliCode**. Les objectifs pédagogiques principaux sont :

\*   **Maîtrise de l'Agilité** : Application concrète des frameworks Scrum et Design Thinking sur un projet réel.

\*   **Expertise Full-Stack** : Utilisation de technologies modernes (Laravel, Tailwind CSS, Alpine.js) pour créer une solution complète.

\*  **Conception Centrée Utilisateur** : Passage du besoin abstrait à une interface fonctionnelle et ergonomique.

\*  **Professionnalisation** : Simulation d'un environnement de production avec gestion de version (Git) et documentation technique.

Ce projet de fin de formation constitue ainsi un tremplin idéal pour allier les exigences d'un produit professionnel aux objectifs d'apprentissage d'un développeur full-stack. Il jette les bases de l'analyse des besoins réels qui sera formalisée dans le cahier des charges de la section suivante.

## **Cahier de charge** {#cahier-de-charge}

*Afin de concevoir une solution parfaitement adaptée aux besoins de l'administration, des enseignants et des étudiants de SoliCode, il est indispensable de définir rigoureusement les contours du projet. Ce cahier des charges détaille la vision du système, les objectifs ciblés, les spécifications fonctionnelles pour chaque profil utilisateur, ainsi que les contraintes techniques et de performance régissant le développement.*

*Rôle : Architecture Système & Analyse*  
*Projet : Système de Gestion des Absences (AMS) pour SoliCode*

---

###  Introduction & Vision {#introduction-&-vision}

*AttendanceFlow-AMS est une solution numérique intégrée visant à supprimer le goulot d'étranglement de la gestion manuelle des absences. La vision centrale est d'automatiser le flux de données de la salle de classe (Terrain) vers l'administration (Core) en temps réel.*

###  Objectifs du Système {#objectifs-du-système}

* *Suppression du Papier : Digitalisation 100% du pointage.*  
* *Précision Granulaire : Gestion des absences par sessions (9-11, 11-14, 14-17).*  
* *Réactivité Administrative : Disponibilité immédiate des données pour validation.*  
* *Transparence Étudiante : Consultation autonome par les apprenants.*

  ### 3\. Spécifications Fonctionnelles {#3.-spécifications-fonctionnelles}

  #### 3.1 Profil Formateur (Mobile-First)

* *Authentification sécurisée.*  
* *Pointage "Flash" : sélection rapide des absents/présents par session.*  
* *Saisie des motifs de retard en temps réel.*  
* *Historique des sessions récentes.*

  #### 3.2 Profil Administrateur (Back-Office Web)

* *Dashboard global de monitoring.*  
* *Hub de validation des pointages formateurs.*  
* *Gestion des justificatifs (Visualisation et Approbation).*  
* *Exportation de rapports dynamiques (PDF/Excel).*

  #### 3.3 Profil Étudiant (Consultation)

* *Tableau de bord personnel d'assiduité.*  
* *Soumission numérique de justificatifs.*  
* *Notifications d'alertes en cas de dépassement de quota.*

  ### 4\. Spécifications Techniques (Architecture) {#4.-spécifications-techniques-(architecture)}

* *Backend : Laravel 12 (Robustesse & Sécurité).*  
* *Frontend : Tailwind CSS & Alpine.js (UX Premium & Performance).*  
* *Database : MySQL (Intégrité référentielle des records d'absence).*  
* *Architecture : Service-Pattern pour le découplage métier.*

  ### 5\. Contraintes & Performance {#5.-contraintes-&-performance}

* *Temps de saisie : Inférieur à 30 secondes pour une classe complète.*  
* *Synchronisation : Immédiate (Real-time update).*  
* *Responsive : Adaptabilité totale Web & Mobile.*

  ---

*La définition claire de ces exigences fonctionnelles et techniques pose le cadre de référence pour l'ensemble du cycle de développement. Afin de concrétiser cette vision de manière itérative et agile, une méthodologie de travail collaborative et structurée s'avère nécessaire, comme décrit dans la section suivante.*

*Méthode de travail*

La réussite d’un projet d’envergure tel que AttendanceFlow-AMS repose non seulement sur les choix technologiques, mais également sur la rigueur de sa gestion et de sa conception. Cette section présente les méthodologies agiles Scrum et l’approche centrée utilisateur Design Thinking qui ont guidé notre processus, depuis la compréhension intime du besoin jusqu’à la validation finale du prototype.

L’approche adoptée pour ce projet repose sur une symbiose entre deux cadres de référence majeurs : l’agilité de Scrum et la philosophie du Design Thinking. Cette combinaison garantit une solution rigoureusement centrée sur l’utilisateur et une livraison itérative de valeur.

## **Scrum** {#scrum}

Scrum est le cadre de travail agile utilisé pour livrer de la valeur de manière itérative. Nous avons structuré le développement d’**AttendanceFlow-AMS** en Sprints de deux semaines.

![Méthodologie Scrum](images/image1.png)

## **Figure 1** : Méthodologie Scrum

### 📋 Sprint 1 : Digitalisation du Pointage (MVP)

Le Sprint 1 s'est déroulé sur **deux semaines** (Mars 2026) avec les objectifs suivants :

| Cérémonie | Fréquence | Détail |
|-----------|-----------|--------|
| **Sprint Planning** | Début du Sprint | Décomposition du backlog en tâches : modélisation DB, auth, dashboards, CRUD sessions, pointage, justifications, reporting |
| **Daily Stand-up** | Quotidien (15 min) | Points de blocage, avancement des tâches, alignement avec la vision |
| **Sprint Review** | Fin du Sprint | Démonstration des fonctionnalités livrées au Product Owner |
| **Sprint Retrospective** | Après la Review | Identification des axes d'amélioration pour le prochain sprint |

* **Product Owner :** responsable de la vision fonctionnelle du produit et de la priorisation du backlog.  
* **Scrum Master :** garant du respect de la méthodologie Scrum et facilitateur de l’équipe.  
* **Équipe de développement :** chargée de la conception, du développement et des tests de l’application.

### **🔹 Événements Scrum** {#🔹-événements-scrum}

* **Sprint Planning :** planification des tâches à réaliser durant le sprint.  
* **Daily Stand-up :** réunion quotidienne de suivi et d’alignement.  
* **Sprint Review :** présentation des fonctionnalités développées.  
* **Sprint Retrospective :** analyse des points d’amélioration pour les prochains sprints.

## **La méthodologie 2TUP** {#la-méthodologie-2tup}

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

### 🎯 Phase 2 : Définition du Problème

Suite à la phase d'empathie, nous avons synthétisé nos découvertes pour isoler le problème central : la transition inefficace du support papier vers une saisie manuelle sur Excel, générant une surcharge logistique et un décalage d'information critique. L'énoncé du problème et les questions "How Might We" sont détaillés dans la **[Branche Fonctionnelle](05_branche_fonctionnelle.md)**.

### 💭 Phase 3 : Idéation (Génération de Solutions)

Lors de cette phase, nous avons exploré des solutions pour transformer le flux d'information :

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

*   **Frontend :** Blade Templates, Tailwind CSS pour le style, et Alpine.js pour l'interactivité dynamique.
*   **Backend :** Laravel 12 (PHP 8.2+) gérant la logique métier, l'authentification et les accès.
*   **Base de Données :** MySQL pour le stockage relationnel des données d'assiduité.
*   **Composants UI :** Preline UI et Lucide Icons pour des interfaces professionnelles et claires.
   **c. Couche Accès aux Données** 

* Modèles Eloquent : User, Article, Category, Comment.  
* Gestion des relations, des requêtes SQL, de la sécurité et de l’intégrité des données.  
* Interaction directe avec la base de données MySQL.

## 2. Modules Fonctionnels

Cette séparation en couches renforce la lisibilité du code et simplifie l’évolution du système.

**3\. Architecture globale**

L’architecture globale du projet est une fusion entre le modèle MVC de Laravel et l’architecture en 3 tiers.

![Architecture globale](images/image12.png)

*Figure 16 : Architecture globale*

* **La couche Présentation** inclut **les Vues Blade et les Controllers**, car ils gèrent l’interaction avec l’utilisateur et les requêtes HTTP.

## Outils de développement & Gestion

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
# ⭐ Conclusion
* **La couche Logique Métier** regroupe les **Services**, la **validation**, et la **gestion des rôles/permissions avec Spatie**.

* **La couche Accès aux Données** contient les **Modèles Éloquent** et la **base MySQL**, responsables du stockage et de la récupération des informations.

Cette organisation assure une application modulaire, sécurisée, facile à maintenir et capable de communiquer aussi bien avec le Web qu’avec une application mobile
- **6 services métier** encapsulant la logique applicative (Service Pattern)
- **33 vues Blade** avec Alpine.js pour une expérience utilisateur réactive et moderne
- **44 utilisateurs de démonstration** (4 staff + 40 étudiants) pour la validation et les tests

## 🧠 Compétences Acquises

Ce projet a été l'occasion de mettre en œuvre et de maîtriser :

- **Design Thinking** : empathie utilisateur, définition du problème, idéation, prototypage et test
- **Méthodologie Scrum** : planification de sprint, backlog management, itérations agiles
- **Laravel 12** : architecture MVC, Eloquent ORM, migrations, validation, middlewares
- **Service Pattern** : découplage de la logique métier en couches spécialisées
- **Tailwind CSS v4** : design atomique, responsive, utilitaires de classes
- **Alpine.js** : interactivité côté client légère et performante
- **Laravel Sanctum** : sécurisation d'API RESTful par token
- **Spatie Permissions** : gestion fine des rôles et accès
- **Chart.js** : visualisation de données et reporting dynamique

## 🔮 Perspectives d'Évolution

### Sprint 2 (Prévu)
- **Application Mobile** : Développement d'une application mobile native (Flutter/React Native) utilisant l'API REST
- **Notifications Push** : Alertes automatiques pour les absences consécutives
- **Soumission de Justificatifs (Mobile)** : Upload de fichiers depuis l'appareil mobile
- **Export PDF/Excel** : Génération de rapports téléchargeables

### Sprint 3 et Au-delà
- **Scan QR Code** : Pointage accéléré par lecture de code QR étudiant
- **Dashboard Analytique Avancé** : Prédiction des risques de décrochage
- **Synchronisation Multi-Sites** : Gestion des absences sur plusieurs centres SoliCode
- **GED (Gestion Électronique de Documents)** : Archivage intelligent des justificatifs

---

*Projet réalisé dans le cadre de la formation **Développement Mobile et Web** à **SoliCode** — 2026*

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```
## **Projet Technique (Prototype CRUD):** {#projet-technique-(prototype-crud):}

Dans le cadre du développement du prototype, un projet technique complet basé sur les opérations **CRUD (Create, Read, Update, Delete)** a été réalisé afin de tester la logique métier et la gestion des données du système. 

Ce projet technique a permis de tester :

* La manipulation dynamique des données  
* Les opérations d’ajout, de modification et de suppression  
* La communication entre les différentes couches de l’architecture  
* La gestion des formulaires et validations  
* L’organisation du code selon l’architecture MVC

Le prototype utilise également **Alpine.js** afin d’améliorer l’interactivité et la réactivité des interfaces utilisateur. Cette intégration permet d’offrir une expérience plus fluide grâce aux composants dynamiques, aux interactions instantanées et aux mises à jour sans rechargement complet des pages.

L’association de **Laravel**, **Blade Templates**, **Tailwind CSS** et **Alpine.js** permet ainsi de construire une application moderne, maintenable et performante.

*Conception* 

La conception visuelle et la structuration des données constituent le pont entre l'architecture logique du système et l'expérience de l'utilisateur final. Cette section présente la modélisation conceptuelle de nos entités via le diagramme de classes ainsi que le schéma des interfaces utilisateur, conçues pour être fluides, intuitives et adaptées à tous les terminaux.

## **Diagramme de classe :** {#diagramme-de-classe-:}

## Le diagramme de classe définit la structure de la base de données et les relations entre les entités clés (Utilisateurs, Sessions, Absences, Justificatifs). {#le-diagramme-de-classe-définit-la-structure-de-la-base-de-données-et-les-relations-entre-les-entités-clés-(utilisateurs,-sessions,-absences,-justificatifs).}

![Diagramme de classe (Conception)](images/image13.png)   
*Figure 17 : Diagramme de classe (Conception)*

## **Charte graphique**  {#charte-graphique}

![Charte graphique](images/image14.png)

*Figure 18 : Charte graphique* 

*Interfaces :*

* Tableau de Bord Administrateur 

![Tableau de Bord Administrateur (Statistique)](images/image15.png)   
*Figure 19 :  Tableau de Bord Administrateur(Statistique)* 

![Justifications récentes et analyse](images/image16.png)

*Figure 20 : Justifications récentes et analyse*

* Tableau de Bord Syndic   
    
   ![Tableau de Bord Syndic (Statistique)](images/image17.png)

*Figure 21 :  Tableau de Bord Syndic (Statistique)*

![Activité récente sur les immeubles](images/image18.png)

*Figure 22 :  Activité récente sur les immeubles* 

* Tableau de Bord Résident  
    
   ![Tableau de Bord Résident](images/image19.png)

*Figure 24 :  Tableau de Bord R*

# **Réalisation :** {#réalisation-:}

La concrétisation de la plateforme **AttendanceFlow-AMS** se traduit par l'implémentation rigoureuse de la logique de programmation, de la base de données relationnelle et d'interfaces utilisateur dynamiques et interactives. Cette section détaille la mise en œuvre pratique de l'architecture MVC et du Service Pattern, les modules fonctionnels développés, les routes et endpoints d'API REST, le schéma physique de la base de données, la stack technologique finale, ainsi que les défis techniques et les solutions apportées. 

## **1\. Architecture Implémentée** {#1.-architecture-implémentée}

L’application repose sur une combinaison de trois approches architecturales complémentaires :

* Architecture MVC (Modèle – Vue – Contrôleur)  
* Architecture 3-Tiers  
* Service Pattern pour la séparation de la logique métier

Cette organisation garantit une meilleure maintenabilité du code, une séparation claire des responsabilités ainsi qu’une évolutivité du système.

### 1.1 Structure globale du système {#1.1-structure-globale-du-système}

Le système est divisé en trois couches principales :

#### Couche Présentation

Cette couche représente l’interface utilisateur. Elle est développée avec :

* Blade Templates pour le rendu côté serveur  
* Tailwind CSS pour le design et la mise en page  
* Alpine.js pour les interactions dynamiques légères

Cette couche permet une expérience utilisateur fluide et réactive sans recourir à un framework frontend lourd.

#### Couche Application

Cette couche contient la logique métier principale développée avec Laravel 12\. Elle comprend :

* Les contrôleurs (gestion des requêtes HTTP)  
* Les services (logique métier découplée)  
* Les règles de validation et de traitement

#### Couche Données

Cette couche assure la persistance des données via :

* MySQL comme base de données relationnelle  
* Eloquent ORM pour la manipulation des données  
* Migrations et seeders pour la structuration et l’initialisation

---

### 1.2 Service Pattern {#1.2-service-pattern}

Afin d’éviter un code monolithique dans les contrôleurs, la logique métier a été déplacée vers des services spécialisés. Cette approche améliore la lisibilité et la réutilisabilité du code.

Les principaux services implémentés sont :

* IdentityService : gestion de l’authentification et des rôles utilisateurs  
* AcademicService : gestion des données académiques (filières, groupes, modules)  
* AttendanceService : traitement des présences et absences  
* JustificationService : gestion du cycle de vie des justificatifs  
* SchedulingService : gestion des sessions et détection des conflits  
* ReportingService : génération des statistiques et analyses

Chaque service joue un rôle précis dans la séparation des responsabilités et permet de structurer la logique métier de manière modulaire.

---

## **2\. Modules Fonctionnels Développés** {#2.-modules-fonctionnels-développés}

* **Module d’authentification :** assure la connexion sécurisée des utilisateurs, la gestion des sessions et la redirection selon les rôles (administrateur, formateur et étudiant).  
* **Dashboard Administrateur :** fournit une vue globale du système avec les statistiques principales, les activités récentes et des accès rapides aux fonctionnalités.  
* **Module de gestion des sessions :** permet la création, la modification, la suppression et la consultation des sessions académiques avec vérification des conflits de planning.  
* **Module de pointage des présences :** facilite l’enregistrement des présences, absences et retards en temps réel avec mise à jour automatique des statistiques.  
* **Module de gestion des justificatifs :** permet aux étudiants de soumettre des justificatifs d’absence et aux administrateurs ou formateurs de les valider ou refuser.  
* **Module de reporting et analytics :** génère des statistiques, graphiques et indicateurs pour le suivi de l’assiduité et des performances académiques.  
* **Annuaire des étudiants :** offre une recherche rapide des étudiants, le filtrage par groupe et le suivi individuel des taux de présence.

---

# **4\. API RESTful** {#4.-api-restful}

Une API REST a été développée afin de permettre une future extension mobile.

Elle est sécurisée via Laravel Sanctum.

Les principaux endpoints sont :

* Authentification utilisateur (login / logout)  
* Récupération du profil utilisateur  
* Gestion des données académiques  
* Enregistrement et consultation des présences  
* Gestion des justificatifs  
* Récupération des statistiques globales

Cette API permet de dissocier le frontend web et de faciliter l’évolution vers une application mobile.

---

# **5\. Base de données** {#5.-base-de-données}

La base de données est relationnelle et structurée autour de plusieurs entités principales :

* Utilisateurs  
* Profils étudiants et enseignants  
* Filières et groupes  
* Modules académiques  
* Sessions académiques  
* Enregistrements de présence  
* Justificatifs d’absence  
* Système de rôles et permissions

Cette structure garantit une cohérence des données et une forte intégrité référentielle.

# 

#  **Conclusion :** {#conclusion-:}

Le projet **ImmoSyndic** a permis de répondre au défi de la transition vers une gestion immobilière digitale et transparente. En utilisant les méthodologies Design Thinking, 2TUP et Scrum, nous avons pu transformer des problèmes vécus (manque de transparence, erreurs manuelles) en une solution logicielle performante.

Ce travail m'a permis de consolider mes compétences en architecture logicielle (MVC), en développement avec Laravel, et en conception UX/UI. Les perspectives d'évolution incluent l'intégration de paiements en ligne Stripe et la gestion de la domotique pour les immeubles intelligents.


