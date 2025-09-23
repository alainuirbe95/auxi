# Community Auth Tables Reference

## 🗃️ **Existing Tables in Your Database**

Based on CodeIgniter Community Auth, your database likely contains these tables:

### **Core Authentication Tables:**
1. **`users`** - Main user table (primary key: `user_id`)
2. **`auth_sessions`** - Active user sessions (references `users.user_id`)
3. **`ci_sessions`** - CodeIgniter sessions

### **ACL (Access Control List) Tables:**
4. **`acl`** - User permissions (references `users.user_id`)
5. **`acl_actions`** - Available actions
6. **`acl_categories`** - Action categories

### **Security & Login Tables:**
7. **`login_errors`** - Failed login attempts
8. **`ips_on_hold`** - IP addresses temporarily blocked
9. **`username_or_email_on_hold`** - Usernames/emails temporarily blocked
10. **`denied_access`** - Denied access records

### **New Marketplace Tables (if created):**
11. **`jobs`** - Job listings (references `users.user_id`)
12. **`job_photos`** - Job photos (references `users.user_id`)
13. **`offers`** - Offers from cleaners (references `users.user_id`)
14. **`job_assignments`** - Job assignments (references `users.user_id`)
15. **`payments`** - Payment records (references `users.user_id`)
16. **`disputes`** - Dispute records (references `users.user_id`)
17. **`user_strikes`** - User penalties (references `users.user_id`)
18. **`notifications`** - Notifications (references `users.user_id`)

## 🔗 **Foreign Key Dependencies**

### **Tables that reference `users.user_id`:**
- `auth_sessions.user_id`
- `acl.user_id`
- `jobs.host_id`
- `job_photos.uploaded_by`
- `offers.cleaner_id`
- `job_assignments.cleaner_id`
- `payments.host_id` & `payments.cleaner_id`
- `disputes.host_id`, `disputes.cleaner_id`, `disputes.admin_id`
- `user_strikes.user_id` & `user_strikes.admin_id`
- `notifications.user_id`

## 🚨 **Why You Can't Drop Users Table**

The error occurs because these tables have foreign key constraints that prevent dropping the referenced `users` table:

```sql
-- These constraints must be removed first:
ALTER TABLE auth_sessions DROP FOREIGN KEY [constraint_name];
ALTER TABLE acl DROP FOREIGN KEY [constraint_name];
-- ... and so on for all tables
```

## ✅ **Solutions**

### **Option 1: Remove Foreign Keys Only**
- Use `remove_all_foreign_keys.sql`
- Keeps all tables intact
- Allows you to modify users table
- Re-add foreign keys later

### **Option 2: Complete Cleanup**
- Use `drop_users_table_complete.sql`
- Removes all marketplace tables
- Keeps or removes Community Auth tables
- Complete fresh start

### **Option 3: Manual Approach**
1. Check existing foreign keys:
```sql
SELECT TABLE_NAME, CONSTRAINT_NAME 
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE REFERENCED_TABLE_NAME = 'users';
```

2. Remove each foreign key:
```sql
ALTER TABLE [table_name] DROP FOREIGN KEY [constraint_name];
```

3. Drop users table:
```sql
DROP TABLE users;
```

## 🔍 **Check Your Current Database**

Run this to see what tables you actually have:
```sql
SHOW TABLES;
```

Run this to see what foreign keys exist:
```sql
SELECT 
    TABLE_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME
FROM 
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE 
    REFERENCED_TABLE_SCHEMA = DATABASE()
    AND REFERENCED_TABLE_NAME = 'users';
```

This will show you exactly which tables are preventing the users table from being dropped.
