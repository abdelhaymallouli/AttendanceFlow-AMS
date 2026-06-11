You are a UML and software engineering expert.

I will provide you with an existing attendance management system model (use case diagram and class diagram). Your task is to carefully analyze the current system and correct it if needed, not just add new features blindly.

🔷 Main Objective

You must:

Analyze the current use case diagram and class diagram
Identify inconsistencies, missing logic, or design problems
Fix and improve the system in a logical, coherent, and realistic way
Ensure the system reflects a correct real-world attendance management system
🔷 New Feature to Integrate

The system now includes QR code-based attendance recording:

Users (students) can saisir their attendance by scanning a QR code
The system must validate the QR code
Attendance is recorded automatically after successful scan
Optional (only if logically needed): teacher/admin may generate the QR code for a session
🔷 What you must do
1. Use Case Diagram
Analyze existing actors and use cases
Fix incorrect or unrealistic use cases
Add missing use cases related to QR attendance:
Scan QR code
Record attendance via QR
Generate QR code (only if logically justified)
Ensure correct relationships (include / extend)
Ensure actors are correctly defined (Student, Teacher, Admin if needed)
2. Class Diagram
Analyze the current structure carefully
Fix design issues (wrong responsibilities, missing classes, bad relationships)
Integrate QR-based attendance properly
Add or adjust classes such as:
Attendance / Presence
QRCode service or generator (if needed)
QR validation component
Ensure:
Proper encapsulation
Clean separation of responsibilities
Correct associations and multiplicities
🔷 Important Rules
Do NOT blindly add new elements — only add what is logically necessary
Remove or correct anything that is unrealistic or redundant
Prioritize clarity, consistency, and real-world behavior
Follow UML best practices
🔷 Output Required

Provide:

Corrected Use Case Diagram (PlantUML or structured description)  i have 3 sprints okay 
Corrected Class Diagram (PlantUML or structured description)
Brief explanation of what you changed and why (focused on logic fixes, not description)

path of cas utlisation : C:\Users\Abdelhay\Documents\GitHub\AttendanceFlow-AMS\Analyse\cas_utilisation
path diagram de class : C:\Users\Abdelhay\Documents\GitHub\AttendanceFlow-AMS\Analyse\diagramme_de_classe