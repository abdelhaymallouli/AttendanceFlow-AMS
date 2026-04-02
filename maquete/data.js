// =============================================================================
// AttendanceFlow-AMS - Centralized Data Store
// Aligned with Class Diagram: IAM_Auth_Service, Academic_Service, Attendance_Service
// =============================================================================

const AppData = {

  // ===========================================================================
  // IAM Auth Service
  // ===========================================================================

  roles: [
    { id: 1, name: 'admin', guard_name: 'web' },
    { id: 2, name: 'teacher', guard_name: 'web' },
    { id: 3, name: 'student', guard_name: 'web' }
  ],

  permissions: [
    { id: 1, name: 'manage-users', guard_name: 'web' },
    { id: 2, name: 'manage-students', guard_name: 'web' },
    { id: 3, name: 'manage-attendance', guard_name: 'web' },
    { id: 4, name: 'view-reports', guard_name: 'web' },
    { id: 5, name: 'submit-justifications', guard_name: 'web' },
    { id: 6, name: 'approve-justifications', guard_name: 'web' },
    { id: 7, name: 'manage-sessions', guard_name: 'web' },
    { id: 8, name: 'view-calendar', guard_name: 'web' }
  ],

  rolePermissions: [
    { role_id: 1, permission_id: 1 },
    { role_id: 1, permission_id: 2 },
    { role_id: 1, permission_id: 3 },
    { role_id: 1, permission_id: 4 },
    { role_id: 1, permission_id: 6 },
    { role_id: 1, permission_id: 7 },
    { role_id: 1, permission_id: 8 },
    { role_id: 2, permission_id: 3 },
    { role_id: 2, permission_id: 4 },
    { role_id: 2, permission_id: 6 },
    { role_id: 2, permission_id: 8 },
    { role_id: 3, permission_id: 5 }
  ],

  users: [
    // Admin
    { id: 1, name: 'Admin User', email: 'admin@school.edu', password: 'admin123', role_id: 1 },
    // Teachers
    { id: 2, name: 'Imane Bouziane', email: 'teacher@school.edu', password: 'teacher123', role_id: 2 },
    { id: 10, name: 'Ms. Anderson', email: 'anderson@school.edu', password: 'teacher123', role_id: 2 },
    { id: 11, name: 'Mr. Thompson', email: 'thompson@school.edu', password: 'teacher123', role_id: 2 },
    { id: 12, name: 'Ms. Garcia', email: 'garcia@school.edu', password: 'teacher123', role_id: 2 },
    { id: 13, name: 'Mr. Wilson', email: 'wilson@school.edu', password: 'teacher123', role_id: 2 },
    { id: 14, name: 'Ms. Martinez', email: 'martinez@school.edu', password: 'teacher123', role_id: 2 },
    // Students
    { id: 3, name: 'Emma Johnson', email: 'emma.j@school.edu', password: 'student123', role_id: 3 },
    { id: 4, name: 'Liam Smith', email: 'liam.s@school.edu', password: 'student123', role_id: 3 },
    { id: 5, name: 'Olivia Brown', email: 'olivia.b@school.edu', password: 'student123', role_id: 3 },
    { id: 6, name: 'Noah Davis', email: 'noah.d@school.edu', password: 'student123', role_id: 3 },
    { id: 7, name: 'Ava Wilson', email: 'ava.w@school.edu', password: 'student123', role_id: 3 },
    { id: 8, name: 'Elijah Moore', email: 'elijah.m@school.edu', password: 'student123', role_id: 3 },
    { id: 9, name: 'Sophia Taylor', email: 'sophia.t@school.edu', password: 'student123', role_id: 3 },
    { id: 15, name: 'James Anderson', email: 'james.a@school.edu', password: 'student123', role_id: 3 },
    { id: 16, name: 'Isabella Thomas', email: 'isabella.t@school.edu', password: 'student123', role_id: 3 },
    { id: 17, name: 'Benjamin Jackson', email: 'benjamin.j@school.edu', password: 'student123', role_id: 3 },
    { id: 18, name: 'Mia White', email: 'mia.w@school.edu', password: 'student123', role_id: 3 },
    { id: 19, name: 'Lucas Harris', email: 'lucas.h@school.edu', password: 'student123', role_id: 3 },
    { id: 20, name: 'Charlotte Martin', email: 'charlotte.m@school.edu', password: 'student123', role_id: 3 },
    { id: 21, name: 'Henry Thompson', email: 'henry.t@school.edu', password: 'student123', role_id: 3 },
    { id: 22, name: 'Amelia Garcia', email: 'amelia.g@school.edu', password: 'student123', role_id: 3 },
    { id: 23, name: 'Alexander Martinez', email: 'alex.m@school.edu', password: 'student123', role_id: 3 },
    { id: 24, name: 'Harper Robinson', email: 'harper.r@school.edu', password: 'student123', role_id: 3 },
    { id: 25, name: 'Sebastian Clark', email: 'sebastian.c@school.edu', password: 'student123', role_id: 3 },
    { id: 26, name: 'Evelyn Rodriguez', email: 'evelyn.r@school.edu', password: 'student123', role_id: 3 },
    { id: 27, name: 'Michael Lewis', email: 'michael.l@school.edu', password: 'student123', role_id: 3 },
    { id: 28, name: 'Sarah Johnson', email: 'sarah.j@school.edu', password: 'student123', role_id: 3 },
    { id: 29, name: 'James Rodriguez', email: 'james.r@school.edu', password: 'student123', role_id: 3 }
  ],

  // ===========================================================================
  // Academic Service
  // ===========================================================================

  filieres: [
    { id: 1, name: 'Développement Digital', code: 'DEV' },
    { id: 2, name: 'Design Graphique', code: 'DES' },
    { id: 3, name: 'Infrastructure Digitale', code: 'INF' }
  ],

  groups: [
    { id: 1, name: '10A', filiere_id: 1 },
    { id: 2, name: '10B', filiere_id: 1 },
    { id: 3, name: '11A', filiere_id: 1 },
    { id: 4, name: '11B', filiere_id: 2 },
    { id: 5, name: '12A', filiere_id: 2 },
    { id: 6, name: '12B', filiere_id: 3 }
  ],

  modules: [
    { id: 1, name: 'Web Development', code: 'WEB101', coefficient: 3.0 },
    { id: 2, name: 'Mobile Development', code: 'MOB101', coefficient: 3.0 },
    { id: 3, name: 'Database Systems', code: 'DB101', coefficient: 2.5 },
    { id: 4, name: 'UI/UX Design', code: 'UXD101', coefficient: 2.0 },
    { id: 5, name: 'Network Administration', code: 'NET101', coefficient: 2.5 },
    { id: 6, name: 'Software Engineering', code: 'SE101', coefficient: 3.0 }
  ],

  teacherProfiles: [
    { id: 1, user_id: 2, specialty: 'Web Development' },
    { id: 2, user_id: 10, specialty: 'Mathematics' },
    { id: 3, user_id: 11, specialty: 'Database Systems' },
    { id: 4, user_id: 12, specialty: 'Mobile Development' },
    { id: 5, user_id: 13, specialty: 'UI/UX Design' },
    { id: 6, user_id: 14, specialty: 'Network Administration' }
  ],

  teacherModules: [
    { teacher_id: 1, module_id: 1 },
    { teacher_id: 1, module_id: 2 },
    { teacher_id: 2, module_id: 6 },
    { teacher_id: 3, module_id: 3 },
    { teacher_id: 4, module_id: 2 },
    { teacher_id: 5, module_id: 4 },
    { teacher_id: 6, module_id: 5 }
  ],

  teacherGroups: [
    { teacher_id: 1, group_id: 1 },
    { teacher_id: 1, group_id: 2 },
    { teacher_id: 2, group_id: 1 },
    { teacher_id: 3, group_id: 3 },
    { teacher_id: 3, group_id: 4 },
    { teacher_id: 4, group_id: 2 },
    { teacher_id: 5, group_id: 3 },
    { teacher_id: 5, group_id: 4 },
    { teacher_id: 6, group_id: 5 },
    { teacher_id: 6, group_id: 6 }
  ],

  studentProfiles: [
    { id: 1, user_id: 3, matricule: 'STU001', group_id: 1 },
    { id: 2, user_id: 4, matricule: 'STU002', group_id: 1 },
    { id: 3, user_id: 5, matricule: 'STU003', group_id: 1 },
    { id: 4, user_id: 6, matricule: 'STU004', group_id: 1 },
    { id: 5, user_id: 7, matricule: 'STU005', group_id: 1 },
    { id: 6, user_id: 8, matricule: 'STU006', group_id: 2 },
    { id: 7, user_id: 9, matricule: 'STU007', group_id: 2 },
    { id: 8, user_id: 15, matricule: 'STU008', group_id: 2 },
    { id: 9, user_id: 16, matricule: 'STU009', group_id: 2 },
    { id: 10, user_id: 17, matricule: 'STU010', group_id: 2 },
    { id: 11, user_id: 18, matricule: 'STU011', group_id: 3 },
    { id: 12, user_id: 19, matricule: 'STU012', group_id: 3 },
    { id: 13, user_id: 20, matricule: 'STU013', group_id: 3 },
    { id: 14, user_id: 21, matricule: 'STU014', group_id: 3 },
    { id: 15, user_id: 22, matricule: 'STU015', group_id: 3 },
    { id: 16, user_id: 23, matricule: 'STU016', group_id: 4 },
    { id: 17, user_id: 24, matricule: 'STU017', group_id: 4 },
    { id: 18, user_id: 25, matricule: 'STU018', group_id: 4 },
    { id: 19, user_id: 26, matricule: 'STU019', group_id: 4 },
    { id: 20, user_id: 27, matricule: 'STU020', group_id: 4 },
    { id: 21, user_id: 28, matricule: 'STU021', group_id: 5 },
    { id: 22, user_id: 29, matricule: 'STU022', group_id: 6 }
  ],

  sessions: [
    // Group 1 (10A) - Teacher 1 (Imane)
    { id: 1, start_time: '09:00', end_time: '11:00', duration_hours: 2.0, type: 'lecture', group_id: 1, teacher_id: 1, module_id: 1 },
    { id: 2, start_time: '11:00', end_time: '14:00', duration_hours: 3.0, type: 'td', group_id: 1, teacher_id: 1, module_id: 2 },
    { id: 3, start_time: '14:00', end_time: '17:00', duration_hours: 3.0, type: 'tp', group_id: 1, teacher_id: 1, module_id: 1 },
    // Group 2 (10B) - Teacher 1 (Imane)
    { id: 4, start_time: '09:00', end_time: '11:00', duration_hours: 2.0, type: 'lecture', group_id: 2, teacher_id: 1, module_id: 2 },
    { id: 5, start_time: '11:00', end_time: '14:00', duration_hours: 3.0, type: 'td', group_id: 2, teacher_id: 1, module_id: 1 },
    { id: 6, start_time: '14:00', end_time: '17:00', duration_hours: 3.0, type: 'tp', group_id: 2, teacher_id: 1, module_id: 2 },
    // Group 3 (11A) - Teacher 5 (Mr. Wilson)
    { id: 7, start_time: '09:00', end_time: '11:00', duration_hours: 2.0, type: 'lecture', group_id: 3, teacher_id: 5, module_id: 4 },
    { id: 8, start_time: '11:00', end_time: '14:00', duration_hours: 3.0, type: 'td', group_id: 3, teacher_id: 5, module_id: 4 },
    // Group 4 (11B) - Teacher 3 (Mr. Thompson)
    { id: 9, start_time: '09:00', end_time: '11:00', duration_hours: 2.0, type: 'lecture', group_id: 4, teacher_id: 3, module_id: 3 },
    { id: 10, start_time: '11:00', end_time: '14:00', duration_hours: 3.0, type: 'td', group_id: 4, teacher_id: 3, module_id: 3 },
    // Group 5 (12A) - Teacher 6 (Ms. Martinez)
    { id: 11, start_time: '09:00', end_time: '11:00', duration_hours: 2.0, type: 'lecture', group_id: 5, teacher_id: 6, module_id: 5 },
    // Group 6 (12B) - Teacher 6 (Ms. Martinez)
    { id: 12, start_time: '14:00', end_time: '17:00', duration_hours: 3.0, type: 'tp', group_id: 6, teacher_id: 6, module_id: 5 }
  ],

  // ===========================================================================
  // Attendance Service
  // ===========================================================================

  attendanceRecords: [
    // Today's attendance for Group 1
    { id: 1, student_id: 1, session_id: 1, status: 'present', date: '2025-12-20' },
    { id: 2, student_id: 2, session_id: 1, status: 'present', date: '2025-12-20' },
    { id: 3, student_id: 3, session_id: 1, status: 'present', date: '2025-12-20' },
    { id: 4, student_id: 4, session_id: 1, status: 'late', date: '2025-12-20' },
    { id: 5, student_id: 5, session_id: 1, status: 'present', date: '2025-12-20' },
    // Today's attendance for Group 2
    { id: 6, student_id: 6, session_id: 4, status: 'present', date: '2025-12-20' },
    { id: 7, student_id: 7, session_id: 4, status: 'absent', date: '2025-12-20' },
    { id: 8, student_id: 8, session_id: 4, status: 'present', date: '2025-12-20' },
    { id: 9, student_id: 9, session_id: 4, status: 'present', date: '2025-12-20' },
    { id: 10, student_id: 10, session_id: 4, status: 'present', date: '2025-12-20' },
    // Previous dates
    { id: 11, student_id: 1, session_id: 1, status: 'present', date: '2025-12-19' },
    { id: 12, student_id: 1, session_id: 2, status: 'present', date: '2025-12-19' },
    { id: 13, student_id: 1, session_id: 3, status: 'absent', date: '2025-12-19' },
    { id: 14, student_id: 2, session_id: 1, status: 'present', date: '2025-12-19' },
    { id: 15, student_id: 3, session_id: 1, status: 'late', date: '2025-12-19' },
    // More historical data
    { id: 16, student_id: 1, session_id: 1, status: 'present', date: '2025-12-18' },
    { id: 17, student_id: 2, session_id: 1, status: 'present', date: '2025-12-18' },
    { id: 18, student_id: 3, session_id: 1, status: 'absent', date: '2025-12-18' },
    { id: 19, student_id: 4, session_id: 1, status: 'present', date: '2025-12-18' },
    { id: 20, student_id: 5, session_id: 1, status: 'present', date: '2025-12-18' }
  ],

  justifications: [
    { id: 1, student_id: 1, reason: 'Doctor appointment - fever and flu symptoms', start_date: '2025-12-20', end_date: '2025-12-20', status: 'pending', submitted_at: '2025-12-21', document_name: 'medical_certificate.pdf', type: 'medical', session_id: 3 },
    { id: 2, student_id: 8, reason: 'Family emergency - had to travel', start_date: '2025-12-18', end_date: '2025-12-18', status: 'pending', submitted_at: '2025-12-19', document_name: 'parent_letter.jpg', type: 'family', session_id: 6 },
    { id: 3, student_id: 15, reason: 'Hospital visit for annual checkup', start_date: '2025-12-15', end_date: '2025-12-15', status: 'pending', submitted_at: '2025-12-16', document_name: 'hospital_note.pdf', type: 'medical', session_id: 8 },
    { id: 4, student_id: 6, reason: 'Scheduled dental surgery', start_date: '2025-12-10', end_date: '2025-12-10', status: 'approved', submitted_at: '2025-12-11', document_name: 'dentist_appointment.pdf', type: 'medical', session_id: 4 },
    { id: 5, student_id: 3, reason: 'Required court appearance', start_date: '2025-12-05', end_date: '2025-12-05', status: 'approved', submitted_at: '2025-12-06', document_name: 'court_summons.pdf', type: 'other', session_id: 3 },
    { id: 6, student_id: 12, reason: 'Family vacation - no valid reason', start_date: '2025-12-01', end_date: '2025-12-01', status: 'rejected', submitted_at: '2025-12-02', document_name: 'travel_document.pdf', type: 'other', session_id: 7 },
    { id: 7, student_id: 5, reason: 'Stomach flu', start_date: '2025-11-28', end_date: '2025-11-28', status: 'approved', submitted_at: '2025-11-29', document_name: 'medical_certificate.pdf', type: 'medical', session_id: 2 },
    { id: 8, student_id: 19, reason: 'Migraine - could not attend afternoon session', start_date: '2025-12-19', end_date: '2025-12-19', status: 'pending', submitted_at: '2025-12-20', document_name: 'medical_note.pdf', type: 'medical', session_id: 10 },
    { id: 9, student_id: 21, reason: 'Sports competition - school approved', start_date: '2025-12-17', end_date: '2025-12-17', status: 'approved', submitted_at: '2025-12-15', document_name: 'sports_approval.pdf', type: 'other', session_id: 11 },
    { id: 10, student_id: 22, reason: 'Family emergency', start_date: '2025-12-12', end_date: '2025-12-12', status: 'pending', submitted_at: '2025-12-13', document_name: 'parent_letter.pdf', type: 'family', session_id: 12 }
  ],

  // Events for calendar
  events: [
    { id: 1, date: '2025-12-25', name: 'Christmas Break', type: 'holiday' },
    { id: 2, date: '2025-12-26', name: 'Christmas Break', type: 'holiday' },
    { id: 3, date: '2025-12-15', name: 'Parent Meeting', type: 'event' }
  ]
};

