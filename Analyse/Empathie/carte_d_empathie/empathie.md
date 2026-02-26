# 📋 Système de Gestion des Absences (AMS)

> **Phase 1 du Design Thinking : Empathie**

Ce document présente la phase de découverte et d'empathie du projet, en se concentrant sur les expériences humaines derrière les données.

---

## 🔍 1. Le Problème Central : Le "Piège du Double Travail"

Nos recherches montrent que l'**Administrateur de l'Absence** est actuellement piégé dans un flux de travail de "Double Saisie".

1. **Le Matin :** Collecte physique des fiches de présence et des listes manuscrites auprès des enseignants.
2. **L'Après-midi :** Saisie manuelle des données dans un ordinateur (Excel/Système hérité).

**La Friction :** Cela crée un "décalage de données" de 4 à 6 heures où l'école fonctionne avec des informations obsolètes, et le risque d'erreur humaine (lier le mauvais nom à une absence) est extrêmement élevé.

---

## 👤 2. Personas d'Utilisateurs

### **L'Administrateur de l'Absence (Utilisateur Principal)**

* **Nom :** Madame Hannane
* **Contexte :** Gère plus de 120 étudiants.
* **Objectif :** Avoir une boîte de réception vide et zéro erreur de saisie de données.
* **Point de Douleur :** Passe 70% de sa journée à regarder le papier puis son écran. Elle se sent comme un "pont humain" entre le papier et le numérique.

### **L'Étudiant Frustré**

* **Contexte :** A besoin de savoir si son certificat médical a été accepté.
* **Objectif :** Transparence.
* **Point de Douleur :** Doit se rendre physiquement au bureau pour poser des questions sur son dossier car il ne peut pas le consulter en ligne.

---

## 🗺️ 3. Carte d'Empathie : Administrateur de l'Absence

Nous avons cartographié l'expérience de l'Administrateur pour comprendre le coût émotionnel et physique du système actuel.

| Catégorie | Observations |
| --- | --- |
| **DIT** | "Je mettrai le système à jour cet après-midi," "Est-ce un 'B' ou un '8' ?", "J'ai perdu cette fiche." |
| **PENSE** | *Je fais un travail qu'un ordinateur devrait faire.* *J'espère n'avoir oublié aucune absence justifiée.* |
| **FAIT** | Transporte des piles de papier, croise les listes, tape manuellement pendant des heures, répond aux appels des parents. |
| **RESSENT** | **Anxieuse** quant à la précision des données, **épuisée** par les tâches répétitives, et **déconnectée** de l'activité réelle de l'école. |

---

## 🛤️ 4. Le Parcours : Du Papier au Numérique

Pour concevoir un système aussi fluide que **absence.io**, nous devons éliminer le "fossé de transition".

* **Parcours Actuel (La Douleur) :** `Collecte de présence` -> `Lecture Admin` -> `Saisie Admin` -> `Sauvegarde`
* **Parcours Proposé (La Solution d'Empathie) :** `Saisie Enseignant (Mobile/Web)` -> `Synchronisation Instantanée` -> `Validation Admin`.

---

## 💡 5. Insights Clés d'Empathie

Basé sur nos entretiens et le problème "Papier-vers-Laptop", notre conception doit se concentrer sur trois points :

1. **Réduire la Cognition :** L'interface utilisateur doit utiliser des couleurs et des icônes pour que l'Admin n'ait pas à "lire" chaque nom ; elle doit pouvoir "scanner" l'état.
2. **Éliminer l'Intermédiaire :** Les enseignants doivent saisir les données directement. Le rôle de l'Admin passe de la **Saisie de données** à la **Vérification de données**.
3. **La Confiance par la Preuve :** Puisque le papier est remplacé, la fonction "Téléchargement de Justificatif" est critique pour garantir que l'Admin sent toujours que les données sont "officielles" (version numérique de la note papier).

---

## CHAPITRE SUIVANT : Définir le Problème
