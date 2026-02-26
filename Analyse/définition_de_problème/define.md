# 🔬 Système de Gestion des Absences (AMS)

> **Phase 2 du Design Thinking : Définir**

Sur la base de nos recherches d'empathie, nous pouvons maintenant définir clairement les problèmes centraux que AttendanceFlow-AMS doit résoudre.

---

## 📌 1. Énoncé du Problème

**Pour l'Administrateur (Madame Hannane) :**
L'Administrateur de l'Absence a besoin d'un moyen de recevoir des données de présence numériques et en temps réel de la part des enseignants, car le système actuel de double saisie sur papier cause un décalage de 4 à 6 heures, une fatigue cognitive élevée et augmente le risque d'erreurs d'enregistrement.

**Pour l'Étudiant :**
Les étudiants ont besoin d'un moyen transparent de consulter leur état de présence et de justification, car le système actuel nécessite des déplacements physiques au bureau qui interrompent à la fois leur journée et le flux de travail de l'administration.

---

## ❓ 2. Questions "Comment pourrions-nous" (HMW)

Pour guider notre prototypage, nous avons posé les questions suivantes :

1. **Comment pourrions-nous** éliminer le transfert physique des fiches de présence papier de la salle de classe au bureau administratif ?
2. **Comment pourrions-nous** permettre à l'Administrateur de traiter le statut de plus de 120 étudiants en un coup d'œil au lieu de lire chaque ligne ?
3. **Comment pourrions-nous** numériser la soumission et l'approbation des notes/justificatifs médicaux ?

---

## 📋 3. Besoins Fonctionnels Identifiés

D'après nos énoncés de problème, le système *doit* comporter :

* **Accès par Rôles :** Vues et permissions distinctes pour les Enseignants (Saisie), les Administrateurs (Vérification/Approbation) et les Étudiants (Consultation uniquement).
* **Synchronisation en Temps Réel :** Les données saisies par un enseignant doivent apparaître instantanément sur le tableau de bord de l'Admin.
* **Indicateurs Visuels d'État :** L'interface utilisateur doit s'appuyer fortement sur des repères visuels (couleurs, icôes) pour un balayage rapide.
* **Système de Pièces Jointes Numériques :** Capacité de télécharger et de stocker des fichiers image/PDF liés à des records d'absence spécifiques pour justification.

---

## 🎯 4. Prochaines Étapes

Le problème étant clairement défini, nous allons passer à la phase d'Idéation pour réfléchir à des solutions potentielles.
