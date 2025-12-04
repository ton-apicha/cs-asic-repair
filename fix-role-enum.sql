-- Fix ENUM to include super_admin
ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'super_admin', 'technician') NULL;

-- Update superadmin role
UPDATE users SET role='super_admin', branch_id=NULL WHERE username='superadmin';

-- Verify
SELECT id, username, role, branch_id, is_active FROM users;
