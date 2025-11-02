# ✅ Pre-Deployment Checklist

## Before Pushing to GitHub

### 1. Switch to Production Files
```bash
copy config_production.php config.php
copy submit_production.php submit.php
```

### 2. Verify Supabase Setup
Go to https://supabase.com and check:
- [ ] Table `admissions` exists (with correct columns)
- [ ] Storage bucket `admission_docs` exists
- [ ] Bucket is set to **Public**
- [ ] Your API credentials are in `config.php`

### 3. Test Locally (Optional)
```bash
php -S localhost:8000
```
- [ ] Form loads
- [ ] Can submit data
- [ ] Files upload to Supabase
- [ ] Data saves to database

---

## GitHub Setup

### 4. Create GitHub Repository
1. Go to https://github.com/new
2. Name: `student-admission-portal`
3. Make it **Public** (for free Render hosting)
4. Don't initialize with README

### 5. Push Your Code
```bash
cd "C:\Users\santh\OneDrive\Documents\MINI PROJECT\web assignment\web assignment 2"

git init
git add .
git commit -m "Initial commit - Student Admission Portal"

git remote add origin https://github.com/YOUR_USERNAME/student-admission-portal.git
git branch -M main
git push -u origin main
```

---

## Render Deployment

### 6. Deploy to Render
1. Go to https://render.com
2. Sign up with GitHub
3. New + → Web Service
4. Connect your repo
5. Configure:
   - **Environment**: PHP
   - **Build Command**: `bash render-build.sh`
   - **Start Command**: `php -S 0.0.0.0:$PORT`
6. Click "Create Web Service"

### 7. Wait for Deployment
- Takes 2-3 minutes
- Watch the logs
- Get your URL: `https://your-app.onrender.com`

---

## Post-Deployment Testing

### 8. Test Live Application
- [ ] Visit your Render URL
- [ ] Form loads without errors
- [ ] Fill all 6 steps
- [ ] Upload 4 files (under 2MB each)
- [ ] Submit form
- [ ] Redirected to success page
- [ ] See Application ID

### 9. Verify in Supabase
- [ ] Go to Supabase → Table Editor → `admissions`
- [ ] See your new entry
- [ ] Go to Storage → `admission_docs`
- [ ] See your uploaded files

---

## 🎉 You're Done!

Your app is live at: `https://your-app.onrender.com`

**Share it:**
- Add to portfolio
- Share with classmates
- Include in resume

**Keep in mind:**
- Render free tier sleeps after 15 min
- First load takes 30-60 seconds
- 750 free hours/month

---

## 🔄 Making Updates

When you update code:
```bash
git add .
git commit -m "Description of changes"
git push origin main
```

Render auto-deploys in 2-3 minutes!

---

**Need help?** Check `RENDER_DEPLOY.md` for troubleshooting.
