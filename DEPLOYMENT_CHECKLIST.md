# 🚀 Deployment Checklist & Optimization Guide

## ✅ Pre-Deployment Checklist

### 1. Environment Configuration

#### A. Set Environment to Production
**File:** `/index.php` (Line 67)

```php
// CHANGE FROM:
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

// TO:
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'production');
```

#### B. Update Base URL
**File:** `/application/config/config.php` (Line 9)

```php
// CHANGE FROM:
$config['base_url'] = 'http://localhost:8000/';

// TO:
$config['base_url'] = 'https://yourdomain.com/';  // Your actual domain
```

#### C. Update PhonePe Configuration (PRODUCTION)
**File:** `/application/config/config.php` (Lines 237-241)

```php
// CHANGE FROM SANDBOX TO PRODUCTION:
$config['client_id'] = 'YOUR_PRODUCTION_CLIENT_ID';
$config['client_secret'] = 'YOUR_PRODUCTION_CLIENT_SECRET';
$config['client_version'] = 'v1';
$config['token_url'] = 'https://api.phonepe.com/apis/hermes/v1/oauth/token';  // Remove 'preprod'
$config['payment_url'] = 'https://api.phonepe.com/apis/hermes/pg/v1/pay';    // Remove 'preprod'
```

⚠️ **IMPORTANT**: Contact PhonePe to get production credentials!

### 2. Security Hardening

#### A. File Permissions
```bash
# Set proper permissions
chmod 755 /workspace
chmod 644 /workspace/index.php
chmod 755 /workspace/application
chmod 644 /workspace/application/config/*.php
chmod 755 /workspace/uploads
chmod 755 /workspace/uploads/kundlis
chmod 755 /workspace/application/data
chmod 644 /workspace/application/data/*.json
```

#### B. Protect Sensitive Files
Create `.htaccess` in `/application/` directory:

```apache
<IfModule authz_core_module>
    Require all denied
</IfModule>
<IfModule !authz_core_module>
    Deny from all
</IfModule>
```

#### C. Hide Index Files
Create `.htaccess` in root directory:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]

# Prevent directory browsing
Options -Indexes

# Deny access to sensitive files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "(composer\.json|composer\.lock|\.gitignore|\.env)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### 3. Remove Test/Debug Files

The following files should be **DELETED** before deployment:

```bash
# Remove test files
rm -f /workspace/debug.php
rm -f /workspace/mongodb_test.php
rm -f /workspace/application/controllers/Payment_test.php
rm -f /workspace/application/controllers/PhonepeClean.php
rm -f /workspace/application/controllers/Test.php
rm -f /workspace/check_config.php

# Remove documentation (optional - or move to separate docs folder)
rm -f /workspace/MONGODB_SETUP_COMPLETE.md
rm -f /workspace/QUICK_REFERENCE.txt
rm -f /workspace/UPLOAD_GUIDE.txt
rm -f /workspace/WEBSITE_FIXED_SUMMARY.md
rm -f /workspace/PHONEPE_PAYMENT_FLOW_FIXED.md
rm -f /workspace/PAYMENT_FLOW_DIAGRAM.md
rm -f /workspace/CHANGES_SUMMARY.md
rm -f /workspace/DEPLOYMENT_CHECKLIST.md
```

### 4. Database Optimization

#### A. Backup Data Files
```bash
# Create backups before deployment
cp /workspace/application/data/users.json /workspace/backups/users_backup_$(date +%Y%m%d).json
cp /workspace/application/data/kundlis.json /workspace/backups/kundlis_backup_$(date +%Y%m%d).json
```

#### B. Data File Location Security
Ensure `/application/data/` directory is NOT web-accessible.

Add to `.htaccess` in `/application/data/`:
```apache
Deny from all
```

### 5. Session Security

**File:** `/application/config/config.php`

```php
// Enable secure session handling
$config['sess_cookie_secure'] = TRUE;      // Force HTTPS
$config['sess_cookie_httponly'] = TRUE;    // Prevent XSS
$config['sess_cookie_samesite'] = 'Strict'; // CSRF protection
```

### 6. Error Logging

**File:** `/application/config/config.php`

```php
// Configure error logging
$config['log_threshold'] = 1;  // Errors only (not debug)
$config['log_path'] = APPPATH . 'logs/';
$config['log_date_format'] = 'Y-m-d H:i:s';
```

Ensure logs directory exists and is writable:
```bash
mkdir -p /workspace/application/logs
chmod 755 /workspace/application/logs
```

### 7. Remove Debug Code

✅ **ALREADY CLEANED:**
- Removed console.log statements from appointment.php
- Removed debug print_r from controllers
- Optimized error messages

### 8. Test Payment Form Features

**Remove Test Data Buttons** (Optional for Production)

**File:** `/application/views/frontend/appointment.php` (Lines 36-39)

```html
<!-- REMOVE THESE TEST BUTTONS IN PRODUCTION -->
<div style="margin-bottom: 20px; text-align: center;">
  <button type="button" id="fillFormBtn" class="as_btn" style="background: #ff9800; margin-right: 10px;">Fill Form with Test Data</button>
  <button type="button" id="clearFormBtn" class="as_btn" style="background: #f44336;">Clear Form</button>
</div>
```

