# User Roles and Permissions

## Role Hierarchy
1. **Super Admin** - Full system access
2. **Admin** - Branch-level management
3. **Technician** - Job and repair management

---

## Super Admin Permissions

### Branch Management
- ✅ View all branches
- ✅ Create new branches
- ✅ Edit any branch
- ✅ Delete branches
- ✅ Activate/deactivate branches

### User Management
- ✅ View all users (across all branches)
- ✅ Create users with any role (including Super Admin)
- ✅ Edit any user (including other Super Admins)
- ✅ Delete any user (except themselves)
- ✅ Assign users to any branch
- ✅ Change user roles
- ✅ Activate/deactivate users

### Data Access
- ✅ View data from all branches
- ✅ Switch between branch views
- ✅ Filter by "All Branches"

---

## Admin Permissions

### Branch Management
- ✅ View all branches (read-only)
- ❌ Cannot create branches
- ❌ Cannot edit branches
- ❌ Cannot delete branches
- **UI**: Add/Edit/Delete buttons hidden, "View Only" badge shown

### User Management
- ✅ View users in their own branch only
- ✅ Create users (Admin or Technician only)
- ✅ Edit users in their own branch (except Super Admins)
- ✅ Delete users in their own branch (except Super Admins)
- ❌ Cannot see Super Admin users in the list
- ❌ Cannot create Super Admin users
- ❌ Cannot edit Super Admin users
- ❌ Cannot delete Super Admin users
- ❌ Cannot manage users from other branches
- ❌ Cannot change user's branch assignment
- **Restrictions**:
  - "Super Admin" option hidden from role dropdown
  - Branch selector disabled (auto-set to admin's branch)
  - Users from other branches filtered out

### Data Access
- ✅ View data from their assigned branch only
- ❌ Cannot switch branches
- ❌ Cannot view "All Branches"

---

## Technician Permissions

### General Access
- ✅ View customers in their branch
- ✅ Create/edit customers
- ✅ View jobs in their branch
- ✅ Create/edit/update jobs
- ✅ Manage repairs and parts
- ❌ Cannot access Settings
- ❌ Cannot manage users
- ❌ Cannot manage branches

---

## Implementation Details

### Backend Security (Controller Level)
All permission checks are enforced in controllers:

**SettingController.php**:
```php
// Branch Management - Super Admin Only
if (!$this->isSuperAdmin()) {
    return redirect()->with('error', 'Access Denied');
}

// User Management - Admin Restrictions
if (!$this->isSuperAdmin()) {
    // Filter users by branch
    $builder->where('users.branch_id', $this->getBranchId())
            ->where('users.role !=', 'super_admin');
    
    // Prevent creating Super Admin
    if ($role === 'super_admin') {
        return redirect()->with('error', 'Access Denied');
    }
    
    // Force branch to admin's branch
    $branchId = $this->getBranchId();
}
```

### Frontend UX (View Level)
UI elements conditionally shown based on role:

**branches.php**:
```php
<?php if ($isSuperAdmin): ?>
    <button>Add Branch</button>
    <button>Edit</button>
    <button>Delete</button>
<?php else: ?>
    <span class="badge">View Only</span>
<?php endif; ?>
```

**users.php**:
```php
<!-- Hide Super Admin option for Admin -->
<select name="role">
    <option value="technician">Technician</option>
    <option value="admin">Admin</option>
    <?php if ($isSuperAdmin): ?>
        <option value="super_admin">Super Admin</option>
    <?php endif; ?>
</select>
```

### Helper Methods (BaseController)
```php
protected function isSuperAdmin(): bool
protected function isAdmin(): bool  // Returns true for both admin and super_admin
protected function getBranchId(): ?int
protected function getBranchFilter(): array
```

---

## Testing Checklist

### Test as Super Admin
- [ ] Can create/edit/delete branches
- [ ] Can see all users from all branches
- [ ] Can create Super Admin users
- [ ] Can edit/delete any user
- [ ] Can switch between branches

### Test as Admin
- [ ] Cannot create/edit/delete branches (buttons hidden)
- [ ] Can only see users from own branch
- [ ] Cannot see Super Admin users in list
- [ ] Cannot create Super Admin users (option hidden)
- [ ] Cannot edit users from other branches
- [ ] Cannot delete Super Admin users
- [ ] Branch selector is disabled when creating users

### Test as Technician
- [ ] Cannot access Settings menu
- [ ] Can only see data from own branch
- [ ] Can manage customers and jobs

---

## Security Notes

⚠️ **Important**: 
- All security checks are implemented at the **controller level** (backend)
- Frontend UI changes are for **user experience only**
- Even if a user manipulates the UI (e.g., using browser dev tools), the backend will reject unauthorized actions
- Never rely solely on frontend validation for security
