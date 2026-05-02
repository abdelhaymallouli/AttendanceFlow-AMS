# Auth Controllers Documentation (Session-Based)

These controllers handle the standard web-based authentication flow.

## 📄 `Auth\LoginController`
- **Role**: Entry point for all web users.
- **Key Features**:
  - `showLoginForm()`: Displays the custom login view.
  - `login()`: Validates credentials and initializes a stateful session.
  - **Redirection Logic**: Automatically redirects users to their specific dashboard based on their role:
    - Admin -> `/admin/dashboard`
    - Teacher -> `/teacher/dashboard`
    - Student -> `/student/dashboard`
  - `logout()`: Destroys the session and redirects to the landing page.
