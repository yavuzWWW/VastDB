# VastDB — English Installation & Usage Guide

## 1. What this package is

VastDB is a small file-based database written in PHP.

It does **not** need MySQL, MariaDB, PostgreSQL, Composer, Node.js, or another database server.

The database is stored inside the `data/` folder.

This copy is clean:

- no user table
- no server table
- no logs table
- no test table
- no old rows
- `data/tables.vast` is empty
- the PHP/JS/.htaccess code was not changed

The file `data/info.vast` is kept because the existing code needs it for admin authentication.

---

# 2. The easiest installation

Think of it like this:

1. Put the `vastdb` folder on your web server.
2. Make sure PHP works.
3. Make sure Apache allows `.htaccess` and URL rewriting.
4. Make sure PHP can write inside `vastdb/data/`.
5. Set/know your admin password and auth key.
6. Open the admin URL.
7. Create your first table.

That is the whole basic installation.

---

# 3. Requirements

## Required

- PHP 7.0 or newer. PHP 8.x is recommended.
- Apache web server for the included `.htaccess` rules.
- PHP permission to create, read, edit, and delete files/folders inside `data/`.
- Apache `mod_rewrite` enabled.
- `AllowOverride` must allow the `.htaccess` file to work.

## Not required

You do NOT need:

- MySQL
- MariaDB
- phpMyAdmin
- PostgreSQL
- Redis
- Composer
- npm
- Node.js

---

# 4. Folder structure

Important files:

```text
vastdb/
├── admin.php                 Admin login page
├── admin_commands.php        Development/example commands
├── db.php                    Main VastDB database functions
├── functions.php             Helper functions
├── .htaccess                 Apache access/rewrite rules
│
├── data/
│   ├── info.vast             Admin username/password hash/auth-key hash
│   └── tables.vast           List of tables; empty on this clean install
│
├── handler/
│   ├── db_handler.php        Admin create/insert/update actions
│   ├── delete_handler.php    Admin delete actions
│   └── .htaccess
│
├── pages/
│   ├── login.php
│   ├── dashboard-content.php
│   └── tables.php
│
└── scripts/lib/htmx.js
```

When you create a table, VastDB creates a folder for it inside `data/`.

Example:

```text
data/users/
├── meta.vast
├── next_index.vast
├── username/data.vastdb
└── email/data.vastdb
```

---

# 5. Install with XAMPP on Windows

This is the simplest local setup.

## Step 1 — Start XAMPP

Open XAMPP and start **Apache**.

You do not need to start MySQL for VastDB.

## Step 2 — Copy the folder

Put the complete `vastdb` folder inside your XAMPP web directory.

Usually:

```text
C:\xampp\htdocs\vastdb
```

## Step 3 — Make sure Apache rewrite is enabled

XAMPP normally already has `mod_rewrite` available.

The project includes `.htaccess`, so Apache must be allowed to read it.

## Step 4 — Configure your admin credentials

Open:

```text
vastdb/data/info.vast
```

The structure looks like this:

```json
{
  "dbInfo": {
    "ver": "0.1 early development",
    "Author": "Vast Hosting - Yavuz Semih Dogandemir"
  },
  "Admin": {
    "username": "YOUR_USERNAME",
    "password": "PASSWORD_HASH_HERE"
  },
  "Key": "AUTH_KEY_HASH_HERE"
}
```

The password and key are hashes. Do NOT put the normal plain password in those hash fields.

To create a password hash, run:

```bash
php -r "echo password_hash('YOUR_PASSWORD', PASSWORD_DEFAULT), PHP_EOL;"
```

To create an auth-key hash, run:

```bash
php -r "echo password_hash('YOUR_SECRET_AUTH_KEY', PASSWORD_DEFAULT), PHP_EOL;"
```

Copy each generated hash into the correct field in `info.vast`.

Example idea only:

```text
Admin username: admin
Admin password: MyStrongPassword123!
Auth key: A-Very-Long-Secret-Key-123456
```

Do not use those example values on a real server.

## Step 5 — Open VastDB

If the folder is directly inside `htdocs`, open:

```text
http://localhost/vastdb/admin?auth_key=YOUR_SECRET_AUTH_KEY
```

The value after `auth_key=` is the **plain auth key** you chose. VastDB compares it with the hash stored in `info.vast`.

Then enter the admin username and normal admin password on the login screen.

---

# 6. Install on Apache/Linux

Example web location:

```text
/var/www/html/vastdb
```

Copy the folder there.

Example:

```bash
sudo cp -R vastdb /var/www/html/vastdb
```

Make sure Apache and PHP are installed and `mod_rewrite` is enabled.

On Debian/Ubuntu this is commonly:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Your Apache site configuration must allow `.htaccess` overrides for this directory, for example with `AllowOverride All`.

