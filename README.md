# Student Admission Registration Portal

A professional, multi-step web application for engineering college admissions built with HTML, CSS, JavaScript, jQuery, and PHP. Features include form validation, PDF preview generation, document uploads, and Supabase cloud storage integration.

## Features

✅ **6-Step Form Wizard**
- Personal Information
- Academic Information (10th & 12th)
- Entrance Exam Details (KCET/COMEDK)
- Document Uploads
- Live PDF Preview
- Declaration & Submit

✅ **Modern UI/UX**
- Clean, professional design
- Responsive layout (mobile-friendly)
- Dark mode toggle
- Smooth transitions and animations
- Progress indicator

✅ **Validation**
- Client-side validation (JavaScript/jQuery)
- Server-side validation (PHP)
- File type and size validation
- Email and phone number format validation

✅ **Data Persistence**
- LocalStorage for form data recovery
- Cloud storage via Supabase

✅ **Document Management**
- File uploads with validation (max 2MB)
- Supported formats: JPG, PNG, PDF
- Secure storage in Supabase

## Tech Stack

- **Frontend**: HTML5, CSS3, JavaScript, jQuery
- **Backend**: PHP
- **Database**: Supabase (PostgreSQL)
- **Storage**: Supabase Storage
- **Libraries**:
  - jQuery 3.6.0
  - html2pdf.js (PDF generation)
  - Font Awesome 6.4.0 (icons)

## Project Structure

```
web assignment 2/
├── index.php           # Main application form
├── submit.php          # Form submission handler
├── success.php         # Success confirmation page
├── config.php          # Supabase configuration
├── README.md           # This file
└── assets/
    ├── style.css       # Styling and responsive design
    └── script.js       # Form wizard logic and validation
```

## Setup Instructions

### 1. Prerequisites

- PHP 7.4 or higher
- Web server (Apache/Nginx) or PHP built-in server
- Supabase account (free tier available)

### 2. Supabase Setup

#### Create a Supabase Project
1. Go to [supabase.com](https://supabase.com) and create an account
2. Create a new project
3. Note your project URL and API keys from Settings > API

#### Create Database Table

Run this SQL in your Supabase SQL Editor:

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

-- Create index for faster lookups
CREATE INDEX idx_application_id ON admissions(application_id);
CREATE INDEX idx_created_at ON admissions(created_at);
```

#### Create Storage Bucket

1. Go to Storage section in Supabase dashboard
2. Create a new bucket named `admission_docs`
3. Set the bucket to **Public** (or configure appropriate access policies)

#### Configure Bucket Policies (Optional)

For public access to uploaded files:

```sql
-- Allow public read access
CREATE POLICY "Public Access"
ON storage.objects FOR SELECT
USING ( bucket_id = 'admission_docs' );

-- Allow authenticated uploads
CREATE POLICY "Allow uploads"
ON storage.objects FOR INSERT
WITH CHECK ( bucket_id = 'admission_docs' );
```

### 3. Application Configuration

Edit `config.php` and replace the placeholder values:

```php
// Supabase Project URL
define('SUPABASE_URL', 'https://your-project-id.supabase.co');

// Supabase Anon/Public Key
define('SUPABASE_KEY', 'your-anon-public-key-here');

// Storage Bucket Name
define('SUPABASE_BUCKET', 'admission_docs');

// Database Table Name
define('SUPABASE_TABLE', 'admissions');
```

**Where to find your credentials:**
- Dashboard → Settings → API
- Copy "Project URL" and "anon/public" key

### 4. Running the Application

#### Option 1: PHP Built-in Server (Development)

```bash
cd "C:\Users\santh\OneDrive\Documents\MINI PROJECT\web assignment\web assignment 2"
php -S localhost:8000
```

Then open http://localhost:8000 in your browser.

#### Option 2: Apache/XAMPP/WAMP

1. Copy project folder to your web server directory (e.g., `htdocs`)
2. Access via http://localhost/web-assignment-2

### 5. Testing

1. Open the application in your browser
2. Fill out the form step by step
3. Upload sample documents (ensure they meet requirements)
4. Preview the form before submission
5. Submit and verify data in Supabase dashboard

## Form Fields

### Personal Information
- Full Name
- Date of Birth
- Gender (Male/Female/Other)
- Mobile Number (10 digits)
- Email Address
- Permanent Address
- Parent/Guardian Name
- Parent/Guardian Phone

### Academic Information
**10th Standard:**
- School Name
- Board
- Year of Passing
- Percentage

**12th Standard:**
- College Name
- Board
- Year of Passing
- Percentage

### Entrance Exam
- Exam Type (KCET/COMEDK)
- Rank (conditional based on exam type)

### Documents (Max 2MB each)
- Student Photo (.jpg, .png)
- 10th Mark Card (.jpg, .png, .pdf)
- 12th Mark Card (.jpg, .png, .pdf)
- Transfer Certificate (.jpg, .png, .pdf)

## Validation Rules

| Field | Rule |
|-------|------|
| Mobile Number | Exactly 10 digits |
| Email | Valid email format |
| Percentage | 0-100 range |
| File Size | Max 2MB per file |
| File Types | .jpg, .png, .pdf |
| Rank | Required if exam selected |
| Declaration | Must be checked |

## Features in Detail

### Form Wizard Navigation
- Step-by-step form completion
- Progress indicator showing current step
- Previous/Next buttons with validation
- Smooth fade transitions between steps

### LocalStorage Persistence
- Form data automatically saved as you type
- Data restored on page reload
- Cleared after successful submission

### PDF Preview
- Dynamic preview generation before submission
- Download preview as PDF option
- Formatted with all entered information

### Dark Mode
- Toggle between light and dark themes
- Preference saved in localStorage
- Smooth theme transitions

### Responsive Design
- Mobile-first approach
- Adapts to all screen sizes
- Touch-friendly interface

## Troubleshooting

### Common Issues

**1. File Upload Fails**
- Check PHP `upload_max_filesize` and `post_max_size` in php.ini
- Verify Supabase bucket permissions
- Ensure files are under 2MB

**2. Database Insert Fails**
- Verify Supabase credentials in config.php
- Check table schema matches code
- Review Supabase logs for errors

**3. CORS Errors**
- Ensure Supabase API key is correct
- Check if bucket is properly configured
- Verify URL format in config.php

**4. PHP cURL Not Working**
- Enable cURL extension in php.ini: `extension=curl`
- Restart web server after changes

## Security Notes

- Input sanitization implemented on server-side
- File type validation prevents malicious uploads
- SQL injection protection via prepared statements
- XSS protection through htmlspecialchars()
- Consider adding CSRF tokens for production

## Optional Enhancements

If time permits, consider adding:
- Email confirmation via SMTP
- Admin panel to view submissions
- Application status tracking
- OTP verification for mobile
- Payment gateway integration

## Browser Support

- Chrome (recommended)
- Firefox
- Safari
- Edge
- Mobile browsers (iOS Safari, Chrome Mobile)

## License

This project is created for educational purposes.

## Support

For issues or questions:
1. Check this README
2. Review Supabase documentation
3. Inspect browser console for errors
4. Check PHP error logs

## Credits

Built with:
- jQuery
- html2pdf.js
- Font Awesome
- Supabase
