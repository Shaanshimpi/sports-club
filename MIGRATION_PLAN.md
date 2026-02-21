# MySQL to PostgreSQL Migration Plan

## Overview
This plan converts the Sports Club Management System from MySQL (using MySQLi) to PostgreSQL (using PDO or pg_* functions). The migration is divided into 6 phases with UI verification tests after each phase.

## Current State Analysis
- **Database**: MySQL with 8 tables (admin, users, log_users, health_status, plan, enrolls_to, sports_timetable, address)
- **Database Objects**: 3 triggers (deletelog, insertlog, updatelog), 1 stored procedure (countGender)
- **Connection Files**: `connect.php` and `include/db_conn.php` using `mysqli_connect()`
- **PHP Files**: ~30+ files using MySQLi functions (`mysqli_query`, `mysqli_fetch_array`, `mysqli_affected_rows`, etc.)
- **Key Features**: Member management, plan subscriptions, payments, health status tracking, timetables

---

## Prerequisites and Setup

### 1. Install PostgreSQL
- Install PostgreSQL server (latest stable version recommended: v14 or v18)
- During installation, note:
  - Installation path
  - Port (default: 5432)
  - Set password for `postgres` superuser (you set: 1234)
  - Select components: Database Server, pgBouncer (optional)

### 2. Enable PHP PostgreSQL Extension in XAMPP
1. Open `C:\xampp\php\php.ini`
2. Find and uncomment these lines (remove the `;`):
   ```ini
   extension=pdo_pgsql
   extension=pgsql
   ```
3. Restart Apache in XAMPP Control Panel
4. Verify extension is loaded by creating `test_pg.php` in htdocs:
   ```php
   <?php
   phpinfo();
   ```
   Visit `http://localhost/test_pg.php` and search for "pdo_pgsql"

### 3. Create PostgreSQL Database
You can create the database using one of these methods:

#### Method 1: Using pgAdmin (GUI)
1. Open pgAdmin 4
2. Connect to PostgreSQL server (password: 1234)
3. Right-click on "Databases" → "Create" → "Database"
4. Name: `sports_club_db`
5. Click "Save"

#### Method 2: Using Command Line (psql)
```cmd
# Open Command Prompt and navigate to PostgreSQL bin directory
cd C:\Program Files\PostgreSQL\18\bin

# Connect to PostgreSQL
psql -U postgres

# Enter password when prompted: 1234

# Create database
CREATE DATABASE sports_club_db;

# Exit psql
\q
```

#### Method 3: Using SQL File
Create a file `create_db.sql`:
```sql
CREATE DATABASE sports_club_db;
```
Then run:
```cmd
psql -U postgres -f create_db.sql
# Enter password: 1234
```

---

## How to Run PostgreSQL SQL Files

### Method 1: Using pgAdmin (Recommended for Beginners)
1. Open pgAdmin 4
2. Connect to PostgreSQL server
3. Expand "Databases" → Right-click `sports_club_db` → "Query Tool"
4. Click the folder icon (Open File) or paste SQL content
5. Click the Execute button (▶) or press F5
6. Check the "Messages" tab for success/error messages

### Method 2: Using psql Command Line
```cmd
# Navigate to PostgreSQL bin directory
cd C:\Program Files\PostgreSQL\18\bin

# Run SQL file
psql -U postgres -d sports_club_db -f "D:\path\to\sports_club_db_pg.sql"

# Enter password when prompted: 1234
```

### Method 3: Using psql Interactive Mode
```cmd
# Connect to database
psql -U postgres -d sports_club_db

# Enter password: 1234

# Run SQL commands directly or from file
\i "D:\path\to\sports_club_db_pg.sql"

# Or paste SQL content directly

# Exit
\q
```

### Method 4: Using PHP Script (For Testing)
Create `run_sql.php`:
```php
<?php
$host = "localhost";
$port = "5432";
$dbname = "sports_club_db";
$user = "postgres";
$password = "1234";

try {
    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
    
    if (!$conn) {
        die("Connection failed: " . pg_last_error());
    }
    
    // Read SQL file
    $sql = file_get_contents('sports_club_db_pg.sql');
    
    // Execute SQL
    $result = pg_query($conn, $sql);
    
    if ($result) {
        echo "SQL executed successfully!";
    } else {
        echo "Error: " . pg_last_error($conn);
    }
    
    pg_close($conn);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

### Common psql Commands
```sql
-- List all databases
\l

-- Connect to a database
\c sports_club_db

-- List all tables
\dt

-- Describe a table structure
\d table_name

-- List all functions
\df

-- List all triggers
\dy

