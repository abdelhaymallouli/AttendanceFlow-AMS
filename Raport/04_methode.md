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