// =============================================================================
// Helper Functions
// =============================================================================

const DataHelpers = {

  // --- IAM Helpers ---
  getUser(id) {
    return AppData.users.find(u => u.id === id);
  },

  getUserByEmail(email) {
    return AppData.users.find(u => u.email === email);
  },

  getUserRole(userId) {
    const user = this.getUser(userId);
    if (!user) return null;
    return AppData.roles.find(r => r.id === user.role_id);
  },

  getRoleByName(name) {
    return AppData.roles.find(r => r.name === name);
  },

  getRolePermissions(roleId) {
    const permIds = AppData.rolePermissions.filter(rp => rp.role_id === roleId).map(rp => rp.permission_id);
    return AppData.permissions.filter(p => permIds.includes(p.id));
  },

  hasPermission(userId, permissionName) {
    const role = this.getUserRole(userId);
    if (!role) return false;
    const perms = this.getRolePermissions(role.id);
    return perms.some(p => p.name === permissionName);
  },

  // --- Academic Helpers ---
  getStudentProfile(userId) {
    return AppData.studentProfiles.find(sp => sp.user_id === userId);
  },

  getStudentProfileById(id) {
    return AppData.studentProfiles.find(sp => sp.id === id);
  },

  getTeacherProfile(userId) {
    return AppData.teacherProfiles.find(tp => tp.user_id === userId);
  },

  getTeacherProfileById(id) {
    return AppData.teacherProfiles.find(tp => tp.id === id);
  },

  getGroup(id) {
    return AppData.groups.find(g => g.id === id);
  },

  getFiliere(id) {
    return AppData.filieres.find(f => f.id === id);
  },

  getModule(id) {
    return AppData.modules.find(m => m.id === id);
  },

  getSession(id) {
    return AppData.sessions.find(s => s.id === id);
  },

  getGroupsForFiliere(filiereId) {
    return AppData.groups.filter(g => g.filiere_id === filiereId);
  },

  getStudentsInGroup(groupId) {
    return AppData.studentProfiles.filter(sp => sp.group_id === groupId);
  },

  getStudentUser(studentProfileId) {
    const sp = this.getStudentProfileById(studentProfileId);
    if (!sp) return null;
    return this.getUser(sp.user_id);
  },

  getTeacherUser(teacherProfileId) {
    const tp = this.getTeacherProfileById(teacherProfileId);
    if (!tp) return null;
    return this.getUser(tp.user_id);
  },

  // --- Session Helpers ---
  getSessionsForTeacher(teacherProfileId) {
    return AppData.sessions.filter(s => s.teacher_id === teacherProfileId);
  },

  getSessionsForGroup(groupId) {
    return AppData.sessions.filter(s => s.group_id === groupId);
  },

  getSessionsForModule(moduleId) {
    return AppData.sessions.filter(s => s.module_id === moduleId);
  },

  getTeacherModules(teacherProfileId) {
    const modIds = AppData.teacherModules.filter(tm => tm.teacher_id === teacherProfileId).map(tm => tm.module_id);
    return AppData.modules.filter(m => modIds.includes(m.id));
  },

  getTeacherGroups(teacherProfileId) {
    const grpIds = AppData.teacherGroups.filter(tg => tg.teacher_id === teacherProfileId).map(tg => tg.group_id);
    return AppData.groups.filter(g => grpIds.includes(g.id));
  },

  getSessionName(session) {
    const hour = parseInt(session.start_time.split(':')[0]);
    if (hour < 11) return 'Morning Session';
    if (hour < 14) return 'Midday Session';
    return 'Afternoon Session';
  },

  getSessionTimeRange(session) {
    return `${session.start_time} - ${session.end_time}`;
  },

  getSessionTypeLabel(type) {
    const labels = { lecture: 'Lecture', td: 'TD (Travaux Dirigés)', tp: 'TP (Travaux Pratiques)' };
    return labels[type] || type;
  },

  // --- Attendance Helpers ---
  getAttendanceForStudent(studentProfileId) {
    return AppData.attendanceRecords.filter(ar => ar.student_id === studentProfileId);
  },

  getAttendanceForSession(sessionId) {
    return AppData.attendanceRecords.filter(ar => ar.session_id === sessionId);
  },

  getAttendanceForDate(date) {
    return AppData.attendanceRecords.filter(ar => ar.date === date);
  },

  getAttendanceForStudentOnDate(studentProfileId, date) {
    return AppData.attendanceRecords.filter(ar => ar.student_id === studentProfileId && ar.date === date);
  },

  calculateAttendanceRate(studentProfileId) {
    const records = this.getAttendanceForStudent(studentProfileId);
    if (records.length === 0) return 100;
    const present = records.filter(r => r.status === 'present' || r.status === 'late').length;
    return Math.round((present / records.length) * 100);
  },

  getStudentAbsenceCount(studentProfileId) {
    const records = this.getAttendanceForStudent(studentProfileId);
    return records.filter(r => r.status === 'absent').length;
  },

  getStudentJustifiedCount(studentProfileId) {
    return AppData.justifications.filter(j => j.student_id === studentProfileId && j.status === 'approved').length;
  },

  getStudentStatus(studentProfileId) {
    const rate = this.calculateAttendanceRate(studentProfileId);
    if (rate >= 90) return 'good';
    if (rate >= 80) return 'atrisk';
    return 'critical';
  },

  // --- Justification Helpers ---
  getJustificationsForStudent(studentProfileId) {
    return AppData.justifications.filter(j => j.student_id === studentProfileId);
  },

  getJustificationsForTeacher(teacherProfileId) {
    const groupIds = this.getTeacherGroups(teacherProfileId).map(g => g.id);
    const studentIds = AppData.studentProfiles.filter(sp => groupIds.includes(sp.group_id)).map(sp => sp.id);
    return AppData.justifications.filter(j => studentIds.includes(j.student_id));
  },

  getPendingJustifications() {
    return AppData.justifications.filter(j => j.status === 'pending');
  },

  // --- Aggregate Helpers ---
  getTotalStudents() {
    return AppData.studentProfiles.length;
  },

  getPresentToday() {
    const today = '2025-12-20';
    const todayRecords = this.getAttendanceForDate(today);
    return todayRecords.filter(r => r.status === 'present').length;
  },

  getAbsentToday() {
    const today = '2025-12-20';
    const todayRecords = this.getAttendanceForDate(today);
    return todayRecords.filter(r => r.status === 'absent').length;
  },

  getPendingJustificationCount() {
    return this.getPendingJustifications().length;
  },

  // --- Student Data for UI (combines user + profile + stats) ---
  getStudentDataForUI(studentProfileId) {
    const sp = this.getStudentProfileById(studentProfileId);
    if (!sp) return null;
    const user = this.getUser(sp.user_id);
    const group = this.getGroup(sp.group_id);
    const filiere = group ? this.getFiliere(group.filiere_id) : null;
    return {
      profileId: sp.id,
      userId: user.id,
      matricule: sp.matricule,
      name: user.name,
      email: user.email,
      groupId: sp.group_id,
      groupName: group ? group.name : '',
      filiereName: filiere ? filiere.name : '',
      attendance: this.calculateAttendanceRate(sp.id),
      absences: this.getStudentAbsenceCount(sp.id),
      justified: this.getStudentJustifiedCount(sp.id),
      status: this.getStudentStatus(sp.id)
    };
  },

  getAllStudentsForUI() {
    return AppData.studentProfiles.map(sp => this.getStudentDataForUI(sp.id)).filter(Boolean);
  },

  // --- Teacher Data for UI ---
  getTeacherDataForUI(teacherProfileId) {
    const tp = this.getTeacherProfileById(teacherProfileId);
    if (!tp) return null;
    const user = this.getUser(tp.user_id);
    const groups = this.getTeacherGroups(teacherProfileId);
    const modules = this.getTeacherModules(teacherProfileId);
    return {
      profileId: tp.id,
      userId: user.id,
      name: user.name,
      email: user.email,
      specialty: tp.specialty,
      groups: groups.map(g => g.name).join(', '),
      groupIds: groups.map(g => g.id),
      modules: modules.map(m => m.name).join(', '),
      moduleIds: modules.map(m => m.id)
    };
  },

  // --- Session Data for UI ---
  getSessionDataForUI(sessionId) {
    const session = this.getSession(sessionId);
    if (!session) return null;
    const group = this.getGroup(session.group_id);
    const teacher = this.getTeacherProfileById(session.teacher_id);
    const teacherUser = teacher ? this.getUser(teacher.user_id) : null;
    const module = this.getModule(session.module_id);
    const students = this.getStudentsInGroup(session.group_id);
    return {
      id: session.id,
      name: this.getSessionName(session),
      time: this.getSessionTimeRange(session),
      start_time: session.start_time,
      end_time: session.end_time,
      duration_hours: session.duration_hours,
      type: session.type,
      typeLabel: this.getSessionTypeLabel(session.type),
      groupId: session.group_id,
      groupName: group ? group.name : '',
      teacherId: session.teacher_id,
      teacherName: teacherUser ? teacherUser.name : '',
      teacherSpecialty: teacher ? teacher.specialty : '',
      moduleId: session.module_id,
      moduleName: module ? module.name : '',
      moduleCode: module ? module.code : '',
      studentsCount: students.length,
      status: this.getSessionStatus(session)
    };
  },

  getSessionStatus(session) {
    const now = new Date();
    const currentHour = now.getHours();
    const currentMinute = now.getMinutes();
    const currentTime = currentHour * 60 + currentMinute;

    const [startH, startM] = session.start_time.split(':').map(Number);
    const [endH, endM] = session.end_time.split(':').map(Number);
    const startTime = startH * 60 + startM;
    const endTime = endH * 60 + endM;

    if (currentTime >= endTime) return 'completed';
    if (currentTime >= startTime) return 'active';
    return 'upcoming';
  },

  getTeacherSessionsForUI(teacherProfileId) {
    const sessions = this.getSessionsForTeacher(teacherProfileId);
    return sessions.map(s => this.getSessionDataForUI(s.id)).filter(Boolean);
  },

  // --- Group Data for UI ---
  getGroupDataForUI(groupId) {
    const group = this.getGroup(groupId);
    if (!group) return null;
    const filiere = this.getFiliere(group.filiere_id);
    const students = this.getStudentsInGroup(groupId);
    const rates = students.map(sp => this.calculateAttendanceRate(sp.id));
    const avgRate = rates.length > 0 ? Math.round(rates.reduce((a, b) => a + b, 0) / rates.length) : 0;
    return {
      id: group.id,
      name: group.name,
      filiereId: group.filiere_id,
      filiereName: filiere ? filiere.name : '',
      filiereCode: filiere ? filiere.code : '',
      studentCount: students.length,
      attendanceRate: avgRate
    };
  },

  // --- Authentication ---
  authenticate(email, password) {
    const user = this.getUserByEmail(email);
    if (!user || user.password !== password) return null;
    const role = this.getUserRole(user.id);
    return { user, role };
  },

  // --- Date Utilities ---
  formatDate(dateStr) {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateStr).toLocaleDateString('en-US', options);
  },

  formatShortDate(dateStr) {
    const options = { month: 'short', day: 'numeric', year: 'numeric' };
    return new Date(dateStr).toLocaleDateString('en-US', options);
  },

  getRecentActivity() {
    const activities = [];
    
    // Add attendance activities
    const recentAttendance = AppData.attendanceRecords.slice(-3);
    recentAttendance.forEach(record => {
      const session = this.getSessionDataForUI(record.session_id);
      const student = this.getStudentDataForUI(record.student_id);
      activities.push({
        type: 'attendance',
        title: `Attendance recorded for ${session.groupName}`,
        description: `${student.name} marked as ${record.status}`,
        date: record.date,
        icon: 'check',
        color: 'green'
      });
    });

    // Add justification activities
    const recentJustifications = AppData.justifications.slice(-2);
    recentJustifications.forEach(just => {
      const student = this.getStudentDataForUI(just.student_id);
      activities.push({
        type: 'justification',
        title: `New justification from ${student.name}`,
        description: `Reason: ${just.reason.substring(0, 30)}...`,
        date: just.submitted_at,
        icon: 'file-text',
        color: just.status === 'pending' ? 'amber' : 'blue'
      });
    });

    return activities.sort((a, b) => new Date(b.date) - new Date(a.date));
  }
};

// Export for use in HTML files
if (typeof window !== 'undefined') {
  window.AppData = AppData;
  window.DataHelpers = DataHelpers;
}
