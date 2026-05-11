<div align="center">

<br>

| | |
|:---:|:---:|
| ![](../Presentation/images/logo-solicode.png){width=140px} | ![](../Presentation/images/ofppt-logo.png){width=140px} |

<br><br><br>

**REPORT FINAL DE PROJET**


<br>

***

<br>

**AttendanceFlow-AMS**
*Système de Gestion des Absences*

<br>

***

<br><br><br>

| | |
|:--- | ---:|
| **Submitted by:** | **Academic Year:** |
| **Mallouli Abdelhay** | **2025 - 2026** |
| | |
| **Supervisor:** | **Filière:** |
| **Mr. Essarraj Fouad** | **Développement Mobile** |

<br><br><br><br><br>

**SOLICODE – Digital & IT Training Center**
*www.solicode.co*

</div>

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```
# 📋 Table of Contents

```{=openxml}
<w:p><w:r><w:fldChar w:fldCharType="begin"/></w:r><w:r><w:instrText xml:space="preserve"> TOC \o "1-3" \h \z \u </w:instrText></w:r><w:r><w:fldChar w:fldCharType="separate"/></w:r><w:r><w:fldChar w:fldCharType="end"/></w:r></w:p>
```

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```
# 📜 Remerciements

Au terme de ce projet, je tiens à exprimer ma profonde gratitude et mes sincères remerciements à toutes les personnes qui ont contribué de près ou de loin à la réalisation de ce travail.

Mes remerciements s'adressent tout particulièrement à mon encadrant, **Monsieur ESSARRAJ Fouad**, pour sa disponibilité, sa patience et ses conseils précieux. Sa vision technique et son accompagnement constant ont été des piliers essentiels pour mener à bien ce projet de gestion d'assiduité.

Je tiens également à remercier l'ensemble de l'équipe pédagogique du centre **SoliCode** pour la qualité de la formation et l'environnement d'apprentissage stimulant qu'ils nous offrent au quotidien.

Enfin, je remercie ma famille et mes amis pour leur soutien indéfectible et leurs encouragements tout au long de mon parcours de formation.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

# 🚀 Introduction

Le projet **AttendanceFlow-AMS** est une réponse technologique moderne aux défis logistiques de la gestion de l'assiduité en centre de formation. En alliant ergonomie mobile et puissance administrative web, il redéfinit le suivi des présences comme un flux continu et transparent.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

# 🏙️ Contexte de projet

## Objectifs de formation (SoliCode)

Le projet **AttendanceFlow-AMS** s'inscrit dans le cadre de la formation "Développement Mobile et Web" au sein de **SoliCode**. Les objectifs pédagogiques principaux sont :

*   **Maîtrise de l'Agilité :** Application concrète des frameworks Scrum et Design Thinking sur un projet réel.
*   **Expertise Full-Stack :** Utilisation de technologies modernes (Laravel, Tailwind CSS, Alpine.js) pour créer une solution complète.
*   **Conception Centrée Utilisateur :** Passage du besoin abstrait à une interface fonctionnelle et ergonomique.
*   **Professionnalisation :** Simulation d'un environnement de production avec gestion de version (Git) et documentation technique.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```


# 📑 Cahier des Charges

**Rôle :** Architecture Système & Analyse
**Projet :** Système de Gestion des Absences (AMS) pour SoliCode

---

## 1. Introduction & Vision
AttendanceFlow-AMS est une solution numérique intégrée visant à supprimer le goulot d'étranglement de la gestion manuelle des absences. La vision centrale est d'automatiser le flux de données de la salle de classe (Terrain) vers l'administration (Core) en temps réel.

## 2. Objectifs du Système
- **Suppression du Papier :** Digitalisation 100% du pointage.
- **Précision Granulaire :** Gestion des absences par sessions (9-11, 11-14, 14-17).
- **Réactivité Administrative :** Disponibilité immédiate des données pour validation.
- **Transparence Étudiante :** Consultation autonome par les apprenants.

## 3. Spécifications Fonctionnelles

### 3.1 Profil Formateur (Mobile-First)
- Authentification sécurisée.
- Pointage "Flash" : sélection rapide des absents/présents par session.
- Saisie des motifs de retard en temps réel.
- Historique des sessions récentes.

### 3.2 Profil Administrateur (Back-Office Web)
- Dashboard global de monitoring.
- Hub de validation des pointages formateurs.
- Gestion des justificatifs (Visualisation et Approbation).
- Exportation de rapports dynamiques (PDF/Excel).

### 3.3 Profil Étudiant (Consultation)
- Tableau de bord personnel d'assiduité.
- Soumission numérique de justificatifs.
- Notifications d'alertes en cas de dépassement de quota.

