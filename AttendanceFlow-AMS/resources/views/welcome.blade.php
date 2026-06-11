<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>SoliCode AMS | Gestion des Présences</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --ink:#0d0f12;
  --ink-mid:#3a3d45;
  --ink-soft:#6b7080;
  --ink-mute:#9ea5b4;
  --surface:#ffffff;
  --surface-2:#f5f6f9;
  --surface-3:#eef0f5;
  --border:#e2e5ed;
  --border-strong:#cdd1dc;
  --accent:#1a56db;
  --accent-dark:#1341b0;
  --accent-light:#ebf0fd;
  --accent-mid:#3b6ef5;
  --green:#0fa96a;
  --green-light:#e6f8f1;
  --amber:#d97706;
  --amber-light:#fef3c7;
  --red:#dc2626;
  --radius-sm:6px;
  --radius-md:12px;
  --radius-lg:20px;
  --radius-xl:32px;
  --shadow-sm:0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);
  --shadow-md:0 4px 16px rgba(0,0,0,.07),0 2px 6px rgba(0,0,0,.04);
  --shadow-lg:0 12px 40px rgba(0,0,0,.10),0 4px 12px rgba(0,0,0,.06);
}

html{scroll-behavior:smooth;-webkit-font-smoothing:antialiased}
body{font-family:'DM Sans',sans-serif;background:var(--surface);color:var(--ink);line-height:1.6;overflow-x:hidden}

