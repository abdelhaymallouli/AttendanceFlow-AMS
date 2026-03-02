# 🔬 Système de Gestion des Absences (AMS)

> **Phase 2 du Design Thinking : Définir**

Sur la base de nos recherches d'empathie, nous pouvons maintenant définir clairement les problèmes centraux que AttendanceFlow-AMS doit résoudre.

---

## 📌 1. Énoncé du Problème Global

Le problème majeur réside dans la transition inefficace du **support papier vers la saisie manuelle sur Excel**. Ce processus archaïque génère une surcharge logistique pour les formateurs (devoir segmenter manuellement les sessions 9-11, 11-14, 14-17) et un décalage d'information critique pour l'administration. Le coeur du défi est la suppression de cette "double saisie" par une automatisation directe à la source.

---


## ❓ 2. Questions "Comment pourrions-nous" (HMW)

Pour guider notre prototypage, nous avons posé les questions suivantes :

1. **Comment pourrions-nous** éliminer le transfert physique des fiches de présence papier de la salle de classe au bureau administratif ?
2. **Comment pourrions-nous** permettre à l'enseignant de valider chaque session (9-11, 11-14, 14-17) en moins de 30 secondes depuis son smartphone ?
3. **Comment pourrions-nous** numériser la soumission et l'approbation des notes/justificatifs médicaux ?

---

## 📋 3. Besoins Fonctionnels Identifiés

D'après nos énoncés de problème, le système *doit* comporter :

* **Accès par Rôles :** Vues et permissions distinctes pour les Enseignants (Saisie), les Administrateurs (Vérification/Approbation) et les Étudiants (Consultation uniquement).
* **Marquage par Session :** Capacité de segmenter les présences par tranches horaires prédéfinies (9-11, 11-14, 14-17).
* **Synchronisation en Temps Réel :** Les données saisies par un enseignant doivent apparaître instantanément sur le tableau de bord de l'Admin.
* **Indicateurs Visuels d'État :** L'interface utilisateur doit s'appuyer fortement sur des repères visuels (couleurs, icônes) pour un balayage rapide.
* **Système de Pièces Jointes Numériques :** Capacité de télécharger et de stocker des fichiers image/PDF liés à des records d'absence spécifiques pour justification.

---

## 🎯 4. Prochaines Étapes

Le problème étant clairement défini, nous allons passer à la phase d'Idéation pour réfléchir à des solutions potentielles.
