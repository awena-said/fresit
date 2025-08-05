# Database Setup Guide for phpMyAdmin

This guide will help you set up the MySQL database using phpMyAdmin and connect it to your staff login system.

## 🚀 **Step 1: Import Database into phpMyAdmin**

### 1.1 Open phpMyAdmin
- Open your web browser
- Navigate to your phpMyAdmin URL (usually `http://your-vm-ip/phpmyadmin`)
- Login with your MySQL credentials

### 1.2 Create Database
- Click on "New" in the left sidebar
- Enter database name: `fresit_art_school`
- Select collation: `utf8mb4_unicode_ci`
- Click "Create"

### 1.3 Import SQL File
- Select the `fresit_art_school` database
- Click on "Import" tab
- Click "Choose File" and select: `database/fresit_database.sql`
- Click "Go" to import

### 1.4 Verify Import
- You should see 8 tables created:
  - `staff_users`
  - `applications`
  - `classes`
  - `class_enrollments`
  - `instructors`
  - `class_assignments`
  - `activity_log`
  - `settings`

## 🔧 **Step 2: Configure Database Connection**

### 2.1 Update Database Settings
Edit the file: `includes/database.php`

```php
// Update these settings to match your VM's MySQL setup
define('DB_HOST', 'localhost');        // Your MySQL host
define('DB_NAME', 'fresit_art_school'); // Database name
define('DB_USER', 'your_mysql_username'); // Your MySQL username
define('DB_PASS', 'your_mysql_password'); // Your MySQL password
define('DB_CHARSET', 'utf8mb4');
```

### 2.2 Common VM Database Settings
- **Host**: Usually `localhost` or `127.0.0.1`
- **Username**: Usually `root` or your MySQL username
- **Password**: Your MySQL password (if any)
- **Database**: `fresit_art_school`

## 🔑 **Step 3: Test the Connection**

### 3.1 Start PHP Server
```bash
php -S localhost:8000 -t public
```

### 3.2 Test Staff Login
- Go to: `http://localhost:8000/staff/login`
- Login with:
  - **Email**: `admin@fresit.com`
  - **Password**: `password`

### 3.3 Check Dashboard
- After login, you should see the staff dashboard
- Check that statistics are loading from the database

## 📊 **Step 4: Verify Database Data**

### 4.1 Check Sample Data in phpMyAdmin
- Go to `staff_users` table
- You should see 2 users:
  - `admin@fresit.com` (admin)
  - `staff@fresit.com` (staff)

- Go to `applications` table
- You should see 3 sample applications

- Go to `classes` table
- You should see 3 sample classes

### 4.2 Test Database Queries
In phpMyAdmin, you can run these queries to verify data:

```sql
-- Check staff users
SELECT * FROM staff_users;

-- Check applications
SELECT * FROM applications;

-- Check classes
SELECT * FROM classes;

-- Check dashboard stats
SELECT 
    (SELECT COUNT(*) FROM applications) as total_applications,
    (SELECT COUNT(*) FROM applications WHERE status = 'pending') as pending_applications,
    (SELECT COUNT(*) FROM classes) as total_classes;
```

## 🚨 **Troubleshooting**

### Connection Issues
If you get database connection errors:

1. **Check MySQL is running**:
   ```bash
   sudo systemctl status mysql
   # or
   sudo service mysql status
   ```

2. **Check MySQL credentials**:
   ```bash
   mysql -u your_username -p
   ```

3. **Verify database exists**:
   ```sql
   SHOW DATABASES;
   USE fresit_art_school;
   SHOW TABLES;
   ```

### Permission Issues
If you get permission errors:

1. **Grant privileges**:
   ```sql
   GRANT ALL ON fresit_art_school.* TO 'your_username'@'localhost';
   FLUSH PRIVILEGES;
   ```

2. **Check user permissions**:
   ```sql
   SHOW GRANTS FOR 'your_username'@'localhost';
   ```

### Common Error Messages

**"Access denied for user"**
- Check username and password in `includes/database.php`
- Verify user has proper privileges

**"Unknown database"**
- Make sure database `fresit_art_school` exists
- Check database name spelling

**"Table doesn't exist"**
- Re-import the SQL file
- Check that all 8 tables were created

## ✅ **Success Indicators**

When everything is working correctly:

1. ✅ **Staff login works** with `admin@fresit.com` / `password`
2. ✅ **Dashboard loads** with statistics from database
3. ✅ **Applications page** shows sample applications
4. ✅ **No database errors** in browser console or PHP logs
5. ✅ **All tables exist** in phpMyAdmin with sample data

## 🔄 **Next Steps**

After successful setup:

1. **Create new staff accounts** through the system
2. **Add real applications** and classes
3. **Customize settings** in the `settings` table
4. **Backup database** regularly

## 📞 **Need Help?**

If you encounter issues:

1. Check PHP error logs
2. Verify MySQL is running
3. Test database connection manually
4. Review the troubleshooting section above

---

**Note**: Always backup your database before making changes! 