# Masjid Management System

A small web application for managing the records of a local mosque, built with **PHP** and **PostgreSQL**.
It was made for the course **Database Management Systems Lab (CSE 2367)** at Northern University Bangladesh.

![Masjid Management System banner](images/pic1.jpg)

> To show your own screenshot here, save it as `images/screenshot.png` and add this line:
> `![Screenshot](images/screenshot.png)`

## Features

- Add, view, edit and delete **members** (Imam, Muazzin, Committee, Regular)
- Add, view, edit and delete **events** (Iftar, Eid Jamaat, Milad, ...)
- Add, view, edit and delete **donations**, linked to a member and an event
- Drop-down lists, so users choose names instead of typing ID numbers
- Clear messages when a record cannot be deleted because another table still uses it
- PDO prepared statements, so user input cannot change the SQL

> **Current status:** the menu shows only the Members page for now. The Events and Donations links are commented out in `header.php`; remove the comment to show them again.

## Database design

Three related tables:

| Table | Columns | Links to |
|-------|---------|----------|
| `member` | member_id (PK), name, phone, address, role | none |
| `event` | event_id (PK), event_name, event_date, location | none |
| `donation` | donation_id (PK), member_id (FK), event_id (FK), amount, donation_date | member, event |

```
member 1 ──── gives ──── N donation N ──── funded_for ──── 1 event
```

One member can make many donations, and one event can receive many donations.
`amount` must be greater than 0, and `role` must be one of Imam, Muazzin, Committee or Regular.

## Tech stack

| Tool | Purpose |
|------|---------|
| PostgreSQL + pgAdmin | Database and running SQL scripts |
| PHP (PDO, `pdo_pgsql`) | Server-side code |
| HTML + CSS | User interface |
| XAMPP (or PHP built-in server) | Running PHP locally |

## Project structure

```
mosque-php/
├── config.php        # database connection and helper function
├── header.php        # page top: CSS, banner, menu
├── footer.php        # page bottom
├── index.php         # home page
├── members.php       # member CRUD
├── events.php        # event CRUD
├── donations.php     # donation list (JOIN) and form
├── database.sql      # creates the tables and sample data
├── style.css
└── images/
    └── pic1.jpg      # header background
```

## How to run

### 1. Create the database

1. Open **pgAdmin**.
2. Right click **Databases** > **Create** > **Database...**, name it `mosque_db`.
3. Right click `mosque_db` > **Query Tool**.
4. Open `database.sql`, copy all of it, paste it into the Query Tool and press **F5**.

This creates the three tables and inserts the sample data. To start again from zero, run the whole file again (it begins with a `DROP TABLE IF EXISTS` line).

### 2. Set the connection

Open `config.php` and change the values to match your PostgreSQL setup:

```php
$host     = "localhost";
$port     = "5432";
$dbname   = "mosque_db";
$user     = "postgres";
$password = "YOUR_PASSWORD";
```

> Do not commit your real password to GitHub.

### 3. Run PHP

PHP needs the `pdo_pgsql` extension.

**Option A: XAMPP**
1. Put this folder in `C:\xampp\htdocs\mosque`.
2. In `C:\xampp\php\php.ini`, remove the `;` before `extension=pdo_pgsql` and `extension=pgsql`.
3. Restart Apache in the XAMPP Control Panel.
4. Open `http://localhost/mosque/`.

**Option B: PHP built-in server**
```bash
php -S localhost:8000
```
Then open `http://localhost:8000`.

## Sample queries

`queries.sql` has example reports using JOIN and GROUP BY, for example the donation list with member and event names:

```sql
SELECT d.donation_id, m.name AS member_name, e.event_name, d.amount, d.donation_date
FROM donation d
JOIN member m ON d.member_id = m.member_id
JOIN event  e ON d.event_id  = e.event_id
ORDER BY d.donation_id;
```

## Limitations

- No login system, so it is meant for a trusted local computer only.
- Tested with sample data only.
- No expense or duty-schedule records yet (possible future work).

## Authors

- Saimoon Ahmed Adnan
- Md. Fatin Ishrak Rahin
- Md Sakibul Islam

Supervised by **A.S.M Sabiqul Hassan**, Senior Lecturer, Department of CSE, Northern University Bangladesh.