/* ── NAVBAR ── */
.nav{position:fixed;top:0;left:0;right:0;z-index:100;padding:0 max(1.5rem, calc((100vw - 1200px)/2))}
.nav-inner{display:flex;align-items:center;justify-content:space-between;padding:.9rem 1.5rem;margin-top:.75rem;background:rgba(255,255,255,.88);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid var(--border);border-radius:var(--radius-xl);box-shadow:var(--shadow-sm);transition:box-shadow .3s}
.nav-inner:hover{box-shadow:var(--shadow-md)}
.logo{display:flex;align-items:center;gap:10px;text-decoration:none}
.logo-icon{width:38px;height:38px;background:var(--accent);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;flex-shrink:0}
.logo-text{font-size:1.05rem;font-weight:700;color:var(--ink);letter-spacing:-.02em}
.logo-text span{color:var(--accent)}
.nav-links{display:flex;align-items:center;gap:2rem}
.nav-links a{font-size:.8rem;font-weight:500;color:var(--ink-soft);text-decoration:none;letter-spacing:.04em;text-transform:uppercase;transition:color .2s}
.nav-links a:hover{color:var(--ink)}
.nav-cta{display:flex;align-items:center;gap:12px}
.btn-ghost{padding:.5rem 1.1rem;border:1px solid var(--border);border-radius:var(--radius-md);background:transparent;color:var(--ink-mid);font-family:'DM Sans',sans-serif;font-size:.8rem;font-weight:500;cursor:pointer;text-decoration:none;transition:background .2s,border-color .2s}
.btn-ghost:hover{background:var(--surface-2);border-color:var(--border-strong)}
.btn-primary{padding:.55rem 1.25rem;border:none;border-radius:var(--radius-md);background:var(--accent);color:#fff;font-family:'DM Sans',sans-serif;font-size:.8rem;font-weight:600;cursor:pointer;text-decoration:none;letter-spacing:.01em;transition:background .2s,box-shadow .2s,transform .15s;box-shadow:0 2px 8px rgba(26,86,219,.3)}
.btn-primary:hover{background:var(--accent-dark);transform:translateY(-1px);box-shadow:0 4px 14px rgba(26,86,219,.4)}
.btn-primary:active{transform:translateY(0)}
.nav-hamburger{display:none;background:none;border:1px solid var(--border);border-radius:var(--radius-sm);padding:.4rem .5rem;cursor:pointer;color:var(--ink-mid);font-size:1.1rem}

/* ── HERO ── */
.hero{min-height:100vh;display:flex;align-items:center;padding-top:100px;padding-bottom:4rem;background:var(--surface);position:relative;overflow:hidden}
.hero::before{content:'';position:absolute;top:-20%;right:-10%;width:700px;height:700px;background:radial-gradient(circle,rgba(26,86,219,.06) 0%,transparent 70%);pointer-events:none}
.hero::after{content:'';position:absolute;bottom:-10%;left:-5%;width:500px;height:500px;background:radial-gradient(circle,rgba(15,169,106,.05) 0%,transparent 70%);pointer-events:none}
.container{max-width:1200px;margin:0 auto;padding:0 1.5rem;position:relative}
.hero-grid{display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center}
.hero-badge{display:inline-flex;align-items:center;gap:8px;padding:.35rem .9rem;background:var(--accent-light);border:1px solid rgba(26,86,219,.2);border-radius:999px;font-size:.72rem;font-weight:600;color:var(--accent);letter-spacing:.05em;text-transform:uppercase;margin-bottom:1.75rem}
.pulse-dot{width:6px;height:6px;border-radius:50%;background:var(--accent);position:relative}
.pulse-dot::after{content:'';position:absolute;inset:-3px;border-radius:50%;background:var(--accent);opacity:.4;animation:pulse 1.8s ease-out infinite}
@keyframes pulse{0%{transform:scale(1);opacity:.4}100%{transform:scale(2.2);opacity:0}}
.hero h1{font-size:clamp(2.8rem,5vw,4.2rem);font-weight:700;letter-spacing:-.04em;line-height:1.05;color:var(--ink);margin-bottom:1.25rem}
.hero h1 em{font-family:'Instrument Serif',serif;font-style:italic;color:var(--accent);font-weight:400}
.hero p{font-size:1.05rem;color:var(--ink-soft);line-height:1.75;max-width:480px;margin-bottom:2.5rem;font-weight:400}
.hero-actions{display:flex;gap:12px;flex-wrap:wrap}
.btn-hero{padding:.85rem 1.8rem;border-radius:var(--radius-md);font-family:'DM Sans',sans-serif;font-size:.9rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all .2s;cursor:pointer}
.btn-hero-primary{background:var(--accent);color:#fff;border:none;box-shadow:0 3px 12px rgba(26,86,219,.35)}
.btn-hero-primary:hover{background:var(--accent-dark);transform:translateY(-2px);box-shadow:0 6px 20px rgba(26,86,219,.4)}
.btn-hero-secondary{background:transparent;color:var(--ink-mid);border:1px solid var(--border-strong)}
.btn-hero-secondary:hover{background:var(--surface-2);transform:translateY(-1px)}

/* ── HERO VISUAL ── */
.hero-visual{position:relative}
.dashboard-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-xl);padding:1.5rem;box-shadow:var(--shadow-lg);position:relative;overflow:hidden}
.dashboard-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--accent),var(--green))}
.dash-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem}
.dash-title{font-size:.7rem;font-weight:600;color:var(--ink-soft);text-transform:uppercase;letter-spacing:.07em}
.dash-date{font-size:.7rem;color:var(--ink-mute);font-weight:400}
.attendance-row{display:flex;align-items:center;gap:10px;padding:.65rem .9rem;border-radius:var(--radius-md);margin-bottom:.4rem;background:var(--surface-2);transition:background .2s}
.attendance-row:hover{background:var(--surface-3)}
.att-avatar{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;flex-shrink:0}
.att-name{flex:1;font-size:.8rem;font-weight:500;color:var(--ink)}
.att-status{font-size:.68rem;font-weight:600;padding:.2rem .55rem;border-radius:999px}
.status-present{background:var(--green-light);color:var(--green)}
.status-absent{background:#fef2f2;color:var(--red)}
.status-late{background:var(--amber-light);color:var(--amber)}
.att-time{font-size:.68rem;color:var(--ink-mute);font-weight:400;min-width:42px;text-align:right}
.progress-section{margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--border)}
.progress-label{display:flex;justify-content:space-between;align-items:center;margin-bottom:.5rem}
.progress-label span{font-size:.72rem;color:var(--ink-soft);font-weight:500}
.progress-label strong{font-size:.72rem;color:var(--green);font-weight:700}
.progress-track{height:6px;background:var(--surface-3);border-radius:999px;overflow:hidden}
.progress-fill{height:100%;border-radius:999px;background:linear-gradient(90deg,var(--green),#34d399);width:82%}

/* Floating badges */
.float-badge{position:absolute;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);padding:.75rem 1rem;box-shadow:var(--shadow-md);display:flex;align-items:center;gap:10px;white-space:nowrap}
.float-badge-icon{width:34px;height:34px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
.fb-1{top:-1.5rem;right:-1.5rem;animation:float1 4s ease-in-out infinite}
.fb-2{bottom:-1rem;left:-1.5rem;animation:float2 5s ease-in-out infinite}
@keyframes float1{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
@keyframes float2{0%,100%{transform:translateY(0)}50%{transform:translateY(8px)}}

/* ── STATS STRIP ── */
.stats-strip{padding:3rem 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);background:var(--surface-2)}
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0}
.stat-item{text-align:center;padding:1.5rem 1rem;border-right:1px solid var(--border)}
.stat-item:last-child{border-right:none}
.stat-num{font-size:2rem;font-weight:700;letter-spacing:-.04em;color:var(--ink);line-height:1;margin-bottom:.3rem}
.stat-label{font-size:.75rem;color:var(--ink-soft);font-weight:500;text-transform:uppercase;letter-spacing:.06em}

/* ── FEATURES ── */
.section{padding:5rem 0}
.section-label{font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:var(--accent);margin-bottom:.75rem;display:block}
.section-title{font-size:clamp(1.9rem,3.5vw,2.8rem);font-weight:700;letter-spacing:-.04em;color:var(--ink);line-height:1.15;margin-bottom:1rem}
.section-title em{font-family:'Instrument Serif',serif;font-style:italic;font-weight:400}
.section-sub{font-size:1rem;color:var(--ink-soft);line-height:1.75;max-width:520px}
.section-head{margin-bottom:3.5rem}
.features-layout{display:grid;grid-template-columns:1fr 1fr;gap:2rem;align-items:start}
.features-left{display:flex;flex-direction:column;gap:1.25rem}
.feature-card{padding:1.5rem;border:1px solid var(--border);border-radius:var(--radius-lg);background:var(--surface);cursor:pointer;transition:all .25s;position:relative;overflow:hidden}
.feature-card:hover,.feature-card.active{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-light)}
.feature-card.active::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:var(--accent);border-radius:2px 0 0 2px}
.fc-icon{width:40px;height:40px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:1.1rem;margin-bottom:.9rem;flex-shrink:0}
.fc-icon-blue{background:var(--accent-light);color:var(--accent)}
.fc-icon-green{background:var(--green-light);color:var(--green)}
.fc-icon-amber{background:var(--amber-light);color:var(--amber)}
.fc-title{font-size:.95rem;font-weight:600;color:var(--ink);margin-bottom:.35rem}
.fc-desc{font-size:.85rem;color:var(--ink-soft);line-height:1.65}
.features-preview{position:sticky;top:120px;border:1px solid var(--border);border-radius:var(--radius-xl);overflow:hidden;box-shadow:var(--shadow-lg)}
.preview-header{background:var(--surface-2);padding:1rem 1.25rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:.5rem}
.traffic-dot{width:10px;height:10px;border-radius:50%}
.preview-body{padding:1.5rem;background:var(--surface);min-height:320px}
.preview-title{font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--ink-soft);margin-bottom:1rem}
.quick-entry{display:flex;flex-direction:column;gap:.6rem}
.qe-row{display:flex;align-items:center;gap:.75rem;padding:.7rem .9rem;border:1px solid var(--border);border-radius:var(--radius-md)}
.qe-check{width:20px;height:20px;border-radius:5px;border:2px solid var(--border-strong);display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all .2s;cursor:pointer}
.qe-check.checked{background:var(--green);border-color:var(--green);color:#fff}
.qe-name{flex:1;font-size:.82rem;font-weight:500;color:var(--ink)}
.qe-tag{font-size:.68rem;font-weight:600;padding:.2rem .5rem;border-radius:999px}
.submit-bar{margin-top:1rem;padding:.8rem 1rem;background:var(--accent);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:space-between}
.submit-bar span{font-size:.8rem;font-weight:600;color:#fff}
.submit-bar small{font-size:.72rem;color:rgba(255,255,255,.7)}

/* ── ROLES ── */
.roles-section{background:var(--surface-2);padding:5rem 0}
.roles-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
.role-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-xl);padding:2rem;transition:all .25s;position:relative;overflow:hidden}
.role-card:hover{box-shadow:var(--shadow-lg);transform:translateY(-4px)}
.role-card::after{content:'';position:absolute;bottom:0;left:0;right:0;height:3px}
.role-teacher::after{background:var(--accent)}
.role-student::after{background:var(--green)}
.role-admin::after{background:var(--amber)}
.role-icon{width:52px;height:52px;border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin-bottom:1.25rem}
.role-icon-blue{background:var(--accent-light);color:var(--accent)}
.role-icon-green{background:var(--green-light);color:var(--green)}
.role-icon-amber{background:var(--amber-light);color:var(--amber)}
.role-title{font-size:1.05rem;font-weight:700;color:var(--ink);margin-bottom:.4rem;letter-spacing:-.02em}
.role-sub{font-size:.78rem;font-weight:500;color:var(--ink-mute);text-transform:uppercase;letter-spacing:.06em;margin-bottom:1rem}
.role-list{list-style:none;display:flex;flex-direction:column;gap:.55rem}
.role-list li{display:flex;align-items:flex-start;gap:.6rem;font-size:.83rem;color:var(--ink-soft);line-height:1.5}
.role-list li::before{content:'';width:5px;height:5px;border-radius:50%;background:var(--border-strong);margin-top:.5em;flex-shrink:0}

