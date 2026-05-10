# Git Workflow & Version Control: AttendanceFlow-AMS

This document outlines the branching strategy and significant development milestones of the AttendanceFlow-AMS project.

## 🌿 Branching Strategy (GitFlow)

The project follows a modular branching strategy to ensure that feature development, mobile integration, and core infrastructure remain isolated until validated.

| Branch Name | Role | Status |
| :--- | :--- | :--- |
| **`main`** | Production-ready code. | Stable |
| **`develop`** | Integration branch for all features. | Active |
| **`feat/Admin-Side`** | Core back-office development (current). | Active |
| **`feat/Public-Side`** | Login, Auth, and Guest pages. | Completed/Remote |
| **`feat/mobile-side`** | NativePHP and API development for Mobile. | Active/Remote |
| **`feature/setup-models`**| Database schema and Eloquent relationships. | Completed |
| **`feature/maquete`** | Original HTML/CSS UI prototypes. | Completed |

---

## 🕒 Significant Milestones (Pushes)

The project evolution can be traced through these key commit groups:

### 1. Foundation Phase
- **Initial Setup**: Initialization of the Laravel 12 project and README.
- **Model Architecture**: Implementation of `User`, `Session`, `Group`, and `AttendanceRecord` models.

### 2. Design Thinking & Analysis
- **Empathy & Ideation**: Integration of Design Thinking artifacts into the repository.
- **UI Prototyping**: Creation of the "Maquete" (mockup) phase using Tailwind CSS.

### 3. Core Service Implementation
- **SOLID Refactoring**: Extraction of business logic into `ReportingService`, `AttendanceService`, and `SchedulingService`.
- **TDD Integration**: Implementation of 30+ feature and unit tests to ensure stability.

### 4. Admin & Auth Finalization (Current)
- Implementation of role-based dashboards.
- Refinement of the justification workflow.
- Standardization of HTTP controllers.

---

## 🚀 Contribution Workflow

1. **Pull** the latest `develop` branch.
2. **Create** a feature branch: `git checkout -b feat/your-feature`.
3. **Commit** using descriptive prefixes (e.g., `feat:`, `docs:`, `fix:`, `chore:`).
4. **Push** and create a **Pull Request** to `develop`.
5. **Verify** that all 31+ tests pass before merging.
