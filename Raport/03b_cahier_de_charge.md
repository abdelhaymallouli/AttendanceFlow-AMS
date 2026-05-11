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
