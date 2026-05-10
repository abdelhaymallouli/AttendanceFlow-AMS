# Auth Views Documentation

The Auth views handle user authentication and session entry points.

## 📄 `auth/login.blade.php`
- **Role**: The main login portal.
- **Design**:
  - Uses the same premium "solicode AMS" branding.
  - Centered card layout with high contrast.
  - Includes fields for Email and Password.
  - **Error Handling**: Displays Blade `@error` messages for invalid credentials or locked accounts.
  - **User Experience**: Mobile-friendly inputs with proper keyboard types.