VastDB must be able to write into the `data/` directory. Give the Apache/PHP user the required write permission according to your server setup.

Do not blindly use `chmod 777` on a public server.

Then configure `data/info.vast` and open:

```text
https://your-domain.example/vastdb/admin?auth_key=YOUR_SECRET_AUTH_KEY
```

### Important Linux limitation in the current code

`deleteTable()` and `deleteColumn()` currently use the Windows command:

```text
rmdir /s /q
```

Because you requested **no code changes**, this was not changed.

Result: creating tables, inserting data, reading data, searching, and updating can work on Linux, but deleting an entire table or column through those functions may fail on Linux/macOS until that code is changed in a future version.

---

# 7. First test after installation

## Test 1 — Admin panel opens

Open:

```text
/vastdb/admin?auth_key=YOUR_AUTH_KEY
```

You should see the VastDB admin login page.

## Test 2 — Login

Enter the username and password configured in `data/info.vast`.

## Test 3 — Create a table

In **New Table** enter:

```text
Table name:
users
```

```text
Columns:
username,email,password_hash
```

Click **Create Table**.

VastDB should create:

```text
data/users/
```

and add `users` to:

```text
data/tables.vast
```

## Test 4 — Insert a row

Choose the `users` table and enter:

```text
username=testuser
email=test@example.com
password_hash=test
```

Click **Insert**.

The row should appear in the table view.

---

# 8. Using VastDB from PHP

First load VastDB:

```php
require_once __DIR__ . '/vastdb/db.php';
```

Adjust the path if your application is in another folder.

---

# 9. Create a table

```php
newTable('users', 'username,email,password_hash');
```

This creates:

- table: `users`
- column: `username`
- column: `email`
- column: `password_hash`

Do this once. Calling it again with the same table name stops with a VastDB error because the table already exists.

---

# 10. Check if a table exists

```php
if (tableExists('users')) {
    echo 'Table exists';
}
```

---

# 11. Get all tables

```php
$tables = getTables();
```

On this clean installation, before creating anything, it returns an empty list.

---

# 12. Add a column

```php
newColumn('users', 'credits');
```

If old rows already exist, VastDB fills the new column for those existing row positions with empty values.

---

# 13. Check if a column exists

```php
if (columnExists('users', 'email')) {
    echo 'Column exists';
}
```

---

# 14. Get table columns

```php
$columns = getColumns('users');
```

Example result:

```php
[
    'username',
    'email',
    'password_hash'
]
```

---

# 15. Insert a row

```php
insert('users', [
    'username' => 'yavuz',
    'email' => 'test@example.com',
    'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
]);
```

The keys are column names.

The values are the data saved in those columns.

If a table has extra columns that you do not include in the insert, VastDB stores an empty value for those missing columns.

---

# 16. Read one row by ID

```php
$user = pull('users', 0);
```

Example result:

```php
[
    'username' => 'yavuz',
    'email' => 'test@example.com',
    'password_hash' => '...'
]
```

IDs start from `0` in a new table.

---

# 17. Update one value

```php
update('users', 'email', 0, 'new@example.com');
```

Meaning:

```text
users = table
email = column
0 = row ID
new@example.com = new value
```

---

# 18. Search for one match

Strict search:

```php
$user = search('users', 'username', 'yavuz');
```

If found, it returns the full row.

If nothing is found, it returns:

```php
false
```

To return only the row ID:

```php
$id = search('users', 'username', 'yavuz', 'id');
```

---

# 19. Search for all matches

```php
$users = searchAll('users', 'status', 'active');
```

To get only matching IDs:

```php
$ids = searchAll('users', 'status', 'active', 'id');
```

---

# 20. Loose vs strict search

Default search is strict.

Strict:

```php
search('users', 'credits', 10, 'rowData', 'strict');
```

Loose:

```php
search('users', 'credits', '10', 'rowData', 'loose');
```

In loose mode PHP can treat values such as the number `10` and string `'10'` as equal.

---

# 21. Read a complete column

```php
$usernames = pullColumn('users', 'username');
```

This returns the saved username values from that column.

---

# 22. Get the last value in a column

```php
$lastUsername = getLast('users', 'username');
```

Use this only when that column contains at least one value.

---

# 23. Delete one row

```php
deleteID('users', 0);
```

Important: deleting a row does not reset or reuse the table's next ID counter. Gaps in IDs are normal.

Example:

```text
0
1
2
```

Delete ID `1`:

```text
0
2
```

The next inserted row can still get the next new index instead of reusing `1`.

---

# 24. Delete a column

```php
deleteColumn('users', 'email');
```

Current limitation: the folder-removal command inside this function is Windows-specific.

