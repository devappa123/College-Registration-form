# 🚀 Deploy to Render.com

## Step-by-Step Guide

### 1️⃣ Prepare Your Code

First, make sure production files are ready:

```bash
# In your project folder
copy config_production.php config.php
copy submit_production.php submit.php
```

### 2️⃣ Push to GitHub

```bash
# Initialize git (if not done)
git init

# Add all files
git add .

# Commit
git commit -m "Initial commit - Student Admission Portal"

# Create repo on GitHub, then:
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git
git branch -M main
git push -u origin main
```

### 3️⃣ Deploy on Render

1. **Go to Render.com**
   - Visit: https://render.com
   - Sign up/Login (use GitHub)

2. **Create New Web Service**
   - Click **"New +"** → **"Web Service"**
   - Click **"Connect a repository"**
   - Select your GitHub repo

3. **Configure Service**
   ```
   Name: student-admission-portal (or your choice)
   Environment: PHP
   Build Command: bash render-build.sh
   Start Command: php -S 0.0.0.0:$PORT
   ```

4. **Click "Create Web Service"**
   - Render will auto-deploy
   - Wait 2-3 minutes

5. **Get Your URL**
   - You'll get a URL like: `https://student-admission-portal.onrender.com`
   - Click to test!

### 4️⃣ Verify Deployment

Visit your URL and test:
- [ ] Form loads correctly
- [ ] Can fill all 6 steps
- [ ] Files upload successfully
- [ ] Form submits
- [ ] Data appears in Supabase
- [ ] Success page shows Application ID

---

## 🔧 Important Notes

### ⚠️ Free Tier Limitations
- **Spins down after 15 min of inactivity**
- First request after sleep takes 30-60 seconds
- 750 hours/month free

### ✅ Supabase Requirements
Make sure in Supabase:
1. Table `admissions` exists
2. Bucket `admission_docs` is **Public**
3. API keys in `config.php` are correct

---

## 🐛 Troubleshooting

### Deploy Fails?
**Check:**
- Build command is correct
- `render-build.sh` has executable permissions
- All files pushed to GitHub

### App Crashes?
**Check Logs:**
1. Go to Render dashboard
2. Click your service
3. Click "Logs" tab
4. Look for errors

### Files Not Uploading?
**Check:**
- Supabase bucket is Public
- Bucket name is exactly `admission_docs`
- PHP has write permissions (Render handles this)

---

## 🎉 Success!

Once deployed, your app will be live at:
```
https://your-app-name.onrender.com
```

Share this URL with:
- Classmates
- Teachers
- On your resume/portfolio

---

## 🔄 Update Your App

After making changes:

```bash
git add .
git commit -m "Updated feature X"
git push origin main
```

Render will **auto-deploy** within 2-3 minutes!

---

## 📊 Monitor Your App

**Render Dashboard:**
- View deployment history
- Check server logs
- Monitor uptime
- See bandwidth usage

**Supabase Dashboard:**
- Check submitted applications
- View uploaded files
- Monitor API usage

---

## 💡 Tips

1. **Keep Free Tier Active**: Visit your app once daily to prevent spin-down
2. **Custom Domain**: You can add your own domain in Render settings
3. **Environment Variables**: Store sensitive data in Render's Environment section
4. **Scaling**: Upgrade to paid plan if you get lots of traffic

---

**Your app is production-ready!** 🚀

Need help? Check Render docs: https://render.com/docs
