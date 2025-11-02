# 🚀 Deployment Guide

## ⚠️ IMPORTANT: Cannot Deploy to Vercel

This is a **PHP project** with file uploads. Vercel only supports Node.js/Next.js.

## ✅ Recommended Free Hosting (with PHP support)

### 1. InfinityFree (Best for Students)
- 🆓 100% Free forever
- ✅ PHP 8.x support
- ✅ 5GB storage
- ✅ No credit card needed
- 🔗 https://www.infinityfree.com

### 2. 000webhost
- 🆓 Free tier
- ✅ PHP support
- 🔗 https://www.000webhost.com

### 3. Railway.app
- 🆓 $5/month free credit
- ✅ Auto-deploy from GitHub
- 🔗 https://railway.app

---

## 📦 Pre-Deployment Setup

### Step 1: Use Production Files

**Replace config.php:**
```bash
copy config_production.php config.php
```

**Replace submit.php:**
```bash
copy submit_production.php submit.php
```

### Step 2: Verify Supabase Setup

In Supabase dashboard, confirm:
- ✅ Table `admissions` exists
- ✅ Bucket `admission_docs` exists and is **Public**
- ✅ Your credentials in config.php are correct

---

## 🌐 Deployment Steps (InfinityFree)

1. **Sign up**: https://www.infinityfree.com
2. **Create website**: Choose subdomain
3. **Upload files**: 
   - Open File Manager
   - Go to `htdocs` folder
   - Upload all project files
4. **Test**: Visit your URL

---

## ✅ Files to Upload

```
htdocs/
├── index.php
├── submit.php
├── success.php
├── config.php
└── assets/
    ├── style.css
    └── script.js
```

---

## 🔒 Production Checklist

- [ ] Using `config_production.php` as `config.php`
- [ ] Using `submit_production.php` as `submit.php`
- [ ] Supabase table exists
- [ ] Supabase bucket is Public
- [ ] Tested form submission
- [ ] Checked data in Supabase

---

## 🐛 Common Issues

**"cURL not found"** → Production config has fallback

**"File upload fails"** → Check bucket is Public in Supabase

**"Slow upload"** → Normal for 2MB files on free hosting

---

**Your project is ready for deployment!** 🎉