## 🔒 Security Features Implemented

### ✅ Input Validation & Sanitization
- Phone number validation (10 digits only)
- Email validation and sanitization
- Name and place sanitization (HTML tag removal)
- Gender, language, kundli_type whitelist validation

### ✅ Payment Security
- Payment verification before account creation
- Session validation (order ID check)
- HTTPS enforcement for sessions (when configured)
- No sensitive data in logs

### ✅ User Account Security
- Secure password hashing (PHP password_hash with bcrypt)
- Auto-generated random passwords for payment-based signups
- Phone-based user detection (no duplicate accounts)

### ✅ Database Security
- File locking on writes (LOCK_EX)
- Error logging for failed operations
- No SQL injection risk (using JSON files, not SQL)

## ⚡ Performance Optimizations Implemented

### ✅ Code Optimizations
1. **Reduced Database Queries**
   - Optimized user lookup (single query)
   - Efficient sorting and filtering in MongoDB library

2. **Efficient Data Storage**
   - Added sorting support to find() method
   - Added limit support for pagination
   - File locking to prevent race conditions

3. **API Call Optimization**
   - Proper timeout settings (30s timeout, 10s connection timeout)
   - Error handling to prevent hanging requests
   - Session-based data storage to reduce API calls

### ✅ Session Management
- Cleared temporary session data after use
- Efficient session storage
- Proper session cleanup on logout

## 📊 Monitoring & Maintenance

### 1. Monitor Log Files
```bash
# Check error logs regularly
tail -f /workspace/application/logs/log-*.php
```

### 2. Monitor Data Files
```bash
# Check data file sizes
du -h /workspace/application/data/*.json

# Backup data regularly
# Set up a cron job for automated backups
```

### 3. Monitor PhonePe API
- Check PhonePe dashboard for payment success rates
- Monitor failed payments
- Track order IDs for support queries

### 4. User Experience Monitoring
- Test payment flow regularly
- Check dashboard loading times
- Verify kundli generation is working
- Test on mobile devices

## 🚦 Deployment Steps

### Step 1: Pre-Deployment
```bash
# 1. Backup everything
tar -czf kundli_backup_$(date +%Y%m%d).tar.gz /workspace

# 2. Update configurations (see above)
# 3. Remove test files (see list above)
# 4. Test in staging environment
```

### Step 2: Deployment
```bash
# 1. Upload to production server
# 2. Set file permissions (see above)
# 3. Configure SSL certificate
# 4. Update PhonePe credentials
# 5. Test payment flow
```

### Step 3: Post-Deployment
```bash
# 1. Verify SSL is working
curl -I https://yourdomain.com

# 2. Test generate kundli flow
# 3. Check error logs
tail -f application/logs/log-*.php

# 4. Monitor PhonePe payments
# 5. Test user registration/login
```

## 🔍 Testing Checklist

### Functional Testing
- [ ] Homepage loads correctly
- [ ] Generate Kundli form displays
- [ ] Place autocomplete works
- [ ] PhonePe payment redirect works
- [ ] Payment verification works
- [ ] User account auto-creation works
- [ ] Dashboard shows generated kundli
- [ ] Returning user detection works
- [ ] Logout works properly

### Security Testing
- [ ] HTTPS is enforced
- [ ] SQL injection attempts blocked
- [ ] XSS attempts blocked
- [ ] Session hijacking prevented
- [ ] Payment verification cannot be bypassed
- [ ] Sensitive files are not accessible
- [ ] Directory browsing is disabled

### Performance Testing
- [ ] Page load time < 3 seconds
- [ ] API calls complete within timeout
- [ ] No memory leaks
- [ ] Database writes are successful
- [ ] Multiple concurrent users supported

## 📱 PhonePe Production Setup

### 1. Create Production Account
1. Go to PhonePe Business Dashboard
2. Complete KYC verification
3. Get production credentials

### 2. Update Webhook URLs
Ensure these URLs are accessible:
- Success: `https://yourdomain.com/payment/payment_confirmation`
- Cancel: `https://yourdomain.com/payment/payment_cancel`
- Failure: `https://yourdomain.com/payment/payment_failure`

### 3. Test with Real Payments
- Start with small amounts (₹1-10)
- Verify money is received
- Test refund process
- Document order IDs

## ⚠️ Common Issues & Solutions

### Issue 1: Payment Verification Fails
**Solution:** Check PhonePe API credentials and ensure production URLs are correct

### Issue 2: Session Data Lost
**Solution:** Check session configuration and ensure session directory is writable

### Issue 3: Kundli Not Showing
**Solution:** Verify user_id is set in session and kundli is saved to database

### Issue 4: Duplicate User Accounts
**Solution:** Already fixed - phone-based detection prevents duplicates

### Issue 5: File Write Errors
**Solution:** Check directory permissions (755 for dirs, 644 for files)

## 📞 Support Information

For issues or questions:
- Email: help@kundlimagic.com
- Keep Order IDs for all transactions
- Monitor error logs for debugging

## 🎉 Ready for Production!

After completing this checklist, your application is ready for production deployment with:
- ✅ Secure payment processing
- ✅ Optimized performance
- ✅ Proper error handling
- ✅ User-friendly experience
- ✅ Production-ready configuration

**Last Updated:** 2025-10-16