---

# 25. Delete a table

```php
deleteTable('users');
```

Current limitation: the folder-removal command inside this function is Windows-specific.

---

# 26. How the data is stored

For a table called `users` with columns `username` and `email`, VastDB stores data like this:

```text
data/
└── users/
    ├── meta.vast
    ├── next_index.vast
    ├── username/
    │   └── data.vastdb
    └── email/
        └── data.vastdb
```

`meta.vast` remembers the column names.

`next_index.vast` remembers the next row index.

Each `data.vastdb` file contains JSON data for one column.

`data/tables.vast` contains the names of all tables.

---

# 27. Admin panel functions

The current admin panel can:

- create a table
- add a column
- insert a row
- update a value
- delete a table
- delete a column
- delete a row
- display tables and rows

The dashboard refreshes its table view automatically with HTMX.

---

# 28. Admin authentication: two separate secrets

There are two steps.

## Step A — Auth key in URL

Example:

```text
/admin?auth_key=MY_SECRET_KEY
```

That plain key is checked against:

```text
data/info.vast -> Key
```

## Step B — Admin login

Then the admin login checks:

```text
data/info.vast -> Admin -> username
data/info.vast -> Admin -> password
```

The password field contains a PHP password hash.

---

# 29. Changing the admin password safely

Generate a new hash:

```bash
php -r "echo password_hash('NEW_PASSWORD', PASSWORD_DEFAULT), PHP_EOL;"
```

Copy the generated value into:

```text
data/info.vast
```

under:

```text
Admin -> password
```

Never try to decrypt the old password hash. Password hashes are meant to be verified, not decrypted.

---

# 30. Changing the auth key safely

Generate a new hash:

```bash
php -r "echo password_hash('NEW_LONG_SECRET_KEY', PASSWORD_DEFAULT), PHP_EOL;"
```

Put that hash in:

```text
data/info.vast -> Key
```

Then open the admin panel using the normal/plain value:

```text
/admin?auth_key=NEW_LONG_SECRET_KEY
```

---

# 31. File permissions

VastDB writes directly to files.

PHP therefore needs write access to:

```text
vastdb/data/
```

and everything VastDB creates inside it.

If PHP cannot write there, creating tables, columns, or rows will fail.

On a production server, give write access only to the required web-server/PHP user. Do not make the whole project publicly writable.

---

# 32. Backups

Backing up VastDB is simple because the database is files.

To back up all database content, copy:

```text
vastdb/data/
```

For a complete application backup, copy the whole:

```text
vastdb/
```

Before restoring a backup, stop writes to the database so files do not change in the middle of the copy.

---

# 33. Resetting VastDB to empty again

To manually reset the database without changing code:

1. Keep `data/info.vast`.
2. Delete every table folder inside `data/`.
3. Empty `data/tables.vast` so it contains zero table names.
4. Do not delete the `data/` folder itself.

After that, `getTables()` should return no tables and you can create new ones.

---

# 34. Important security notes

VastDB is currently marked in its own configuration as an **early development** version.

For a public/production system, be aware of the current design:

- database security depends heavily on filesystem and web-server configuration
- the admin auth key is supplied in the URL query string
- URL query strings can appear in browser history and server/proxy logs
- the admin dashboard handlers should not be exposed beyond what is necessary
- always use HTTPS on a public server
- use a long random auth key and a strong admin password
- keep backups
- do not expose the `data/` directory directly
- test permissions carefully

The included root `.htaccess` currently blocks direct access to most files and allows only the admin-related routes it needs when running under Apache.

---

# 35. Known current-code limitations — not changed in this clean package

Because this package was requested with **absolutely no code changes**, these were documented instead of modified:

1. `deleteTable()` uses `rmdir /s /q`, a Windows command.
2. `deleteColumn()` uses `rmdir /s /q`, a Windows command.
3. On Linux/macOS, those complete table/column deletion operations can fail.
4. `admin.php` calls `redirect()` when the auth key is wrong, but no `redirect()` function exists in the supplied code. A wrong auth key can therefore cause an error instead of a clean redirect.
5. Row IDs are indexes and are not automatically compacted/reused after deletion.
6. VastDB does not provide transactions or a database-server-style concurrency system in this version.

These are descriptions of the supplied code, not modifications.

---

# 36. Tiny cheat sheet

```php
require_once __DIR__ . '/vastdb/db.php';

newTable('users', 'username,email');

insert('users', [
    'username' => 'alice',
    'email' => 'alice@example.com'
]);

$row = pull('users', 0);

$id = search('users', 'username', 'alice', 'id');

update('users', 'email', 0, 'new@example.com');

$all = pullColumn('users', 'username');
```

That is enough to start using VastDB.
