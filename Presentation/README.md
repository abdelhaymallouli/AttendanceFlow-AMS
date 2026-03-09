---
marp: true
theme: default
_class: lead
_paginate: false
paginate: true
backgroundColor: #ffffff
style: |
  section {
    font-size: 22px;
    color: #333;
    line-height: 1.6;
    padding: 60px 80px;
  }
  footer { width: 100%; text-align: right; font-size: 14px; color: #888; }
  .logo-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: absolute;
    top: 40px;   
    left: 60px;
    right: 60px;
  }
  .logo-header img { height: 140px; margin: 0; margin-left:10px; margin-right:10px }
  h1 { color: #088dc7; font-size: 2.8em; margin-top: 100px; text-align: left; }
  h2 { color: #088dc7; font-size: 2em; border-bottom: 2px solid #088dc7; margin-bottom: 40px;}
  h3 { text-align: left; color: #444; margin-top: 0; }

  .sommaire-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
  }
  .sommaire-item {
    display: flex;
    align-items: center;
    background: #f4faff;
    border-radius: 12px;
    padding: 15px 20px;
    border-left: 5px solid #088dc7;
  }
  .sommaire-num {
    background: #088dc7; color: white; width: 35px; height: 35px;
    display: flex; justify-content: center; align-items: center;
    border-radius: 50%; font-weight: bold; margin-right: 15px; flex-shrink: 0;
  }

  .img-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
  }
  .img-methodo {
    width: 85%;
    height: auto;
    max-height: 450px;
    object-fit: contain;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  }

  .dt-card {
    background: #f0f7fa;
    padding: 30px;
    border-radius: 10px;
    border-top: 6px solid #088dc7;
    text-align: left;
    margin-top: 20px;
    width: 100%;
  }

  /* --- FIX COULEURS TECH STACK --- */
  .tech-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
  }
  .badge-simple {
    padding: 8px 18px;
    border-radius: 6px;
    font-weight: 600;
    background-color: #545353ff; /* Gris foncé unique */
    color: #ffffff !important;
    font-size: 0.85em;
    border: 1px solid #222;
  }
  .maquette-grid {
    display: flex;
    gap: 15px;
    justify-content: center;
    align-items: flex-start;
    height: 350px;
  }
---

<div class="logo-header">
  <img src="images/ofppt-logo.png" alt="Logo Left">
  <img src="images/logo-solicode.png" alt="Logo Right">
</div>

# **Projet de Fin de Formation**

### AttendanceFlow-AMS

**Système de Gestion des Absences**

**Réalisé par :** <span class="highlight">Abdelhay Mallouli</span>  
**Encadré par :** <span class="highlight">M. ESSARRAJ Fouad</span>  
**Filière :** Développement Mobile

---

## Sommaire

<div class="sommaire-grid">
  <div class="sommaire-item"><div class="sommaire-num">1</div><div class="sommaire-text">Contexte du projet</div></div>
  <div class="sommaire-item"><div class="sommaire-num">2</div><div class="sommaire-text">Méthodologie de travail</div></div>
  <div class="sommaire-item"><div class="sommaire-num">3</div><div class="sommaire-text">Branche Fonctionnelle</div></div>
  <div class="sommaire-item"><div class="sommaire-num">4</div><div class="sommaire-text">Branche Technique</div></div>
  <div class="sommaire-item"><div class="sommaire-num">5</div><div class="sommaire-text">Conception</div></div>
    <div class="sommaire-item"><div class="sommaire-num">6</div><div class="sommaire-text">Démonstration</div></div>
  <div class="sommaire-item"><div class="sommaire-num">7</div><div class="sommaire-text">Conclusion</div></div>
</div>

---

## 1. Contexte du projet

<div class="img-container">
  <img src="images/contexte.png" class="img-methodo" style="max-height: 400px;" alt="Contexte du Projet">
</div>

---

## 1. Contexte : Carte d'Empathie - Mme Hannane