/* ── HOW IT WORKS ── */
.how-section{padding:5rem 0}
.steps-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0;margin-top:3rem;position:relative}
.steps-grid::before{content:'';position:absolute;top:28px;left:calc(12.5%);right:calc(12.5%);height:1px;background:var(--border);z-index:0}
.step-item{text-align:center;padding:0 1rem;position:relative;z-index:1}
.step-num{width:56px;height:56px;border-radius:50%;background:var(--surface);border:1.5px solid var(--border);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:.8rem;font-weight:700;color:var(--accent);position:relative;z-index:2}
.step-num.active{background:var(--accent);border-color:var(--accent);color:#fff}
.step-title{font-size:.9rem;font-weight:600;color:var(--ink);margin-bottom:.5rem}
.step-desc{font-size:.78rem;color:var(--ink-soft);line-height:1.6}

/* ── CTA ── */
.cta-section{padding:5rem 0}
.cta-card{background:var(--ink);border-radius:var(--radius-xl);padding:4rem;position:relative;overflow:hidden;text-align:center}
.cta-card::before{content:'';position:absolute;top:-60%;right:-20%;width:500px;height:500px;background:radial-gradient(circle,rgba(26,86,219,.25) 0%,transparent 65%);pointer-events:none}
.cta-card::after{content:'';position:absolute;bottom:-60%;left:-15%;width:400px;height:400px;background:radial-gradient(circle,rgba(15,169,106,.15) 0%,transparent 65%);pointer-events:none}
.cta-label{font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:1rem;display:block}
.cta-card h2{font-size:clamp(2rem,4vw,3rem);font-weight:700;color:#fff;letter-spacing:-.04em;margin-bottom:1rem;line-height:1.1}
.cta-card h2 em{font-family:'Instrument Serif',serif;font-style:italic;font-weight:400;color:rgba(255,255,255,.6)}
.cta-card p{font-size:1rem;color:rgba(255,255,255,.55);max-width:440px;margin:0 auto 2.5rem;line-height:1.75}
.cta-actions{display:flex;align-items:center;justify-content:center;gap:12px;flex-wrap:wrap;position:relative;z-index:1}
.btn-cta-primary{padding:.9rem 2rem;background:#fff;color:var(--ink);border:none;border-radius:var(--radius-md);font-family:'DM Sans',sans-serif;font-size:.9rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all .2s;cursor:pointer}
.btn-cta-primary:hover{background:var(--accent);color:#fff;transform:translateY(-2px)}
.btn-cta-ghost{padding:.9rem 2rem;background:transparent;color:rgba(255,255,255,.65);border:1px solid rgba(255,255,255,.15);border-radius:var(--radius-md);font-family:'DM Sans',sans-serif;font-size:.9rem;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all .2s;cursor:pointer}
.btn-cta-ghost:hover{background:rgba(255,255,255,.08);color:#fff}

/* ── FOOTER ── */
footer{border-top:1px solid var(--border);padding:2.5rem 0}
.footer-inner{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem}
.footer-copy{font-size:.77rem;color:var(--ink-mute);font-weight:400}
.footer-links{display:flex;align-items:center;gap:1.25rem}
.footer-links a{font-size:1.1rem;color:var(--ink-mute);text-decoration:none;transition:color .2s}
.footer-links a:hover{color:var(--ink)}

/* ── MOBILE NAV ── */
.mobile-nav{display:none;flex-direction:column;gap:.25rem;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);padding:.5rem;margin-top:.5rem;box-shadow:var(--shadow-md)}
.mobile-nav a{padding:.65rem .9rem;border-radius:var(--radius-sm);font-size:.85rem;font-weight:500;color:var(--ink-mid);text-decoration:none;transition:background .15s}
.mobile-nav a:hover{background:var(--surface-2)}
.mobile-nav.open{display:flex}

/* ── RESPONSIVE ── */
@media(max-width:900px){
  .hero-grid{grid-template-columns:1fr;gap:3rem;text-align:center}
  .hero p{margin:0 auto 2.5rem}
  .hero-actions{justify-content:center}
  .hero-visual{order:-1}
  .fb-1{top:-1rem;right:0}
  .fb-2{bottom:-1rem;left:0}
  .hero::before,.hero::after{display:none}
  .stats-grid{grid-template-columns:repeat(2,1fr)}
  .stat-item:nth-child(2){border-right:none}
  .stat-item:nth-child(3){border-top:1px solid var(--border)}
  .stat-item:nth-child(4){border-top:1px solid var(--border);border-right:none}
  .features-layout{grid-template-columns:1fr}
  .features-preview{position:static}
  .roles-grid{grid-template-columns:1fr}
  .steps-grid{grid-template-columns:1fr 1fr;gap:2rem}
  .steps-grid::before{display:none}
  .nav-links,.nav-cta .btn-ghost{display:none}
  .nav-hamburger{display:block}
}
@media(max-width:600px){
  .hero h1{font-size:2.4rem}
  .stats-grid{grid-template-columns:1fr 1fr}
  .stat-item:nth-child(odd){border-right:1px solid var(--border)}
  .stat-item:nth-child(even){border-right:none}
  .stat-item:nth-child(n+3){border-top:1px solid var(--border)}
  .steps-grid{grid-template-columns:1fr}
  .cta-card{padding:2.5rem 1.5rem}
  .btn-cta-primary,.btn-cta-ghost{width:100%;justify-content:center}
  .cta-actions{flex-direction:column}
  .footer-inner{flex-direction:column;text-align:center}
  .hero-actions{flex-direction:column;align-items:center}
  .btn-hero{width:100%;max-width:320px;justify-content:center}
}
@media(max-width:480px){
  .nav-inner{padding:.7rem 1rem}
  .float-badge{display:none}
  .role-card{padding:1.5rem}
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="nav" id="navbar">
  <div class="nav-inner">
    <a href="#" class="logo">
      <div class="logo-icon"><i class="bi bi-fingerprint"></i></div>
      <span class="logo-text">solicode<span>AMS</span></span>
    </a>
    <div class="nav-links">
      <a href="#features">Fonctionnalités</a>
      <a href="#how">Comment ça marche</a>
      <a href="#roles">Rôles</a>
    </div>
    <div class="nav-cta">
      <a href="/login" class="btn-primary"><i class="bi bi-box-arrow-in-right"></i> Accès portail</a>
      <button class="nav-hamburger" id="hamburger" aria-label="Menu"><i class="bi bi-list"></i></button>
    </div>
  </div>
  <div class="mobile-nav" id="mobileNav">
    <a href="#features">Fonctionnalités</a>
    <a href="#how">Comment ça marche</a>
    <a href="#roles">Rôles</a>
    <a href="/login" style="color:var(--accent);font-weight:600">Accès portail →</a>
  </div>
</nav>

<!-- HERO -->
<main class="hero" id="home">
  <div class="container">
    <div class="hero-grid">
      <div>
        <div class="hero-badge">
          <div class="pulse-dot"></div>
          SoliCode AMS · 2026
        </div>
        <h1>Gestion des<br/>présences,<br/><em>réinventée.</em></h1>
        <p>Un écosystème temps réel connectant formateurs, étudiants et administrateurs. Zéro papier, visibilité totale, chaque séance.</p>
        <div class="hero-actions">
          <a href="/login" class="btn-hero btn-hero-primary"><i class="bi bi-box-arrow-in-right"></i> Accès portail</a>
          <a href="#how" class="btn-hero btn-hero-secondary"><i class="bi bi-play-circle"></i> Voir le fonctionnement</a>
        </div>
      </div>

      <div class="hero-visual">
        <div class="dashboard-card">
          <div class="dash-header">
            <span class="dash-title">Séance · Module Web — Grp 3</span>
            <span class="dash-date">Aujourd'hui, 09:00 – 11:00</span>
          </div>

          <div class="attendance-row">
            <div class="att-avatar" style="background:#e0e9ff;color:#1a56db">AS</div>
            <span class="att-name">Amine Saidi</span>
            <span class="att-status status-present">Présent</span>
            <span class="att-time">08:57</span>
          </div>
          <div class="attendance-row">
            <div class="att-avatar" style="background:#fef3c7;color:#d97706">LM</div>
            <span class="att-name">Laila Moussaoui</span>
            <span class="att-status status-late">Retard</span>
            <span class="att-time">09:14</span>
          </div>
          <div class="attendance-row">
            <div class="att-avatar" style="background:#fef2f2;color:#dc2626">YB</div>
            <span class="att-name">Youssef Benhaddou</span>
            <span class="att-status status-absent">Absent</span>
            <span class="att-time">—</span>
          </div>
          <div class="attendance-row">
            <div class="att-avatar" style="background:#e6f8f1;color:#0fa96a">HZ</div>
            <span class="att-name">Hana Zaoui</span>
            <span class="att-status status-present">Présent</span>
            <span class="att-time">08:59</span>
          </div>

          <div class="progress-section">
            <div class="progress-label">
              <span>Taux de présence</span>
              <strong>82%</strong>
            </div>
            <div class="progress-track"><div class="progress-fill"></div></div>
          </div>
        </div>

        <div class="float-badge fb-1">
          <div class="float-badge-icon" style="background:var(--green-light);color:var(--green)"><i class="bi bi-check2-all"></i></div>
          <div>
            <div style="font-size:.7rem;font-weight:600;color:var(--ink)">Séance soumise</div>
            <div style="font-size:.65rem;color:var(--ink-mute)">Synchronisé il y a 2s</div>
          </div>
        </div>

        <div class="float-badge fb-2">
          <div class="float-badge-icon" style="background:var(--amber-light);color:var(--amber)"><i class="bi bi-file-earmark-check"></i></div>
          <div>
            <div style="font-size:.7rem;font-weight:600;color:var(--ink)">Justification approuvée</div>
            <div style="font-size:.65rem;color:var(--ink-mute)">Médical · Amine S.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- STATS -->
<section class="stats-strip">
  <div class="container">
    <div class="stats-grid">
      <div class="stat-item">
        <div class="stat-num">30s</div>
        <div class="stat-label">Saisie moyenne</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">100%</div>
        <div class="stat-label">Flux sans papier</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">3</div>
        <div class="stat-label">Rôles connectés</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">Live</div>
        <div class="stat-label">Sync temps réel</div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="section" id="features">
  <div class="container">
    <div class="section-head">
      <span class="section-label">Moteur principal</span>
      <h2 class="section-title">Conçu pour la vitesse.<br/><em>Pensé pour la clarté.</em></h2>
      <p class="section-sub">Chaque fonctionnalité est optimisée pour une interaction mobile et une synchronisation immédiate avec l'administration.</p>
    </div>
    <div class="features-layout">
      <div class="features-left">
        <div class="feature-card active" onclick="setActive(this,'flash')">
          <div class="fc-icon fc-icon-blue"><i class="bi bi-phone-vibrate"></i></div>
          <div class="fc-title">Saisie mobile rapide</div>
          <p class="fc-desc">Les formateurs marquent les présences sur leur téléphone en moins de 30 secondes — appuyer, confirmer, terminé.</p>
        </div>
        <div class="feature-card" onclick="setActive(this,'sessions')">
          <div class="fc-icon fc-icon-green"><i class="bi bi-calendar-event"></i></div>
          <div class="fc-title">Séances dynamiques</div>
          <p class="fc-desc">Configurez des créneaux précis par groupe et par module — 09h00–11h00, 11h00–14h00 et plus.</p>
        </div>
        <div class="feature-card" onclick="setActive(this,'justify')">
          <div class="fc-icon fc-icon-amber"><i class="bi bi-file-earmark-medical"></i></div>
          <div class="fc-title">Justifications numériques</div>
          <p class="fc-desc">Les étudiants téléchargent leurs justificatifs médicaux. Les admins les valident en un clic depuis n'importe où.</p>
        </div>
      </div>
      <div class="features-preview">
        <div class="preview-header">
          <div class="traffic-dot" style="background:#ff5f57"></div>
          <div class="traffic-dot" style="background:#febc2e"></div>
          <div class="traffic-dot" style="background:#28c840"></div>
        </div>
        <div class="preview-body" id="previewBody">
          <p class="preview-title">Présences rapides · Séance 09:00</p>
          <div class="quick-entry" id="qeList">
            <div class="qe-row">
              <div class="qe-check checked" onclick="toggleCheck(this)"><i class="bi bi-check" style="font-size:.75rem"></i></div>
              <span class="qe-name">Amine Saidi</span>
              <span class="qe-tag status-present">Présent</span>
            </div>
            <div class="qe-row">
              <div class="qe-check" onclick="toggleCheck(this)"></div>
              <span class="qe-name">Laila Moussaoui</span>
              <span class="qe-tag status-absent">Absent</span>
            </div>
            <div class="qe-row">
              <div class="qe-check checked" onclick="toggleCheck(this)"><i class="bi bi-check" style="font-size:.75rem"></i></div>
              <span class="qe-name">Youssef Benhaddou</span>
              <span class="qe-tag status-present">Présent</span>
            </div>
            <div class="qe-row">
              <div class="qe-check checked" onclick="toggleCheck(this)"><i class="bi bi-check" style="font-size:.75rem"></i></div>
              <span class="qe-name">Hana Zaoui</span>
              <span class="qe-tag status-present">Présent</span>
            </div>
          </div>
          <div class="submit-bar">
            <span><i class="bi bi-send-check" style="margin-right:6px"></i>Soumettre séance</span>
            <small>4 étudiants · 3 présents</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-section" id="how">
  <div class="container">
    <div class="section-head">
      <span class="section-label">Flux de travail</span>
      <h2 class="section-title">Simple par <em>conception.</em></h2>
    </div>
    <div class="steps-grid">
      <div class="step-item">
        <div class="step-num active"><i class="bi bi-calendar2-plus"></i></div>
        <div class="step-title">Séance créée</div>
        <p class="step-desc">L'admin configure le module, le groupe et le créneau dans le tableau de bord.</p>
      </div>
      <div class="step-item">
        <div class="step-num"><i class="bi bi-phone"></i></div>
        <div class="step-title">Le formateur note</div>
        <p class="step-desc">Le formateur ouvre l'app, tape le statut de chaque étudiant et soumet en quelques secondes.</p>
      </div>
      <div class="step-item">
        <div class="step-num"><i class="bi bi-bell"></i></div>
        <div class="step-title">Étudiant notifié</div>
        <p class="step-desc">Les étudiants absents reçoivent une notification immédiate et peuvent déposer un justificatif.</p>
      </div>
      <div class="step-item">
        <div class="step-num"><i class="bi bi-check2-circle"></i></div>
        <div class="step-title">Admin valide</div>
        <p class="step-desc">L'admin approuve les justificatifs et génère les rapports de présence en un clic.</p>
      </div>
    </div>
  </div>
</section>

<!-- ROLES -->
<section class="roles-section" id="roles">
  <div class="container">
    <div class="section-head">
      <span class="section-label">Trois rôles, un système</span>
      <h2 class="section-title">Chaque acteur a<br/><em>son propre espace.</em></h2>
    </div>
    <div class="roles-grid">
      <div class="role-card role-teacher">
        <div class="role-icon role-icon-blue"><i class="bi bi-person-badge"></i></div>
        <div class="role-title">Formateur</div>
        <div class="role-sub">Portail instructeur</div>
        <ul class="role-list">
          <li>Voir les séances et groupes assignés</li>
          <li>Marquer les présences en 30 secondes</li>
          <li>Consulter l'historique des séances</li>
          <li>Signaler les absences exceptionnelles</li>
        </ul>
      </div>
      <div class="role-card role-student">
        <div class="role-icon role-icon-green"><i class="bi bi-mortarboard"></i></div>
        <div class="role-title">Étudiant</div>
        <div class="role-sub">Portail apprenant</div>
        <ul class="role-list">
          <li>Voir son historique de présence</li>
          <li>Déposer des justificatifs médicaux</li>
          <li>Suivre le statut d'approbation</li>
          <li>Voir les alertes d'absence en temps réel</li>
        </ul>
      </div>
      <div class="role-card role-admin">
        <div class="role-icon role-icon-amber"><i class="bi bi-sliders"></i></div>
        <div class="role-title">Administrateur</div>
        <div class="role-sub">Panneau de contrôle</div>
        <ul class="role-list">
          <li>Gérer groupes, modules et séances</li>
          <li>Approuver ou refuser les justificatifs</li>
          <li>Exporter les rapports de présence</li>
          <li>Surveiller les statistiques globales</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="container">
    <div class="cta-card">
      <span class="cta-label">Prêt à commencer ?</span>
      <h2>Plus de papier.<br/><em>Plus de suppositions.</em></h2>
      <p>SoliCode AMS offre à chaque intervenant une vue claire et en temps réel des présences — de la première séance au rapport final.</p>
      <div class="cta-actions">
        <a href="/login" class="btn-cta-primary"><i class="bi bi-box-arrow-in-right"></i> Accéder au portail</a>
        <a href="#features" class="btn-cta-ghost"><i class="bi bi-info-circle"></i> En savoir plus</a>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="footer-inner">
      <a href="#" class="logo">
        <div class="logo-icon" style="width:30px;height:30px;font-size:14px"><i class="bi bi-fingerprint"></i></div>
        <span class="logo-text" style="font-size:.9rem">solicode<span>AMS</span></span>
      </a>
      <span class="footer-copy">© 2026 Projet SoliCode. Sprint 1.</span>
      <div class="footer-links">
        <a href="#" aria-label="GitHub"><i class="bi bi-github"></i></a>
        <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
      </div>
    </div>
  </div>
</footer>

<script>
const hamburger=document.getElementById('hamburger');
const mobileNav=document.getElementById('mobileNav');
hamburger.addEventListener('click',()=>{
  mobileNav.classList.toggle('open');
  hamburger.querySelector('i').className=mobileNav.classList.contains('open')?'bi bi-x':'bi bi-list';
});
mobileNav.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>{
  mobileNav.classList.remove('open');
  hamburger.querySelector('i').className='bi bi-list';
}));

function toggleCheck(el){
  const row=el.closest('.qe-row');
  const tag=row.querySelector('.qe-tag');
  const isChecked=el.classList.contains('checked');
  el.classList.toggle('checked');
  el.innerHTML=isChecked?'':'<i class="bi bi-check" style="font-size:.75rem"></i>';
  if(isChecked){tag.textContent='Absent';tag.className='qe-tag status-absent';}
  else{tag.textContent='Présent';tag.className='qe-tag status-present';}
  updateCount();
}
function updateCount(){
  const total=document.querySelectorAll('.qe-row').length;
  const present=document.querySelectorAll('.qe-check.checked').length;
  const bar=document.querySelector('.submit-bar small');
  if(bar)bar.textContent=`${total} étudiants · ${present} présents`;
}

const previewContents={
  flash:{title:'Présences rapides · Séance 09:00',body:document.getElementById('previewBody').innerHTML},
  sessions:{title:'Séances module · Grp 3',body:`
    <p class="preview-title">Séances configurées</p>
    <div style="display:flex;flex-direction:column;gap:.5rem">
      ${[['Développement Web','09:00 – 11:00','Lun, Mer'],['Conception BD','11:00 – 14:00','Mar, Jeu'],['Fondamentaux UI/UX','14:00 – 16:00','Ven']].map(([m,t,d])=>`
      <div style="display:flex;align-items:center;gap:.75rem;padding:.7rem .9rem;border:1px solid var(--border);border-radius:var(--radius-md)">
        <div style="width:8px;height:8px;border-radius:50%;background:var(--accent);flex-shrink:0"></div>
        <div style="flex:1"><div style="font-size:.82rem;font-weight:500;color:var(--ink)">${m}</div><div style="font-size:.7rem;color:var(--ink-mute)">${d}</div></div>
        <span style="font-size:.7rem;font-weight:600;color:var(--accent);background:var(--accent-light);padding:.2rem .5rem;border-radius:999px">${t}</span>
      </div>`).join('')}
    </div>`
  },
  justify:{title:'File des justifications',body:`
    <p class="preview-title">En attente de validation</p>
    <div style="display:flex;flex-direction:column;gap:.5rem">
      ${[['Amine Saidi','Note médicale','En attente'],['Laila Moussaoui','Événement familial','Approuvé'],['Youssef B.','Doc hôpital','Refusé']].map(([n,t,s])=>`
      <div style="display:flex;align-items:center;gap:.75rem;padding:.7rem .9rem;border:1px solid var(--border);border-radius:var(--radius-md)">
        <i class="bi bi-file-earmark-text" style="color:var(--ink-mute);font-size:1rem"></i>
        <div style="flex:1"><div style="font-size:.82rem;font-weight:500;color:var(--ink)">${n}</div><div style="font-size:.7rem;color:var(--ink-mute)">${t}</div></div>
        <span style="font-size:.68rem;font-weight:600;padding:.2rem .55rem;border-radius:999px;${s==='En attente'?'background:var(--amber-light);color:var(--amber)':s==='Approuvé'?'background:var(--green-light);color:var(--green)':'background:#fef2f2;color:var(--red)'}">${s}</span>
      </div>`).join('')}
    </div>`
  }
};

function setActive(card,key){
  document.querySelectorAll('.feature-card').forEach(c=>c.classList.remove('active'));
  card.classList.add('active');
  const pb=document.getElementById('previewBody');
  const c=previewContents[key];
  pb.innerHTML=c.body;
}
</script>
</body>
</html>