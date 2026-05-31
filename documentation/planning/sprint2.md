# Sprint 2 — Attendance & Justification Lifecycle

> **Statut :** ✅ Terminé  
> **Période :** Mai 2026

---

## Objectifs

Consolider le cycle de vie complet **Absence → Justification → Résolution** avec traçabilité complète entre enregistrements de présence et justificatifs approuvés.

---

## Base de Données

### `attendance_records` — Changements

| Colonne | Type | Description |
|---|---|---|
| `status` | `enum` | `present`, `late`, `absent_unexcused`, `absent_excused` |
| `justification_id` | `FK nullable` | Lien vers la justification approuvée |

### `justifications` — Ajouts

| Colonne | Type | Description |
|---|---|---|
| `session_id` | `FK` | Identifie exactement la séance concernée |
| `document_name` | `string nullable` | Nom du fichier justificatif |
| `status` | `enum` | `pending`, `approved`, `rejected` |
| `reviewed_at` | `timestamp nullable` | Date de décision admin |
| `reviewed_by` | `FK nullable` | Admin ayant statué |

---

## Règle Métier — Matching

```
Une justification appartient à exactement une séance : (student_id + session_id)
```

**Délai de soumission :** Avant la séance ou dans les **48h** suivant `session_end_time`.

---

## Statuts de Référence

### `attendance_records.status`

| Valeur | Signification |
|---|---|
| `present` | Étudiant présent |
| `late` | Étudiant en retard |
| `absent_unexcused` | Absent sans justificatif approuvé |
| `absent_excused` | Absent avec justificatif approuvé |

### `justifications.status`

| Valeur | Signification |
|---|---|
| `pending` | En attente de décision |
| `approved` | Acceptée — déclenche la mise à jour de l'`AttendanceRecord` |
| `rejected` | Refusée — repasse le statut en `absent_unexcused` |

---

## Services

### `AttendanceService::markAttendance(int $studentId, int $sessionId, string $status, string $date)`

- Résout automatiquement `absent` → `absent_excused` si une justification `approved` existe pour `(student_id, session_id)`.
- Sinon enregistre `absent_unexcused`.
- Appelé depuis `AttendanceController::store()`.

### `JustificationService::reviewJustification(Justification $justification, string $decision)`

- Si `approved` : met à jour l'`AttendanceRecord` en `absent_excused` + renseigne `justification_id`.
- Si `rejected` : repasse en `absent_unexcused` + `justification_id = null`.

---

## Contrôleurs

### `Admin\AttendanceController`

| Méthode | Changement |
|---|---|
| `index()` | Redirect 302 si `?date=undefined`, défaut à aujourd'hui |
| `store()` | Délègue à `AttendanceService`, redirect avec `?date=<date_séance>` |

### `Student\JustificationController`

| Méthode | Changement |
|---|---|
| `store()` | Validation délai 48h, `session_id` obligatoire |

---

## Corrections de Bugs

| Bug | Cause | Fix |
|---|---|---|
| `?date=undefined` dans l'URL | `this.value` dans Alpine.js `@change` = contexte composant | Remplacé par `$event.target.value` |
| Date ancienne ignorée | Même cause | Même fix |
| Redirect Save → revient à aujourd'hui | `store()` sans paramètre `date` | `['date' => $sessionDate]` dans le redirect |
| `create.blade.php` cassé | Fichier tronqué (début manquant) | Recréé complet |

---

## Fichiers Impactés

```
app/
├── Models/
│   ├── AttendanceRecord.php          ← relation justification (BelongsTo)
│   └── Justification.php             ← session_id, reviewer
├── Services/
│   ├── AttendanceService.php         ← markAttendance()
│   └── JustificationService.php     ← reviewJustification()
├── Http/Controllers/Admin/
│   ├── AttendanceController.php      ← store() + redirect fix
│   └── JustificationController.php  ← review logic
database/migrations/
├── *_create_attendance_records_table.php  ← enum + justification_id
└── *_create_justifications_table.php     ← session_id, reviewed_at/by
resources/views/admin/
├── attendance/index.blade.php        ← $event.target.value fix
└── sessions/create.blade.php        ← recréé complet
```

---

## Seed

- `CsvSeeder` synchronise automatiquement au `migrate:fresh`.
- Lie les justifications CSV à l'`AttendanceRecord` via `(student_id + session_id)`.
- Met à jour le statut en `absent_excused` si justification `approved`.
