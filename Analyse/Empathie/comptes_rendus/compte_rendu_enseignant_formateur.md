# Compte Rendu d'Entretien : Enseignante / Formatrice (Imane Bouziane)

**Projet :** AttendanceFlow-AMS (Attendance Management System)  
**Date :** 02 mars 2026  
**Intervenant :** Imane Bouziane  

---

![Carte d'Empathie - Imane Bouziane](../carte_d_empathie/empathy_map_fouad.png)

### 1. Identify the target user
- **Role :** Formatrice référente / Enseignante.
- **Technical Level :** Moyen.
- **Device Used :** Smartphone (Préfère la mobilité en classe).

### 2. Describe the current user workflow step-by-step
1. Arrivée en classe et lancement de la séance.
2. Gestion de l'appel par tranches horaires (sessions).
3. Circulation de la feuille d'émargement papier.
4. Récupération et vérification rapide des signatures.
5. Remise des fiches au bureau de la scolarité en fin de journée.

### 3. Identify friction points in the workflow
- **Segmentation horaire :** Difficile de gérer les absences sur trois sessions distinctes (9h-11h, 11h-14h, 14h-17h) avec une seule feuille papier.
- **Gestion des retards :** Noter précisément qui est arrivé à quelle session est fastidieux.
- **Data Gap :** L'administration ne reçoit les informations que plusieurs heures après la fin de la dernière session.

### 4. List repeated manual actions users perform
- Diviser manuellement les colonnes de présence pour les différentes sessions.
- Recalculer le total d'heures d'absence par étudiant en fin de journée.
- Se déplacer physiquement pour rendre les rapports.

### 5. Identify common errors or validation issues
- Erreur de session : Marquer un étudiant absent de 9h-11h alors qu'il n'était absent que de 11h-14h.
- Signature oubliée lors de la session de l'après-midi.

### 6. Detect missing features that would simplify tasks
- **Marquage par Session :** Une interface permettant de switcher entre les sessions (9-11, 11-14, 14-17) d'un simple clic.
- **Pré-remplissage :** Si l'élève était présent à 9h, le marquer présent par défaut pour la session suivante.

### 7. Identify automation opportunities
- Envoi automatique des données par session dès la validation.
- Alerte immédiate si un étudiant manque deux sessions consécutives.

### 8. Highlight performance issues
- **Temps perdu :** La gestion multi-sessions sur papier prend environ **15 minutes** par jour.

### 9. Identify navigation problems
- **Logistique :** Devoir jongler entre plusieurs feuilles ou sections pour les différentes tranches horaires.

### 10. Convert all pain points into functional improvement opportunities
- **Opportunité :** Dashboard mobile segmenté par sessions horaires.
- **Opportunité :** Validation granulaire (par tranche de 2 ou 3 heures).

### 11. Write a clear functional problem statement
Imane Bouziane a besoin d'une solution numérique permettant un pointage précis par session (9h-11h, 11h-14h, 14h-17h). Le système actuel ne permet pas une granularité suffisante, ce qui entraîne des erreurs de calcul et un décalage d'information critique pour l'administration. Elle souhaite valider chaque session séparément depuis son smartphone pour garantir une donnée fiable et en temps réel.

---
*Document restructuré selon le framework d'analyse d'empathie.*