-- Show current database
SELECT current_database();

-- Exit psql
\q
```

### Troubleshooting SQL Execution
- **Permission errors**: Make sure you're connected as `postgres` user or a user with proper privileges
- **Syntax errors**: Check PostgreSQL syntax differences from MySQL
- **Connection errors**: Verify PostgreSQL service is running
- **File path errors**: Use absolute paths or ensure you're in the correct directory

---

## Phase 1: Environment Setup and Database Schema Conversion

### Tasks
1. **Install PostgreSQL and PHP PostgreSQL extensions**
   - ✅ PostgreSQL installed (password: 1234)
   - ✅ Enable `pdo_pgsql` or `pgsql` extension in PHP
   - ✅ Verify connection capability

2. **Convert SQL schema file** (`sports_club_db.sql`)
   - Remove MySQL-specific syntax:
     - `ENGINE = InnoDB` → Remove (PostgreSQL uses default)
     - `CHARACTER SET = latin1 COLLATE = latin1_swedish_ci` → Convert to PostgreSQL collation
     - `AUTO_INCREMENT` → `SERIAL` or `GENERATED ALWAYS AS IDENTITY`
     - `USING BTREE` → Remove (PostgreSQL default)
     - Backticks `` ` `` → Double quotes `"` or remove
   - Convert data types:
     - `varchar(n)` → `VARCHAR(n)` (compatible)
     - `int(11)` → `INTEGER`
     - `datetime` → `TIMESTAMP`
   - Update foreign key syntax if needed
   - Convert triggers:
     - MySQL `OLD`/`NEW` → PostgreSQL `OLD`/`NEW` (similar syntax)
     - `NOW()` → `CURRENT_TIMESTAMP`
   - Convert stored procedure:
     - MySQL `DELIMITER` → PostgreSQL function syntax
     - `CREATE PROCEDURE` → `CREATE OR REPLACE FUNCTION`
   - Remove `SET FOREIGN_KEY_CHECKS` (PostgreSQL doesn't support)
   - Remove `SET NAMES utf8mb4`

3. **Create new PostgreSQL schema file** (`sports_club_db_pg.sql`)
   - Test schema creation in PostgreSQL
   - Verify all tables, constraints, and indexes

4. **Data migration preparation**
   - Export data from MySQL (CSV or SQL INSERT statements)
   - Prepare data import script for PostgreSQL

### How to Run Phase 1 SQL File
After the converted `sports_club_db_pg.sql` is created:

1. **Using pgAdmin**:
   - Open pgAdmin → Connect to server
   - Right-click `sports_club_db` → Query Tool
   - Open `sports_club_db_pg.sql` file
   - Execute (F5)
   - Check for errors in Messages tab

2. **Using Command Line**:
   ```cmd
   cd C:\Program Files\PostgreSQL\18\bin
   psql -U postgres -d sports_club_db -f "C:\xampp\htdocs\Sport-club-management-system-pg\sports_club_db_pg.sql"
   ```

### UI Tests for Phase 1
- [ ] Verify PostgreSQL server is running
- [ ] Verify PHP can connect to PostgreSQL (create test connection script)
- [ ] Verify all tables are created successfully (`\dt` in psql or pgAdmin)
- [ ] Verify all foreign key constraints are in place
- [ ] Verify triggers are created and functional
- [ ] Verify stored procedure/function is created
- [ ] Verify initial data is imported correctly

---

## Phase 2: Database Connection Layer Migration

### Tasks
1. **Update `include/db_conn.php`**
   - Replace `mysqli_connect()` with PostgreSQL connection:
     - Option A: Use `pg_connect()` (pg_* functions)
     - Option B: Use PDO with PostgreSQL driver (recommended)
   - Update connection parameters (host, port, database, user, password)
   - Update error handling to use PostgreSQL error functions
   - Keep `page_protect()` function unchanged (session-based, no DB dependency)

2. **Update `connect.php`**
   - Convert to PostgreSQL connection
   - Update database name from `tms` to `sports_club_db`

3. **Create connection abstraction layer** (optional but recommended)
   - Create wrapper functions for common database operations
   - This will make future migrations easier

### UI Tests for Phase 2
- [ ] Test login functionality (`secure_login.php`)
- [ ] Verify admin can log in with existing credentials
- [ ] Verify session management works correctly
- [ ] Check for any connection errors in browser console/server logs
- [ ] Verify dashboard loads without database errors

---

## Phase 3: Core Query Functions Migration

### Tasks
1. **Replace MySQLi functions with PostgreSQL equivalents**
   - `mysqli_query()` → `pg_query()` or PDO `query()`
   - `mysqli_fetch_array($result, MYSQLI_ASSOC)` → `pg_fetch_assoc()` or PDO `fetch(PDO::FETCH_ASSOC)`
   - `mysqli_fetch_row()` → `pg_fetch_row()` or PDO `fetch(PDO::FETCH_NUM)`
   - `mysqli_num_rows()` → `pg_num_rows()` or PDO row count
   - `mysqli_affected_rows()` → `pg_affected_rows()` or PDO `rowCount()`
   - `mysqli_real_escape_string()` → `pg_escape_string()` or PDO prepared statements (recommended)
   - `mysqli_error()` → `pg_last_error()` or PDO `errorInfo()`
   - `mysqli_connect_errno()` → PostgreSQL error checking

2. **Update files in priority order:**
   - **Authentication**: `secure_login.php`, `change_s_pwd.php`
   - **Dashboard**: `dashboard/admin/index.php`
   - **Member Management**: `dashboard/admin/new_entry.php`, `dashboard/admin/new_submit.php`, `dashboard/admin/view_mem.php`, `dashboard/admin/edit_member.php`, `dashboard/admin/edit_mem_submit.php`, `dashboard/admin/del_member.php`
   - **Plan Management**: `dashboard/admin/view_plan.php`, `dashboard/admin/new_plan.php`, `dashboard/admin/submit_plan_new.php`, `dashboard/admin/edit_plan.php`, `dashboard/admin/updateplan.php`, `dashboard/admin/del_plan.php`
   - **Payment Management**: `dashboard/admin/payments.php`, `dashboard/admin/make_payments.php`, `dashboard/admin/submit_payments.php`
   - **Reports**: `dashboard/admin/income_month.php`, `dashboard/admin/revenue_month.php`, `dashboard/admin/over_month.php`, `dashboard/admin/over_members_month.php`, `dashboard/admin/over_members_year.php`
   - **Health Status**: `dashboard/admin/new_health_status.php`, `dashboard/admin/health_status_entry.php`
   - **Routines/Timetable**: `dashboard/admin/viewroutine.php`, `dashboard/admin/saveroutine.php`, `dashboard/admin/updateroutine.php`, `dashboard/admin/deleteroutine.php`
   - **Other**: `dashboard/admin/read_member.php`, `dashboard/admin/more-userprofile.php`, `dashboard/admin/gen_invoice.php`, `dashboard/admin/table_view.php`, `dashboard/admin/viewall_detail.php`, `dashboard/admin/plandetail.php`

3. **SQL Query Adjustments**
   - Replace backticks with double quotes or remove
   - Update `LIKE` patterns if needed (PostgreSQL is case-sensitive by default, may need `ILIKE`)
   - Update date functions: `NOW()` → `CURRENT_TIMESTAMP`
   - Check `COUNT(*)` usage (should work the same)

### UI Tests for Phase 3
- [ ] **Login & Authentication**
  - [ ] Admin login works correctly
  - [ ] Password change functionality works
  - [ ] Session persists correctly
  - [ ] Logout works correctly

- [ ] **Dashboard**
  - [ ] Dashboard loads with correct statistics
  - [ ] "Paid Income This Month" displays correct amount
  - [ ] "Total Members" count is accurate
  - [ ] "Joined This Month" count is accurate
  - [ ] "Total Plan Available" count is accurate

- [ ] **Member Management**
  - [ ] Create new member with all fields
  - [ ] View member list displays all members
  - [ ] Edit member information
  - [ ] Delete member (verify cascade deletes work)
  - [ ] View member details/history
  - [ ] Search/filter members (if applicable)

- [ ] **Plan Management**
  - [ ] View all plans
  - [ ] Create new plan
  - [ ] Edit existing plan
  - [ ] Delete plan
  - [ ] Plan details display correctly

---

## Phase 4: Advanced Features Migration (Triggers, Stored Procedures, Complex Queries)

### Tasks
1. **Convert Triggers**
   - `deletelog` trigger: Convert MySQL trigger syntax to PostgreSQL
   - `insertlog` trigger: Convert to PostgreSQL
   - `updatelog` trigger: Convert to PostgreSQL
   - Test trigger functionality

2. **Convert Stored Procedure**
   - Convert `countGender` procedure to PostgreSQL function
   - Update any PHP code that calls the procedure
   - Test function execution

3. **Complex Query Updates**
   - Review JOIN queries (should work similarly)
   - Update `LIKE` patterns for case-insensitive searches if needed
   - Verify date comparisons work correctly
   - Check aggregate functions (SUM, COUNT, etc.)

4. **Update files with complex queries:**
   - `dashboard/admin/income_month.php` (complex JOIN)
   - `dashboard/admin/read_member.php`
   - `dashboard/admin/viewall_detail.php`
   - Any other files with complex queries

### UI Tests for Phase 4
- [ ] **Trigger Functionality**
  - [ ] Create new user → verify log_users entry is created (insertlog trigger)
  - [ ] Update user → verify log_users entry is created (updatelog trigger)
  - [ ] Delete user → verify log_users entry is created (deletelog trigger)
  - [ ] Verify log_users table contains correct action and timestamp

- [ ] **Stored Procedure/Function**
  - [ ] Execute countGender function (if called from UI)
  - [ ] Verify gender count results are correct

- [ ] **Complex Queries**
  - [ ] Income month report displays correct data with JOINs
  - [ ] Member detail views show all related data correctly
  - [ ] All reports generate without errors
  - [ ] Date-based queries work correctly

---

## Phase 5: Data Migration and Validation

### Tasks
1. **Export data from MySQL**
   - Export all table data (if not already done)
   - Verify data integrity before export

2. **Import data to PostgreSQL**
   - Import data in correct order (respecting foreign keys)
   - Handle any data type conversion issues
   - Verify row counts match

3. **Data validation**
   - Compare record counts between MySQL and PostgreSQL
   - Spot-check critical data
   - Verify foreign key relationships
   - Verify dates and timestamps

4. **Update any hardcoded values**
   - Check for hardcoded database names
   - Update any configuration files

### How to Export/Import Data

#### Export from MySQL
```cmd
# Using mysqldump
mysqldump -u root -p sports_club_db > mysql_export.sql

# Or export specific table
mysqldump -u root -p sports_club_db users > users_export.sql
```

#### Import to PostgreSQL
1. Convert MySQL INSERT statements to PostgreSQL format
2. Use pgAdmin Query Tool or psql to import
3. Or use a migration tool

### UI Tests for Phase 5
- [ ] **Data Integrity**
  - [ ] All members are visible in member list
  - [ ] All plans are visible in plan list
  - [ ] All payment records are visible
  - [ ] All health status records are present
  - [ ] All addresses are linked correctly
  - [ ] All enrollments are linked correctly

- [ ] **Data Accuracy**
  - [ ] Member details match original data
  - [ ] Payment amounts are correct
  - [ ] Dates are formatted correctly
  - [ ] Calculated fields (expiry dates, totals) are correct

- [ ] **Relationships**
  - [ ] Foreign key relationships work (cascade deletes)
  - [ ] User enrollments link to correct plans
  - [ ] Addresses link to correct users

---

## Phase 6: Testing, Cleanup, and Documentation

### Tasks
1. **Comprehensive Testing**
   - Test all CRUD operations for each module
   - Test all reports and queries
   - Test edge cases (empty results, invalid inputs)
   - Performance testing (compare query execution times)

2. **Code Cleanup**
   - Remove old MySQL connection code if any remains
   - Remove unused files (`connect.php` if not needed)
   - Standardize error handling
   - Add comments for PostgreSQL-specific code

3. **Security Review**
   - Ensure all queries use prepared statements or proper escaping
   - Verify SQL injection protection
   - Check connection security

4. **Documentation**
   - Update README with PostgreSQL setup instructions
   - Document connection parameters
   - Document any PostgreSQL-specific configurations
   - Create migration rollback plan (if needed)

### UI Tests for Phase 6
- [ ] **End-to-End Workflows**
  - [ ] Complete member registration flow (new member → select plan → payment)
  - [ ] Complete payment renewal flow
  - [ ] Complete plan management flow
  - [ ] Complete health status update flow
  - [ ] Complete timetable/routine management flow

- [ ] **Error Handling**
  - [ ] Invalid login credentials show appropriate error
  - [ ] Duplicate email registration shows error
  - [ ] Invalid date inputs are handled
  - [ ] Database connection errors are handled gracefully

- [ ] **Reports and Analytics**
  - [ ] Monthly income report generates correctly
  - [ ] Revenue reports are accurate
  - [ ] Member statistics are correct
  - [ ] All date-based filters work correctly

- [ ] **Performance**
  - [ ] Dashboard loads within acceptable time
  - [ ] Member list loads quickly
  - [ ] Reports generate without timeout
  - [ ] No noticeable performance degradation

- [ ] **Cross-browser Testing** (if applicable)
  - [ ] Test in Chrome
  - [ ] Test in Firefox
  - [ ] Test in Edge

---

## Key Files to Modify

### Database Files
- `sports_club_db.sql` → Convert to `sports_club_db_pg.sql`
- `include/db_conn.php` → PostgreSQL connection
- `connect.php` → PostgreSQL connection (or remove if unused)

### PHP Files (30+ files)
- Authentication: `secure_login.php`, `change_s_pwd.php`
- Dashboard: `dashboard/admin/index.php`
- Member Management: `dashboard/admin/new_entry.php`, `dashboard/admin/new_submit.php`, `dashboard/admin/view_mem.php`, `dashboard/admin/edit_member.php`, `dashboard/admin/edit_mem_submit.php`, `dashboard/admin/del_member.php`, `dashboard/admin/read_member.php`, `dashboard/admin/more-userprofile.php`
- Plan Management: `dashboard/admin/view_plan.php`, `dashboard/admin/new_plan.php`, `dashboard/admin/submit_plan_new.php`, `dashboard/admin/edit_plan.php`, `dashboard/admin/updateplan.php`, `dashboard/admin/del_plan.php`, `dashboard/admin/plandetail.php`
- Payment Management: `dashboard/admin/payments.php`, `dashboard/admin/make_payments.php`, `dashboard/admin/submit_payments.php`
- Reports: `dashboard/admin/income_month.php`, `dashboard/admin/revenue_month.php`, `dashboard/admin/over_month.php`, `dashboard/admin/over_members_month.php`, `dashboard/admin/over_members_year.php`, `dashboard/admin/gen_invoice.php`, `dashboard/admin/table_view.php`, `dashboard/admin/viewall_detail.php`
- Health Status: `dashboard/admin/new_health_status.php`, `dashboard/admin/health_status_entry.php`
- Routines: `dashboard/admin/viewroutine.php`, `dashboard/admin/viewdetailroutine.php`, `dashboard/admin/saveroutine.php`, `dashboard/admin/updateroutine.php`, `dashboard/admin/deleteroutine.php`, `dashboard/admin/editroutine.php`, `dashboard/admin/editdetailroutine.php`

---

## Migration Considerations

### SQL Syntax Differences
- **Backticks**: MySQL uses backticks, PostgreSQL uses double quotes (or none)
- **AUTO_INCREMENT**: MySQL uses `AUTO_INCREMENT`, PostgreSQL uses `SERIAL` or `GENERATED ALWAYS AS IDENTITY`
- **String Concatenation**: MySQL uses `CONCAT()`, PostgreSQL uses `||` or `CONCAT()`
- **Case Sensitivity**: PostgreSQL `LIKE` is case-sensitive, use `ILIKE` for case-insensitive
- **Date Functions**: `NOW()` works in both, but verify timezone handling

### PHP Function Differences
- **Connection**: `mysqli_connect()` → `pg_connect()` or PDO
- **Query Execution**: `mysqli_query()` → `pg_query()` or PDO
- **Fetch Functions**: Similar concepts, different function names
- **Error Handling**: Different error functions

### Best Practices
- Use **PDO with prepared statements** for better security and easier migration
- Test each phase thoroughly before moving to the next
- Keep MySQL database as backup until migration is fully verified
- Document any PostgreSQL-specific workarounds

---

## Connection Parameters Reference

### PostgreSQL Connection Details
- **Host**: localhost
- **Port**: 5432 (default)
- **Database**: sports_club_db
- **Username**: postgres
- **Password**: 1234

### PHP Connection Examples

#### Using pg_connect()
```php
$host = "localhost";
$port = "5432";
$dbname = "sports_club_db";
$user = "postgres";
$password = "1234";

$con = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$con) {
    die("Connection failed: " . pg_last_error());
}
```

#### Using PDO (Recommended)
```php
$host = "localhost";
$port = "5432";
$dbname = "sports_club_db";
$user = "postgres";
$password = "1234";

try {
    $con = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
```

---

## Rollback Plan
- Keep MySQL database intact during migration
- Maintain backup of all PHP files before changes
- Document all changes in version control
- If issues arise, can revert to MySQL by restoring connection files

---

## Quick Reference: Common PostgreSQL Commands

```sql
-- Connect to database
\c sports_club_db

-- List all tables
\dt

-- Describe table structure
\d table_name

-- List all functions
\df

-- List all triggers
\dy

-- Show table data
SELECT * FROM table_name LIMIT 10;

-- Count rows
SELECT COUNT(*) FROM table_name;

-- Exit psql
\q
```

---

## Notes
- Always test in a development environment first
- Keep backups at each phase
- Document any issues encountered and their solutions
- Update this document as you progress through the migration

