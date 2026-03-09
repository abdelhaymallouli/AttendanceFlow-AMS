# 🏗️ Branche Technique

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

