# 🎓 AttendanceFlow-AMS: Système de Gestion des Absences

Bienvenue sur le dépôt principal du projet **AttendanceFlow-AMS**, une solution numérique innovante conçue pour moderniser et simplifier la gestion des absences en milieu scolaire/académique.

---

## 🚀 La Vision

Le système éducatif actuel souffre souvent d'une inefficacité systémique : le "pont humain" entre le support papier et les bases de données numériques. **AMS** a été pensé pour éliminer ce délai de traitement (data lag) en offrant un flux de travail "Direct-to-System" pour les formateurs et les administrateurs.

## 👥 Personas & Approche Empathique

Le projet a été pensé via la méthodologie **Design Thinking**, en se concentrant sur trois acteurs clés :
1. **Madame Hannane (Administratrice)** : Cherche à passer de la "saisie manuelle" (fastidieuse) à la "validation" (efficace).
2. **Imane Bouziane (Formatrice)** : A besoin d'une interface mobile ultra-rapide pour valider les présences par session (9h-11h, 11h-14h, 14h-17h).
3. **Anouar Benyakhelef (Étudiant)** : Demande plus de transparence en temps réel et la possibilité de soumettre ses justificatifs numériquement.

---

## 🛠️ Stack Technique

- **Backend** : Laravel 12 (PHP 8.2+)
- **Frontend** : Blade, Tailwind CSS (Mobile-First UI)
- **Base de données** : MySQL
- **Architecture** : MVC / Service Pattern

## 🏗️ Structure du Projet & Documentation

Toute l'analyse fonctionnelle et technique se trouve dans le répertoire `Analyse/` :
- `Analyse/définition_de_problème/` : Synthèse des "How Might We".
- `Analyse/cas_utilisation/` : Diagrammes de cas d'utilisation (Global, Sprint 1, Sprint 2).
- `Analyse/diagramme_de_classe/` : Modélisation des données.

Les rapports et présentations associés sont disponibles dans les dossiers `Raport/` et `Presentation/`.

---

## 📅 Roadmap & Périmètre (MVP)

Le projet est divisé en Sprints (Méthode Agile/Scrum) :

- **Sprint 1 (Actuel)** : *Digitalisation du Pointage*. Focus sur l'authentification (RBAC) et l'interface formateur pour la saisie directe par session. Monitoring en temps réel pour l'administration.
- **Sprint 2** : *Justificatifs & Consultation*. Soumission numérique des notes médicales par les étudiants et validation par l'administration.
- **Sprint 3 (Post-MVP)** : *Dashboard & Analytics*. Exports légaux (Excel/PDF) et alertes automatisées.

---

## 🚦 Mise en Route (Développement)

```bash
# Cloner le dépôt
git clone https://github.com/abdelhaymallouli/School-Absence-Management-System-AMS-.git

# Installer les dépendances
composer install
npm install

# Créer le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Lancer les migrations et les seeders (à venir)
php artisan migrate --seed

# Lancer le serveur de développement
php artisan serve
npm run dev
```

---

*Ce projet est réalisé dans le cadre d'un Projet de Fin de Formation.*
