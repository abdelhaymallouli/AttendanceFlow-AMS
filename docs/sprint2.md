# Sprint 2 — Documentation Technique

> **Période :** Mai 2026  
> **Branche :** `feature/sprint2-attendance-justification`

---

## Objectifs du Sprint

Consolider le cycle de vie complet **Absence → Justification → Résolution** avec une traçabilité complète entre les enregistrements de présence et les justificatifs approuvés.

---

## Changements de Base de Données

### Migration : `attendance_records`

| Colonne | Type | Description |
|---|---|---|
| `status` | `enum` | `present`, `late`, `absent_unexcused`, `absent_excused` |
| `justification_id` | `FK nullable` | Lien vers la justification approuvée |

### Migration : `justifications`

| Colonne | Type | Description |
|---|---|---|
| `session_id` | `FK` | Identifie exactement la séance concernée |
| `document_name` | `string nullable` | Nom du fichier justificatif |
| `status` | `enum` | `pending`, `approved`, `rejected` |
| `reviewed_at` | `timestamp nullable` | Date de décision |
| `reviewed_by` | `FK nullable` | Admin ayant statué |

---

## Règle Métier — Matching Justification

```
Une justification appartient à exactement une séance : (student_id + session_id)
```

**Délai de soumission :** Avant la séance ou dans les **48h** suivant `session_end_time`.

---

## Services Implémentés

### `AttendanceService::markAttendance()`

- Appelé lors de la sauvegarde de présence par l'admin.
- Résout automatiquement `absent` → `absent_excused` si une justification `approved` existe pour `(student_id, session_id)`.
- Sinon → `absent_unexcused`.

### `JustificationService::reviewJustification()`

- Appelé lors de l'approbation/rejet d'une justification par l'admin.
- Si `approved` : met à jour l'`AttendanceRecord` correspondant en `absent_excused` et renseigne `justification_id`.
- Si `rejected` : repasse le statut en `absent_unexcused`.

---

## Contrôleurs Modifiés

### `Admin\AttendanceController`

| Méthode | Changement |
|---|---|
| `index()` | Redirect 302 si `?date=undefined`, défaut à aujourd'hui |
| `store()` | Utilise `AttendanceService::markAttendance()`, redirige avec `?date=<session_date>` |

### `Student\JustificationController`

| Méthode | Changement |
|---|---|
| `store()` | Validation délai 48h, sélection `session_id` obligatoire |

---

## Corrections de Bugs

| Bug | Cause | Fix |
|---|---|---|
| `?date=undefined` dans l'URL | `this.value` dans `@change` Alpine.js = contexte composant | Remplacé par `$event.target.value` |
| Date ancienne ignorée par le filtre | Même cause côté JS | Même fix |
| Redirect après Save → revient à aujourd'hui | `store()` sans paramètre `date` | Ajout `['date' => $sessionDate]` dans le redirect |
| `create.blade.php` tronqué | Fichier corrompu (début manquant) | Recréé complet depuis `edit.blade.php` |

---

## Modèles mis à jour

### `AttendanceRecord`

```php
public function justification(): BelongsTo
{
    return $this->belongsTo(Justification::class);
}
```

### `Justification`

```php
public function session(): BelongsTo { ... }
public function reviewer(): BelongsTo { ... }  // Admin user
```

---

## Seed / CsvSeeder

- Synchronisation automatique au `migrate:fresh`.
- Les justifications du CSV sont liées à l'`AttendanceRecord` correspondant via `(student_id + session_id)`.
- Le statut de l'`AttendanceRecord` est mis à jour en `absent_excused` si la justification est `approved`.

---

## Statuts de Référence

### Présence (`attendance_records.status`)

| Valeur | Signification |
|---|---|
| `present` | Étudiant présent |
| `late` | Étudiant en retard |
| `absent_unexcused` | Absent sans justificatif approuvé |
| `absent_excused` | Absent avec justificatif approuvé |

### Justification (`justifications.status`)

| Valeur | Signification |
|---|---|
| `pending` | En attente de décision |
| `approved` | Acceptée par l'admin |
| `rejected` | Refusée par l'admin |

---

## Fichiers Impactés

```
app/
├── Models/
│   ├── AttendanceRecord.php          ← relation justification
│   └── Justification.php             ← session_id, reviewer
├── Services/
│   ├── AttendanceService.php         ← markAttendance()
│   └── JustificationService.php     ← reviewJustification()
├── Http/Controllers/Admin/
│   ├── AttendanceController.php      ← store() fix, date redirect
│   └── JustificationController.php  ← review logic
database/
└── migrations/
    ├── *_create_attendance_records_table.php
    └── *_create_justifications_table.php
resources/views/admin/
├── attendance/index.blade.php        ← $event.target.value fix
└── sessions/create.blade.php        ← fichier recréé complet
```
