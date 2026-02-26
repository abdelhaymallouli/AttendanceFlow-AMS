# 📋 School Absence Management System (AMS)

> **Design Thinking Phase 1: Empathize**

This document outlines the discovery and empathy phase of the project, focusing on the human experiences behind the data.

---

## 🔍 1. The Core Problem: The "Double-Work" Trap

Our research shows that the **Attendance Administrator** is currently trapped in a "Double-Entry" workflow.

1. **Morning:** Physical collection of paper slips and hand-written lists from teachers.
2. **Afternoon:** Manual data entry into a laptop (Excel/Legacy system).

**The Friction:** This creates a 4–6 hour "data lag" where the school is operating on old information, and the risk of human error (tying the wrong name to an absence) is extremely high.

---

## 👤 2. User Personas

### **The Attendance Administrator (Primary User)**

* **Name:** Attendance Administrator Madam Hannane 
* **Context:** Manages 120+ students.
* **Goal:** To have an empty "In-Box" and zero data-entry errors.
* **Pain Point:** Spent 70% of her day looking down at paper and then up at a screen. She feels like a "human bridge" between paper and digital.

### **The Frustrated Student**

* **Context:** Needs to know if their medical note was accepted.
* **Goal:** Transparency.
* **Pain Point:** Has to physically visit the office to ask about their record because they can't see it online.

---

## 🗺️ 3. Empathy Map: Attendance Administrator

We mapped the experience of the Administrator to understand the emotional and physical toll of the current system.

| Category | Observations |
| --- | --- |
| **SAYS** | "I'll update the system this afternoon," "Is this a 'B' or an '8'?", "I lost that slip." |
| **THINKS** | *I'm doing work that a computer should do.* *I hope I didn't miss anyone's justified absence.* |
| **DOES** | Carries stacks of paper, cross-references lists, types manually for hours, answers phone calls from parents. |
| **FEELS** | **Anxious** about data accuracy, **exhausted** by repetitive tasks, and **disconnected** from real-time school activity. |

---

## 🛤️ 4. The Journey: From Paper to Digital

To design a system as fluid as **absence.io**, we must eliminate the "Transition Gap."

* **Current Journey (The Pain):** `Administration Attendance collect`  `Admin Reads`  `Admin Types`  `Saved`
* **Proposed Journey (The Empathy Solution):** `Teacher Inputs or Administration Attendance (Mobile/Web)`  `Instant Sync`  `Admin Validates`.

---

## 💡 5. Key Empathy Insights

Based on our interviews and the "Paper-to-Laptop" problem, our design must focus on three things:

1. **Reduce Cognition:** The UI should use colors and icons so the Admin doesn't have to "read" every name; they should be able to "scan" the status.
2. **Eliminate the Middle-Man:** Teachers must enter data directly. The Admin’s role changes from **Data Entry** to **Data Verification**.
3. **Trust through Evidence:** Since paper is being replaced, the "Upload Justification" feature is critical to ensure the Admin still feels the data is "official" (digital version of the paper note).

---

## NEXT Chapter : Define Problem