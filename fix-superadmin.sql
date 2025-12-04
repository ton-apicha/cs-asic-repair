-- Fix superadmin role
UPDATE users SET role='super_admin', branch_id=NULL WHERE username='superadmin';

-- Verify
SELECT id, username, role, branch_id, is_active FROM users WHERE username='superadmin';
