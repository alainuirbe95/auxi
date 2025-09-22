# AuxiApp Database Installation Guide

## 🚨 **IMPORTANT: Use the Fixed Version**

The original `auxi_database_schema.sql` has foreign key constraint issues. Use `auxi_database_schema_fixed.sql` instead.

## 📋 **Prerequisites**

1. **MySQL/MariaDB** running and accessible
2. **Database created** (e.g., `auxidb`)
3. **MySQL user** with CREATE, INSERT, UPDATE, DELETE privileges
4. **CodeIgniter application** already set up

## 🔧 **Installation Steps**

### **Step 1: Create Database (if not exists)**
```sql
CREATE DATABASE auxidb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE auxidb;
```

### **Step 2: Run the Fixed Schema**
```bash
# Option 1: Using MySQL command line
mysql -u your_username -p auxidb < auxi_database_schema_fixed.sql

# Option 2: Using phpMyAdmin
# 1. Open phpMyAdmin
# 2. Select your database
# 3. Go to "Import" tab
# 4. Choose "auxi_database_schema_fixed.sql"
# 5. Click "Go"
```

### **Step 3: Verify Installation**
```sql
-- Check if all tables were created
SHOW TABLES;

-- Expected output:
-- +-------------------+
-- | Tables_in_auxidb  |
-- +-------------------+
-- | disputes          |
-- | job_assignments   |
-- | job_photos        |
-- | jobs              |
-- | notifications     |
-- | offers            |
-- | payments          |
-- | user_strikes      |
-- | users             |
-- +-------------------+

-- Check foreign key constraints
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM 
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE 
    REFERENCED_TABLE_SCHEMA = 'auxidb'
    AND REFERENCED_TABLE_NAME IS NOT NULL;
```

## 🔍 **Troubleshooting**

### **Error: "Cannot add or update a child row: a foreign key constraint fails"**

**Cause:** The `users` table doesn't exist or has wrong structure.

**Solution:**
1. Use `auxi_database_schema_fixed.sql` (not the original)
2. The fixed version creates the `users` table first
3. Then creates all other tables with proper foreign keys

### **Error: "Table 'users' doesn't exist"**

**Cause:** The users table wasn't created properly.

**Solution:**
```sql
-- Check if users table exists
SHOW TABLES LIKE 'users';

-- If not, run this manually first:
-- (Copy the users table creation from auxi_database_schema_fixed.sql)
```

### **Error: "Access denied for user"**

**Cause:** Insufficient database privileges.

**Solution:**
```sql
-- Grant necessary privileges
GRANT CREATE, INSERT, UPDATE, DELETE, SELECT, INDEX, ALTER ON auxidb.* TO 'your_username'@'localhost';
FLUSH PRIVILEGES;
```

### **Error: "Unknown column 'user_id' in 'field list'"**

**Cause:** The users table has a different primary key name.

**Solution:**
1. Check your existing users table structure:
```sql
DESCRIBE users;
```

2. If it uses `id` instead of `user_id`, update the foreign key references:
```sql
-- Update all foreign key references
ALTER TABLE jobs DROP FOREIGN KEY fk_jobs_host_id;
ALTER TABLE jobs ADD CONSTRAINT fk_jobs_host_id FOREIGN KEY (host_id) REFERENCES users(id) ON DELETE CASCADE;
-- Repeat for all other tables...
```

## ✅ **Verification Checklist**

After installation, verify:

- [ ] All 9 tables created successfully
- [ ] Foreign key constraints working
- [ ] Sample data inserted (3 users, 1 job)
- [ ] Views created (active_jobs, cleaner_earnings, platform_analytics)
- [ ] Indexes created for performance
- [ ] No error messages in MySQL logs

## 🧪 **Test the Installation**

```sql
-- Test 1: Check sample data
SELECT * FROM users;
SELECT * FROM jobs;

-- Test 2: Test foreign key relationships
SELECT 
    j.title,
    u.username as host_name
FROM jobs j
JOIN users u ON j.host_id = u.user_id;

-- Test 3: Test views
SELECT * FROM active_jobs;
SELECT * FROM platform_analytics;

-- Test 4: Test constraints
-- This should fail (foreign key constraint):
INSERT INTO jobs (host_id, title, address, city, state, scheduled_date, scheduled_time, estimated_duration, suggested_price, status, created_at, updated_at) 
VALUES (999, 'Test Job', 'Test Address', 'Test City', 'Test State', '2024-01-01', '10:00:00', 60, 100.00, 'draft', NOW(), NOW());
```

## 🔄 **CodeIgniter Migration Alternative**

If you prefer using CodeIgniter migrations:

```php
// In your CodeIgniter application
// Go to: http://your-domain/migrate
// Or run: php index.php migrate
```

**Note:** The migration files are already created in `application/migrations/` directory.

## 📊 **Database Structure Summary**

| Table | Purpose | Key Relationships |
|-------|---------|-------------------|
| `users` | User management (existing) | Primary table |
| `jobs` | Job listings | host_id → users.user_id |
| `job_photos` | Job photos | job_id → jobs.id, uploaded_by → users.user_id |
| `offers` | Offers/counters | job_id → jobs.id, cleaner_id → users.user_id |
| `job_assignments` | Job assignments | job_id → jobs.id, cleaner_id → users.user_id |
| `payments` | Payment processing | job_id → jobs.id, host_id/cleaner_id → users.user_id |
| `disputes` | Dispute management | job_id → jobs.id, host_id/cleaner_id → users.user_id |
| `user_strikes` | Strike tracking | user_id → users.user_id, job_id → jobs.id |
| `notifications` | Notifications | user_id → users.user_id, job_id → jobs.id |

## 🚀 **Next Steps**

After successful installation:

1. **Update CodeIgniter config** - Point to the new database
2. **Create models** - Build CodeIgniter models for each table
3. **Test API endpoints** - Ensure data can be retrieved
4. **Build frontend** - Create marketplace interfaces

## 📞 **Support**

If you encounter issues:

1. Check MySQL error logs
2. Verify database user privileges
3. Ensure all tables were created
4. Test foreign key relationships
5. Check sample data insertion

The fixed schema should resolve all foreign key constraint issues!
