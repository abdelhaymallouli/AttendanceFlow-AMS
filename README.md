
# 📋 final Project : Attendance Management System (AMS)

An empathetic solution to the "Paper-to-Laptop" friction in school administration. Built with **Laravel** and **Tailwind CSS**.

---

## 🚀 The Vision

Most school systems fail because they treat administrators like "human bridges" between paper and digital databases. **AMS** is designed to eliminate the manual data-entry lag by creating a real-time, digital-first workflow for teachers and administrators.

## 🔍 The Problem: The "Double-Work" Trap

Our research into the **Empathy Phase** revealed that Attendance Administrators spend up to **70% of their day** transcribing physical paper slips into digital spreadsheets.

* **The Data Lag:** A 4–6 hour gap exists between a student being absent and the system being updated.
* **The Risk:** High potential for human error and lost documentation.
* **The Friction:** Administrators are stuck in a repetitive cycle of "Looking at paper → Looking at screen."

---

## 👤 User Personas & Empathy

We designed this system with two primary users in mind:

### **Sarah, the Attendance Administrator**

* **The Goal:** Zero manual data entry and 100% accuracy.
* **The Pain:** Carrying stacks of paper and cross-referencing lists manually.
* **The Solution:** A validation-only dashboard where she simply "approves" incoming teacher data.

### **The Student**

* **The Goal:** Transparency.
* **The Pain:** Having to visit the office physically to check if a medical note was processed.
* **The Solution:** A personal portal to view attendance status in real-time.

---

## 🛠️ The Tech Stack

To ensure a fast, responsive, and secure experience, we are using:

* **Backend:** [Laravel](https://laravel.com/) (Robust API & Database Management)
* **Frontend:** [Tailwind CSS](https://tailwindcss.com/) (For a clean, "scannable" UI)
* **Database:** MySQL / PostgreSQL
* **Key Features:** * Mobile-ready attendance marking for Teachers.
* Digital "Justification" uploads (Replacing paper notes).
* Real-time status alerts.



---

## 🛤️ The Journey: From Paper to Digital

We are shrinking the attendance workflow to make it as fluid as modern enterprise tools:

**Old Workflow:** `Administration Attendance collect`  `Admin Reads`  `Admin Types`  `Saved`

**AMS Workflow:** `Teacher Inputs or Administration Attendance (Mobile/Web)`  `Instant Sync`  `Admin Validates`

---

## 🏗️ Project Structure & Roadmap

1. **Phase 1: Empathy (in Progress)** - Understanding the user struggle.
2. **Phase 2: Define** - Mapping User Stories and Functional Requirements.
3. **Phase 3: Development** - Sprint 1 focusing on the Teacher & Admin Dashboards.
4. **Phase 4: Testing** - Eliminating bugs in the "Justification Upload" flow.

---

## 🚦 Getting Started (Development)

*(Note: These are standard Laravel installation steps)*

```bash
# Clone the repository
git clone https://github.com/abdelhaymallouli/School-Absence-Management-System-AMS-.git

# Install dependencies
composer install
npm install

# Run the development server
php artisan serve
npm run dev
```

