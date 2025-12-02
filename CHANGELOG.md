# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased] - 2025-12-02

### Added - User Management Permission Controls
- **Admin Role Restrictions**: Admin users now have limited permissions compared to Super Admin
  - Admin can only view users within their own branch
  - Admin cannot create, edit, or delete Super Admin users
  - Admin cannot manage users from other branches
  - Admin users are automatically assigned to their own branch when creating new users

### Added - Branch Management Permission Controls  
- **Super Admin Only**: Branch management (create, edit, delete) is now restricted to Super Admin only
  - Admin users can view all branches (read-only)
  - Admin users cannot create, edit, or delete branches
  - UI elements (Add/Edit/Delete buttons) are hidden from Admin users in branch management

### Fixed - Mobile Responsive Design
- **Customers Page Mobile View**: Fixed customers list not displaying on mobile devices
  - Email column hidden on mobile (< 768px), shown below customer name instead
  - Created Date column hidden on mobile (< 992px), shown below phone number instead
  - Added icons (envelope, calendar) for better mobile UX
  - Table now properly responsive with Bootstrap classes

### Technical Changes

#### Backend (Controllers)
- `app/Controllers/SettingController.php`:
  - `users()`: Added branch and role filtering for Admin users
  - `storeUser()`: Added security checks preventing Admin from creating Super Admin users
  - `updateUser()`: Added validation to prevent Admin from editing Super Admin users or users from other branches
  - `deleteUser()`: Added validation to prevent Admin from deleting Super Admin users or users from other branches
  - `storeBranch()`, `updateBranch()`, `deleteBranch()`: Added Super Admin only checks

#### Frontend (Views)
- `app/Views/settings/branches.php`:
  - Conditionally hide Add/Edit/Delete buttons for Admin users
  - Show "View Only" badge for Admin users
  
- `app/Views/customers/index.php`:
  - Added responsive classes `d-none d-md-table-cell` for Email column
  - Added responsive classes `d-none d-lg-table-cell` for Created Date column
  - Email displayed below customer name on mobile with envelope icon
  - Date displayed below phone number on mobile with calendar icon

### Security Improvements
- All permission checks implemented at controller level (backend) for security
- UI changes are for UX only - backend enforces all restrictions
- Admin cannot bypass restrictions even if UI is manipulated

---

## Previous Changes
See git history for changes prior to 2025-12-02