<div class="img-container">
  <img src="images/mindmap_hannane.png" class="img-methodo" style="max-height: 450px;" alt="Carte d'Empathie Hannane">
</div>

---


## 1. Contexte : Carte d'Empathie - Anouar

<div class="img-container">
  <img src="images/mindmap_anouar.png" class="img-methodo" style="max-height: 450px;" alt="Carte d'Empathie Anouar">
</div>

---


## 1. Contexte : Carte d'Empathie - Mme Imane

<div class="img-container">
  <img src="images/mindmap_imane.png" class="img-methodo" style="max-height: 450px;" alt="Carte d'Empathie Imane">
</div>

---

## 2. Méthodologie : Design Thinking

<div class="img-container">
  <img src="images/designThinking.png" class="img-methodo" style="max-height: 350px;" alt="Design Thinking">
  <p style="font-size: 1.1em; font-weight: bold; color: #088dc7;">Empathie → Définition → Idéation → Prototype → Test</p>
</div>

---


## 2. Méthodologie : Scrum (Agile)

<div class="img-container">
  <img src="images/scrum.jpg" class="img-methodo" style="max-height: 400px;" alt="Scrum">
  <p style="font-size: 1.1em; font-weight: bold; color: #088dc7;">Itérations rapides pour une livraison continue de valeur.</p>
</div>


---

## 3. Branche Fonctionnelle : Définition du Problème

<div class="dt-card">
  <p>Le problème majeur réside dans la transition inefficace du <strong>support papier vers la saisie manuelle sur Excel</strong>.</p>
  <p>Ce processus archaïque génère une surcharge logistique pour les formateurs et un décalage d'information critique pour l'administration.</p>
  <p><strong>Objectif :</strong> Suppression de la "double saisie" par une automatisation directe à la source.</p>
</div>

---

## 3. Branche Fonctionnelle : Web (Global)

![Web Global](images/global-w.png)

---

## 3. Branche Fonctionnelle : Mobile (Global)

![Mobile Global](images/global-m.png)

---



## Branche Fonctionnelle : Maquettes (UI/UX)

<div class="maquette-grid">
  <div style="text-align: center;">
    <img src="images/maquette.png" class="img-methodo" style="height: 360px; width: auto;" alt="Interface Admin">
    <p style="font-size: 0.8rem; color: #666;">Tableau de Bord Administratif (Web)</p>
  </div>
  <div style="text-align: center;">
    <img src="images/maquete-m.png" class="img-methodo" style="height: 360px; width: auto;" alt="Interface Mobile">
    <p style="font-size: 0.8rem; color: #666;">Application Mobile (Saisie Terrain)</p>
  </div>
</div>

---

## 4. Branche Technique : Tech Stack

<div class="sommaire-grid">
  <div class="dt-card" style="margin-top:0;">
    <h4>Backend & Architecture</h4>
    <ul>
      <li><strong>Laravel 12 (PHP 8.2+) :</strong> Coeur d'application robuste.</li>
      <li><strong>Architecture Service-Pattern :</strong> Découplage de la logique métier.</li>
      <li><strong>Base de données :</strong> MySQL relationnelle.</li>
    </ul>
  </div>
  <div class="dt-card" style="margin-top:0; border-top-color: #27ae60;">
    <h4>Frontend & Outillage</h4>
    <ul>
      <li><strong>Blade & Tailwind CSS :</strong> Interface responsive et premium.</li>
      <li><strong>Livewire / Alpine.js :</strong> Interactivité temps réel.</li>
      <li><strong>Vite :</strong> Compilation ultra-rapide des assets.</li>
    </ul>
  </div>
</div>

---

## 5. Conception : Diagramme de Classe

<div class="maquette-grid">
  <div style="text-align: center;">
    <img src="images/diagramme-class.png" class="img-methodo" style="height: 450px; width: auto;" alt="Diagramme de Classe">
    <p style="font-size: 0.8rem; color: #666;">Diagramme de Classe</p>
  </div>
</div>

---

## Merci pour votre attention !
