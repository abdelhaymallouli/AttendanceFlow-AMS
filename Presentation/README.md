---
marp: true
theme: default
_class: lead
_paginate: false
paginate: true
backgroundColor: #ffffff
style: |
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap');
  section {
    font-family: 'Outfit', sans-serif;
    font-size: 24px;
    color: #2d3436;
    line-height: 1.6;
    padding: 60px 80px;
    background: linear-gradient(135deg, #ffffff 0%, #f1f2f6 100%);
  }
  footer { width: 100%; text-align: right; font-size: 14px; color: #a4b0be; font-weight: 300; }
  .logo-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: absolute;
    top: 30px;   
    left: 50px;
    right: 50px;
  }
  .logo-header img { height: 110px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1)); transition: transform 0.3s ease; }
  .logo-header img:hover { transform: scale(1.05); }
  
  h1 { color: #0984e3; font-size: 3em; margin-top: 80px; text-align: left; font-weight: 700; letter-spacing: -1px; }
  h2 { color: #0984e3; font-size: 2.2em; border-bottom: 3px solid #74b9ff; margin-bottom: 35px; font-weight: 600; padding-bottom: 10px; }
  h3 { text-align: left; color: #636e72; margin-top: 0; font-weight: 400; font-size: 1.4em; }
  h4 { color: #0984e3; margin-bottom: 15px; font-weight: 600; }

  .highlight { color: #0984e3; font-weight: 600; }

  .sommaire-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-top: 30px;
  }
  .sommaire-item {
    display: flex;
    align-items: center;
    background: #ffffff;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    border-left: 6px solid #0984e3;
    transition: all 0.3s ease;
  }
  .sommaire-item:hover { transform: translateX(10px); box-shadow: 0 15px 30px rgba(9, 132, 227, 0.1); }
  .sommaire-num {
    background: #0984e3; color: white; width: 40px; height: 40px;
    display: flex; justify-content: center; align-items: center;
    border-radius: 10px; font-weight: bold; margin-right: 20px; flex-shrink: 0; font-size: 1.1em;
  }
  .sommaire-text { font-weight: 500; font-size: 1.1em; color: #2d3436; }

  .img-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    margin-top: 20px;
  }
  .img-premium {
    width: 90%;
    max-height: 420px;
    object-fit: contain;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    border: 1px solid rgba(255,255,255,0.5);
  }

  .glass-card {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    padding: 35px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.4);
    box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    margin-top: 20px;
    width: 100%;
  }
  .problem-callout {
    background: #dfe6e9;
    padding: 25px;
    border-radius: 12px;
    border-left: 5px solid #d63031;
    margin: 20px 0;
    font-style: italic;
  }

  .tech-card {
    background: #ffffff;
    padding: 25px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.04);
    border-top: 6px solid #0984e3;
    height: 100%;
  }
  .tech-card ul { padding-left: 20px; margin: 0; }
  .tech-card li { margin-bottom: 12px; list-style-type: '→ '; }

  .maquette-showcase {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 30px;
    align-items: center;
  }
  .device-frame {
    background: #2d3436;
    padding: 10px;
    border-radius: 25px;
    box-shadow: 0 30px 60px rgba(0,0,0,0.25);
  }
  .device-frame img { border-radius: 15px; display: block; }
---

<div class="logo-header">
  <img src="images/ofppt-logo.png" alt="OFPPT">
  <img src="images/logo-solicode.png" alt="Solicode">
</div>

# **Projet de Fin de Formation**

### AttendanceFlow-AMS

**Système de Gestion des Absences de Nouvelle Génération**

**Réalisé par :** <span class="highlight">Abdelhay Mallouli</span>  
**Encadré par :** <span class="highlight">M. ESSARRAJ Fouad</span>  
**Filière :** Développement Mobile

---

## Sommaire

<div class="sommaire-grid">
  <div class="sommaire-item"><div class="sommaire-num">1</div><div class="sommaire-text">Contexte du projet</div></div>
  <div class="sommaire-item"><div class="sommaire-num">2</div><div class="sommaire-text">Méthodologie & Agile</div></div>
  <div class="sommaire-item"><div class="sommaire-num">3</div><div class="sommaire-text">Branche Fonctionnelle</div></div>
  <div class="sommaire-item"><div class="sommaire-num">4</div><div class="sommaire-text">Branche Technique</div></div>
  <div class="sommaire-item"><div class="sommaire-num">5</div><div class="sommaire-text">Conception (UML)</div></div>
  <div class="sommaire-item"><div class="sommaire-num">6</div><div class="sommaire-text">Démonstration</div></div>
  <div class="sommaire-item"><div class="sommaire-num">7</div><div class="sommaire-text">Conclusion</div></div>
</div>

---

## 1. Contexte du projet

<div class="img-container">
  <img src="images/contexte.png" class="img-premium" alt="Contexte du Projet">
</div>

---

## 2. Méthodologie : Design Thinking

<div class="img-container">
  <img src="images/designThinking.png" class="img-premium" style="max-height: 320px;" alt="Design Thinking">
  <div class="glass-card" style="margin-top: 30px; text-align: center;">
    <p style="font-size: 1.2em; font-weight: 600; color: #0984e3; margin: 0;">Empathie → Définition → Idéation → Prototype → Test</p>
  </div>
</div>

---

## 2. Méthodologie : Scrum (Agile)

<div class="img-container">
  <img src="images/scrum.jpg" class="img-premium" style="max-height: 380px;" alt="Scrum">
  <div class="glass-card" style="margin-top: 25px; border-top: 4px solid #00b894;">
    <p style="font-size: 1.1em; font-weight: 500; color: #2d3436; margin: 0;">L'agilité au cœur du développement pour une fiabilité maximale.</p>
  </div>
</div>

---

## 3. Branche Fonctionnelle : Problématique

<div class="glass-card">
  <div class="problem-callout">
    "La transition inefficace du <strong>support papier</strong> vers la saisie manuelle sur <strong>Excel</strong> génère des erreurs et une perte de temps critique."
  </div>
  <p><strong>Impact :</strong> Surcharge logistique pour les formateurs et opacité pour l'administration.</p>
  <p><strong>Solution :</strong> Automatisation complète dès la source (Mobile) vers le Cloud (Web & Reports).</p>
</div>

---

## 3. Branche Fonctionnelle : Écosystème Web

<div class="img-container">
  <img src="images/global-w.png" class="img-premium" alt="Web Global">
</div>

---

## 3. Branche Fonctionnelle : Excellence Mobile

<div class="img-container">
  <img src="images/global-m.png" class="img-premium" alt="Mobile Global">
</div>

---

## Branche Fonctionnelle : Maquettes Premium

<div class="maquette-showcase">
  <div style="text-align: center;">
    <div class="device-frame" style="border-radius: 12px;">
       <img src="images/maquette.png" style="width: 100%; border-radius: 4px;" alt="Admin Dashboard">
    </div>
    <p style="font-size: 0.9em; margin-top: 15px; color: #636e72;">Dashboard Administratif (Web)</p>
  </div>
  <div style="text-align: center;">
    <div class="device-frame">
       <img src="images/maquete-m.png" style="height: 380px; width: auto;" alt="Mobile App">
    </div>
    <p style="font-size: 0.9em; margin-top: 15px; color: #636e72;">Application Mobile (Saisie Directe)</p>
  </div>
</div>

---

## 4. Branche Technique : Architecture & Stack

<div class="sommaire-grid" style="grid-template-columns: 1fr 1fr;">
  <div class="tech-card">
    <h4>Backend Excellence</h4>
    <ul>
      <li><strong>Laravel 12 :</strong> Framework PHP robuste (8.2+).</li>
      <li><strong>Service Pattern :</strong> Architecture maintenable.</li>
      <li><strong>MySQL / Eloquent :</strong> Gestion fluide des données.</li>
    </ul>
  </div>
  <div class="tech-card" style="border-top-color: #00b894;">
    <h4>Modern Frontend</h4>
    <ul>
      <li><strong>Tailwind CSS & Preline :</strong> Design Pixel-perfect.</li>
      <li><strong>Livewire / Alpine.js :</strong> Interactivité temps-réel.</li>
      <li><strong>Vite :</strong> Pipeline de build haute performance.</li>
    </ul>
  </div>
</div>

---

## 5. Conception : Diagramme de Classe

<div class="img-container" style="margin-top: 0;">
  <img src="images/diagramme-class.png" class="img-premium" style="max-height: 520px; width: auto;" alt="UML Class Diagram">
  <p style="font-size: 0.8em; margin-top: 10px; color: #b2bec3;">Modélisation rigoureuse de la logique métier.</p>
</div>

---

## Merci pour votre attention !

<div class="img-container">
   <h3 style="color: #0984e3; font-weight: 700;">Avez-vous des questions ?</h3>
   <p style="color: #636e72;">AttendanceFlow-AMS | 2026</p>
</div>
