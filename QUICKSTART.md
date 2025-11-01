# Quick Start Guide

Get your Student Admission Portal running in 5 minutes!

## Step 1: Setup Supabase (2 minutes)

### Create Account & Project
1. Go to https://supabase.com
2. Sign up (free)
3. Click "New Project"
4. Fill in:
   - Name: `admission-portal`
   - Database Password: (create a strong password)
   - Region: (choose closest to you)
5. Wait for project to initialize (~2 minutes)

### Get Your Credentials
1. Go to Settings → API
2. Copy these two values:
   - **Project URL**: `https://xxxxx.supabase.co`
   - **anon public key**: Long string starting with `eyJ...`

## Step 2: Create Database Table (1 minute)

1. In Supabase dashboard, click "SQL Editor" (left sidebar)
2. Click "New Query"
3. Paste this SQL:

```sql
CREATE TABLE admissions (
    id BIGSERIAL PRIMARY KEY,
    application_id VARCHAR(50) UNIQUE NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    dob DATE NOT NULL,
    gender VARCHAR(20) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    email VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    parent_name VARCHAR(255) NOT NULL,
    parent_phone VARCHAR(15) NOT NULL,
    school_10 VARCHAR(255) NOT NULL,
    board_10 VARCHAR(100) NOT NULL,
    year_10 VARCHAR(4) NOT NULL,
    percentage_10 VARCHAR(10) NOT NULL,
    college_12 VARCHAR(255) NOT NULL,
    board_12 VARCHAR(100) NOT NULL,
    year_12 VARCHAR(4) NOT NULL,
    percentage_12 VARCHAR(10) NOT NULL,
    exam_type VARCHAR(50) NOT NULL,
    exam_rank VARCHAR(50) NOT NULL,
    photo_url TEXT,
    markcard_10_url TEXT,
    markcard_12_url TEXT,
    tc_url TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);
```

4. Click "Run" button

## Step 3: Create Storage Bucket (1 minute)

1. Click "Storage" in left sidebar
2. Click "Create a new bucket"
3. Enter name: `admission_docs`
4. Make it **Public**
5. Click "Create bucket"

## Step 4: Configure Application (1 minute)

1. Open `config.php` in your code editor
2. Replace lines 13 and 16 with your credentials:

```php
define('SUPABASE_URL', 'https://YOUR-PROJECT-ID.supabase.co');
define('SUPABASE_KEY', 'YOUR-ANON-KEY-HERE');
```

3. Save the file

## Step 5: Run the Application (30 seconds)

### Method A: PHP Built-in Server (Easiest)
Open PowerShell/Terminal in project folder:

```bash
php -S localhost:8000
```

Then open: http://localhost:8000

### Method B: XAMPP/WAMP
1. Copy project folder to `htdocs/`
2. Start Apache
3. Open: http://localhost/web-assignment-2

## Test It!

1. Fill out the form
2. Upload sample files (under 2MB each)
3. Preview before submit
4. Submit!
5. Check Supabase dashboard → Table Editor → `admissions`
6. You should see your entry!

## Troubleshooting

### "Cannot POST to Supabase"
- Double-check your credentials in `config.php`
- Ensure no extra spaces in URL or key

### "File upload failed"
- Verify bucket name is exactly `admission_docs`
- Ensure bucket is set to Public

### "Table not found"
- Make sure SQL query ran successfully
- Check Table Editor to confirm table exists

## What's Next?

- Check `README.md` for full documentation
- Customize colors in `assets/style.css`
- Add more fields as needed
- Deploy to production server

## Need Help?

1. Review full `README.md`
2. Check Supabase documentation
3. Inspect browser console (F12) for errors

---

**You're all set! 🎉 Start accepting applications!**