## 4. Spécifications Techniques (Architecture)
- **Backend :** Laravel 12 (Robustesse & Sécurité).
- **Frontend :** Tailwind CSS & Alpine.js (UX Premium & Performance).
- **Database :** MySQL (Intégrité référentielle des records d'absence).
- **Architecture :** Service-Pattern pour le découplage métier.

## 5. Contraintes & Performance
- **Temps de saisie :** Inférieur à 30 secondes pour une classe complète.
- **Synchronisation :** Immédiate (Real-time update).
- **Responsive :** Adaptabilité totale Web & Mobile.

---

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```
# 🛠️ Méthodes de travail

L'approche adoptée pour ce projet repose sur une symbiose entre deux cadres de référence majeurs : l'agilité de **Scrum** et la philosophie du **Design Thinking**. Cette combinaison garantit une solution rigoureusement centrée sur l'utilisateur et une livraison itérative de valeur.

---

## 📅 Méthodologie Scrum

Scrum est le cadre de travail agile utilisé pour livrer de la valeur de manière itérative. Nous avons structuré le développement d'**AttendanceFlow-AMS** en Sprints de deux semaines.

![Méthodologie Scrum](imgs/scrum.jpg)

### 👥 Rôles & Évènements
*   **Rôles :** Product Owner (Vision), Scrum Master (Facilitateur), Équipe Dev (Réalisation).
*   **Cycles :** Sprint Planning, Daily Stand-up, Sprint Review et Sprint Retrospective pour une amélioration continue.

### 📋 Sprint 1 : Digitalisation du Pointage (MVP)

Le Sprint 1 s'est déroulé sur **deux semaines** (Mars 2026) avec les objectifs suivants :

| Cérémonie | Fréquence | Détail |
|-----------|-----------|--------|
| **Sprint Planning** | Début du Sprint | Décomposition du backlog en tâches : modélisation DB, auth, dashboards, CRUD sessions, pointage, justifications, reporting |
| **Daily Stand-up** | Quotidien (15 min) | Points de blocage, avancement des tâches, alignement avec la vision |
| **Sprint Review** | Fin du Sprint | Démonstration des fonctionnalités livrées au Product Owner |
| **Sprint Retrospective** | Après la Review | Identification des axes d'amélioration pour le prochain sprint |

**Backlog Sprint 1 (Livré) :**
- Modélisation et migration de la base de données (12 tables)
- Système d'authentification avec rôles (Admin, Formateur, Étudiant)
- Dashboard Administrateur avec statistiques globales
- CRUD complet des sessions académiques
- Pointage des présences par session (formateur + admin)
- Gestion des justificatifs (soumission + validation)
- Reporting et analyses avec graphiques (Chart.js)
- API RESTful pour consommation mobile
- Données de démonstration (seeders CSV)

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## 🎨 Design Thinking : L'Humain au Centre

Le Design Thinking nous a permis de déconstruire le problème complexe du pointage papier pour aboutir à une interface intuitive.

![Méthodologie Design Thinking](imgs/designThinking.png)

### 💡 Phase 1 : Empathie (Compréhension des Acteurs)

Nous avons cartographié les frustrations et besoins de nos trois profils types via des **Mind Maps** détaillées. C'est cette analyse "terrain" qui a dicté la conception de l'application.

#### 👤 Madame Hannane (Administratrice)
Son besoin central est de passer du rôle de "saisisseuse" de données à celui de "validatrice".
![Mind Map - Madame Hannane](imgs/mindmap_hannane.png)

#### 👤 Anouar Benyakhelef (Étudiant)
Son défi est l'opacité du système actuel ; il a besoin de transparence et d'autonomie.
![Mind Map - Anouar Benyakhelef](imgs/mindmap_anouar.png)

#### 👤 Imane Bouziane (Formatrice)
Elle recherche la rapidité pour ne pas empiéter sur le temps pédagogique.
![Mind Map - Imane Bouziane](imgs/mindmap_imane.png)

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

### 🎯 Phase 2 : Définition du Problème

Suite à la phase d'empathie, nous avons synthétisé nos découvertes pour isoler le problème central : la transition inefficace du support papier vers une saisie manuelle sur Excel, générant une surcharge logistique et un décalage d'information critique. L'énoncé du problème et les questions "How Might We" sont détaillés dans la **[Branche Fonctionnelle](05_branche_fonctionnelle.md)**.

### 💭 Phase 3 : Idéation (Génération de Solutions)

Lors de cette phase, nous avons exploré des solutions pour transformer le flux d'information :

- **Saisie Directe Mobile/Web :** L'enseignant enregistre numériquement les présences par tranche horaire, éliminant le support papier.
- **Hub de Validation "One-Click" :** Interface administrateur avec code couleur (Rouge/Vert/Jaune) pour un scan rapide de l'assiduité.
- **Portail de Justificatifs :** Soumission numérique des preuves (PDF/images) liées directement aux absences.
- **Alertes Automatisées :** Notifications en cas d'absences consécutives anormales.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

### 🖥️ Phase 4 : Prototype (Maquettage et Développement)

Deux niveaux de prototype ont été réalisés :

1. **Maquettes HTML Interactives :** 17 pages web et 14 pages mobiles ont été créées en HTML/CSS statique pour valider l'expérience utilisateur avant développement. Ces maquettes couvrent tous les écrans (login, dashboards, pointage, justificatifs, reporting).

2. **Prototype Fonctionnel (MVP) :** Développement complet en Laravel 12 avec :
   - Authentification réelle et gestion des rôles (Spatie Permissions)
   - Base de données relationnelle (MySQL, 12 tables)
   - Tableaux de bord dynamiques avec données réelles
   - Pointage fonctionnel avec recherche et actions groupées
   - Reporting avec graphiques Chart.js

### ✅ Phase 5 : Test (Validation Utilisateur)

Les tests ont été effectués à plusieurs niveaux :

- **Tests d'acceptance :** Parcours complets pour chaque rôle (Admin, Formateur, Étudiant) en utilisant les comptes de démonstration.
- **Tests de performance :** Validation du temps de pointage < 30 secondes par classe.
- **Tests de régression :** Après chaque itération, vérification que les fonctionnalités existantes restent opérationnelles.
- **Feedback utilisateur :** Simulation des scénarios réels avec les personas définis en phase d'empathie.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```
# Branche Fonctionnelle

La branche fonctionnelle constitue le cœur de notre analyse. Elle vise à traduire les besoins abstraits des utilisateurs en une structure de solution concrète. En nous appuyant sur la méthodologie **Design Thinking**, nous explorons ici le parcours utilisateur depuis la compréhension profonde du problème jusqu'à la modélisation des solutions techniques, garantissant ainsi une application centrée sur l'humain et l'efficacité opérationnelle.

L'analyse fonctionnelle de ce projet suit la méthodologie **Design Thinking**, structurée en phases immersives pour garantir que la solution répond aux besoins réels des utilisateurs.

### 1. Empathie

L'approche **Design Thinking** commence par l'immersion. Dans cette phase, nous avons cherché à comprendre profondément les défis quotidiens de nos trois acteurs clés : l'administratrice, l'enseignant et l'étudiant.

#### A. Madame Hannane (Administratrice de l'Absence)

Son flux de travail actuel est marqué par une surcharge de tâches manuelles et une dépendance critique au support papier. Le passage de la "saisie" à la "validation" est son besoin prioritaire.

![Mind Map - Madame Hannane](imgs/mindmap_hannane.png)

**Analyse de l'Expérience :**

- **Points de Friction :** Des listes manuscrites illisibles, un décalage (data lag) de 4 à 6 heures, et une peur constante de l'erreur humaine.
- **Besoins Clés :** Transformation de son rôle en validateur de données, synchronisation immédiate et suppression du support papier.

#### B. Anouar Benyakhelef (Étudiant)

Pour l'étudiant, l'opacité du système actuel génère du stress et une lourdeur administrative inutile, notamment pour la gestion des justificatifs.

![Mind Map - Anouar Benyakhelef](imgs/mindmap_anouar.png)

**Analyse de l'Expérience :**

- **Points de Friction :** Manque de visibilité sur son assiduité, besoin de se déplacer physiquement à l'administration pour chaque démarche.
- **Besoins Clés :** Transparence en temps réel sur son compteur d'absences et autonomie dans la soumission numérique des justificatifs.

#### C. Imane Bouziane (Formatrice)

La formatrice voit la gestion des absences comme un "vol" de temps pédagogique, surtout avec la nécessité de pointer les étudiants par session (9h-11h, 11h-14h, 14h-17h).

![Mind Map - Imane Bouziane](imgs/mindmap_imane.png)

**Analyse de l'Expérience :**

- **Points de Friction :** Difficulté à segmenter les présences par tranches horaires sur papier, logistique lourde des fiches et interruption du rythme des cours.
- **Besoins Clés :** Validation granulaire par session (9-11, 11-14, 14-17), interface mobile fluide et synchronisation automatique avec l'administration.

**Synthèse Globale de l'Empathie :**
L'analyse croisée de ces trois profils révèle un besoin commun : la **suppression du support physique** au profit d'un flux numérique fluide, sécurisé et instantané.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

### 2. Définition du Problème

Suite à la phase d'empathie, nous avons synthétisé nos découvertes pour isoler le problème central. L'analyse révèle que le système actuel de "double saisie" (papier puis Excel) est la source principale d'inefficacité et d'erreurs.

**Énoncé du problème global :**

> Le problème central est l'inefficacité du flux de travail actuel qui repose sur le passage du **support papier vers une saisie manuelle sur Excel**. Cette méthode génère une surcharge logistique pour les formateurs (notamment pour la segmentation complexe des sessions 9-11, 11-14, 14-17) et un décalage d'information critique pour l'administration, compromettant la fiabilité globale du système.

**Questions "How Might We" (HMW) :**
Pour stimuler notre créativité, nous avons posé trois questions directrices :

1. **Comment pourrions-nous** éliminer totalement le transfert physique des fiches papier de la salle de classe ?
2. **Comment pourrions-nous** permettre à l'enseignant de valider chaque session (9-11, 11-14, 14-17) en moins de 30 secondes ?
3. **Comment pourrions-nous** dématérialiser l'approbation des justificatifs médicaux ?

**Besoins Fonctionnels Identifiés :**

- **Accès par Rôles :** Permissions distinctes pour Enseignants, Administrateurs et Étudiants.
- **Synchronisation en Temps Réel :** Données instantanément visibles sur le tableau de bord Admin.
- **Indicateurs Visuels :** Utilisation de couleurs et icônes pour un balayage rapide.
- **Gestion Numérique :** Capacité de stocker des justificatifs (PDF/Images) liés aux records d'absence.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

### 3. Idéation

La phase d'idéation nous a permis d'explorer des solutions concrètes. Notre vision est celle d'un flux **"Direct-to-System"** où l'information ne subit aucune friction intermédiaire. L'objectif est de transformer le rôle de l'Administrateur, de la "Saisie de données" vers la "Vérification de données".

**Solutions Stratégiques Retenues :**

- **Saisie Directe par Session (Mobile/Web) :** L'enseignant enregistre les présences numériquement pour chaque tranche horaire (9h-11h, 11h-14h, 14h-17h).
- **Hub de Validation "One-Click" :** Un tableau de bord administratif utilisant un code couleur (Rouge: Absent, Vert: Présent, Jaune: Justifié) pour scanner rapidement l'état.
- **Cloud de Justificatifs :** Un portail où les étudiants/parents soumettent leurs preuves numériques, liées directement aux absences.

**Brainstorming des Fonctionnalités :**

- **Alertes Automatisées :** Notifications si un étudiant manque plusieurs cours consécutifs.
- **Validation Interactive :** L'Admin clique sur "Approuver" ou "Rejeter" pour les justificatifs téléchargés.
- **Export Intelligent :** Génération de rapports Excel/PDF en un clic pour les résumés quotidiens.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

### 4. Diagrammes de Cas d'Utilisation Globaux

L'analyse fonctionnelle est divisée en deux écosystèmes complémentaires : la plateforme Web pour la gestion lourde et l'application Mobile pour les opérations de terrain.

#### A. Plateforme Web (Global)

La plateforme Web centralise la gestion des justificatifs, le reporting et le dashboard administratif.

![Cas d'Utilisation Global - Plateforme Web](imgs/global-w.png)

#### B. Application Mobile (Global)

L'application mobile se concentre sur la rapidité de saisie et la mobilité.

![Cas d'Utilisation Global - Plateforme mobile](imgs/global-m.png)

---

### 5. Cas d'Utilisation par Sprints

Le développement est segmenté en sprints pour garantir une livraison itérative de valeur. Chaque sprint intègre à la fois les fonctionnalités Web (administration) et Mobile (terrain).

#### Sprint 1 : Digitalisation du Pointage (MVP Web & Mobile)

Ce sprint se concentre sur le remplacement du papier par le numérique pour les opérations critiques de pointage.

![Sprint 1 - Web & Mobile MVP](imgs/sprint1.png)

**Fonctionnalités clés :**
- **Web :** Authentification, visualisation du dashboard de base et gestion des absences.
- **Mobile :** Pointage "Flash" par session, saisie des motifs de retard et authentification.

#### Sprint 2 : Justificatifs & Intelligence Métier

Ce sprint apporte l'interactivité pour l'étudiant et la validation administrative avancée.

![Sprint 2 - Extension Web & Mobile](imgs/sprint2.png)

**Fonctionnalités clés :**
- **Web :** Soumission et validation des justificatifs, historique d'assiduité complet et export de rapports.
- **Mobile :** Consultation de l'historique d'assiduité et vérification du statut de session.

# 🏗️ Branche Technique

## 🎯 Définition du Problème

Le problème majeur réside dans la transition inefficace du **support papier vers la saisie manuelle sur Excel**. Ce processus archaïque génère une surcharge logistique pour les formateurs (devoir segmenter manuellement les sessions 9-11, 11-14, 14-17) et un décalage d'information critique pour l'administration. Le coeur du défi est la suppression de cette "double saisie" par une automatisation directe à la source.

### Questions "Comment pourrions-nous" (HMW)

1. **Comment pourrions-nous** éliminer le transfert physique des fiches de présence papier de la salle de classe au bureau administratif ?
2. **Comment pourrions-nous** permettre à l'enseignant de valider chaque session (9-11, 11-14, 14-17) en moins de 30 secondes depuis son smartphone ?
3. **Comment pourrions-nous** numériser la soumission et l'approbation des notes/justificatifs médicaux ?

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## Choix Technologiques

Le projet repose sur un écosystème robuste et moderne permettant une scalabilité verticale et horizontale.

*   **Backend :** Laravel 12, offrant un routage puissant, une sécurité intégrée et un ORM (Eloquent) performant.
*   **Frontend :** Blade Templates pour le rendu côté serveur, combiné à Tailwind CSS pour un design atomique et Alpine.js pour la réactivité.
*   **Base de données :** MySQL gérant les relations complexes entre les sessions, les étudiants et les pointages.
*   **Outils Externes :** Git/GitHub (Versionnement), Lucide Icons (Visuels), Preline UI (Composants).

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## Architecture du Système

### Architecture MVC
Nous utilisons le motif **Modèle-Vue-Contrôleur** nativement supporté par Laravel :
- **Modèle :** Gestion de la logique de données et des règles métier.
- **Vue :** Présentation des données aux utilisateurs via Blade.
- **Contrôleur :** Intermédiaire traitant les requêtes et orchestrant les réponses.

### Architecture en 3 Couches (3-Tier)
L'application est structurée pour séparer les responsabilités :
1.  **Couche Présentation (Client) :** Navigateurs Web et Application Mobile.
2.  **Couche Application (Serveur) :** Serveur Laravel traitant la logique métier.
3.  **Couche Données (Stockage) :** Serveur de base de données MySQL.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## Prototype (Fonctionnalités & Classes)

Le prototype d'**AttendanceFlow-AMS** couvre les flux critiques :
-   **Section Administrateur :** Dashboard, gestion des étudiants, validation des justificatifs.
-   **Section Formateur :** Pointage par session (Flash), gestion des retards.
-   **Section Étudiant :** Consultation de l'assiduité, soumission de documents.

### Diagramme de Classe — Entités Principales
Les entités `User`, `Student`, `Teacher`, `Session`, `Attendance` et `Justification` forment le squelette du système, garantissant une intégrité référentielle stricte.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

# 🎨 Conception & UI Design

## Diagramme de Classe

Le diagramme de classe définit la structure de la base de données et les relations entre les entités clés (Utilisateurs, Sessions, Absences, Justificatifs).

![Diagramme de Classe](imgs/diagramme-class.png)

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## Schéma des interfaces (Maquette UI/UX)

La conception visuelle repose sur une approche premium et responsive, utilisant Tailwind CSS pour garantir une expérience fluide sur tous les terminaux.

### Vue d'ensemble - Tableau de Bord Administratif (Web)

![Maquette UI - Dashboard Admin](imgs/maquette.png)


### Vue d'ensemble - Application Mobile

![Maquette UI - Application Mobile](imgs/maquete-m.png)

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

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
# ⭐ Conclusion

Le projet **AttendanceFlow-AMS** a permis de répondre efficacement aux défis de la gestion des absences au sein de **SoliCode**. En remplaçant les processus manuels par une solution numérique intégrée (Web & Mobile), nous avons atteint les objectifs de :

1.  **Fiabilité :** Suppression des erreurs de saisie et centralisation de l'information.
2.  **Productivité :** Gain de temps significatif pour les formateurs et l'administration.
3.  **Transparence :** Accès direct pour les étudiants à leurs données d'assiduité.

## 📊 Bilan du Sprint 1

Le MVP livré couvre l'ensemble du périmètre fonctionnel défini dans le cahier des charges :

- **12 tables** de base de données modélisant l'intégralité du domaine métier
- **22 routes web** et **17 endpoints API** pour une couverture fonctionnelle complète
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
