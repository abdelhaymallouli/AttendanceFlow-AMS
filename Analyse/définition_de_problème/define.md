# 🔬 Système de Gestion des Absences (AMS)

> **Phase 2 du Design Thinking : Définir**

Sur la base de nos recherches d'empathie, nous pouvons maintenant définir clairement les problèmes centraux que AttendanceFlow-AMS doit résoudre.

---

## 📌 1. Énoncé du Problème

**Pour l'Administrateur (Madame Hannane) :**
Le système actuel impose une charge cognitive élevée due à un flux de données manuel et papier, entraînant une latence critique de l'information (4-6h) et un taux d'erreur humaine non négligeable. Le besoin est une plateforme numérique assurant la transmission instantanée et sécurisée des statuts de présence de la source (classe) vers l'administration.

**Pour l'Étudiant (Anouar Benyakhelef) :**
L'étudiant souffre d'un manque de transparence radical concernant son assiduité et d'une lourdeur bureaucratique pour justifier ses absences. Le système doit fournir une interface mobile permettant la consultation instantanée des statistiques de présence et la soumission dématérialisée des justificatifs médicaux afin de réduire les déplacements inutiles et le stress lié à l'incertitude.

**Pour le Formateur (Fouad Essarraj) :**
La gestion manuelle des présences par les formateurs est une source de distraction pédagogique et une faille logistique (risque de perte, erreurs de signature). Le système doit proposer une interface de saisie ultra-rapide (gestion par exception) intégrée à l'emploi du temps, permettant une transmission instantanée des données à l'administration sans nécessiter de déplacement physique.

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
