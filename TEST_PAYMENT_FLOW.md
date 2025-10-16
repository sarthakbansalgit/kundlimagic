# 🧪 Test Payment Flow Guide

## Quick Test Steps

### 1. Open Generate Kundli Page
```
Visit: http://localhost:8000/generate-kundli
```

### 2. Fill the Form
Click the **"Fill Form with Test Data"** button to auto-fill:
- Name
- Phone (10 digits)
- Email (optional)
- Gender
- Date of Birth
- Time of Birth
- Place of Birth (type and select from dropdown)
- Language
- Kundli Type

**IMPORTANT:** Make sure to select a place from the autocomplete dropdown!

### 3. Click "Generate Kundli"
You should see:
1. ✅ "Processing payment request..." message
2. ✅ Button changes to "Processing..."
3. ✅ After 1-2 seconds, redirects to PhonePe payment page

### 4. Complete Payment (Test Mode)
- PhonePe sandbox will show test payment screen
- Complete the test payment
- You'll be redirected back

### 5. After Payment
- ✅ User account created automatically (or logged into existing)
- ✅ Kundli generated with PDF
- ✅ Redirected to dashboard showing your kundli

## Troubleshooting

### Nothing happens when clicking Generate Kundli?

**Check 1: Browser Console**
```
Press F12 → Console tab
Look for errors or messages
```

**Check 2: Network Tab**
```
Press F12 → Network tab
Click Generate Kundli
Look for the POST request to "payment/generateKundli"
Check the response
```

**Check 3: Check Logs**
```bash
tail -f application/logs/log-*.php
```

### Common Issues:

#### Issue 1: "Please select a valid place of birth"
**Solution:** Type a city name and SELECT from the dropdown (don't just type)

#### Issue 2: Form validation error
**Solution:** Make sure all fields are filled, especially:
- Phone must be 10 digits
- Email must be valid format (or leave empty)
- Place must be selected from dropdown

#### Issue 3: Payment service error
**Solution:** Check PhonePe credentials in `application/config/config.php`

### Debug Mode

To see detailed errors, edit `index.php`:
```php
// Line 67
define('ENVIRONMENT', 'development');
```

Then check browser console and application logs for detailed error messages.

## Expected Behavior

### Step 1: Form Submission
- ✅ Button disables
- ✅ Shows "Processing..." message
- ✅ AJAX POST to `/payment/generateKundli`

### Step 2: Server Processing
- ✅ Validates all fields
- ✅ Checks coordinates (lat/long)
- ✅ Stores data in session
- ✅ Calls PhonePe API
- ✅ Returns redirect URL

### Step 3: PhonePe Redirect
- ✅ Browser redirects to PhonePe
- ✅ Shows payment screen
- ✅ Test payment completes

### Step 4: Return to Site
- ✅ PhonePe redirects to `/payment/payment_confirmation`
- ✅ Verifies payment status
- ✅ Creates/finds user account
- ✅ Auto-logs in user
- ✅ Generates kundli and PDF
- ✅ Redirects to `/dashboard/kundli/{id}`

### Step 5: Dashboard
- ✅ Shows kundli details
- ✅ Shows PDF download/view buttons
- ✅ Can generate more kundlis

## Manual Test Data

If auto-fill doesn't work, use this:

```
Name: Test User
Phone: 9876543210
Email: test@example.com (or leave empty)
Gender: Male
DOB: 1990-01-15
TOB: 10:30
Place: Type "Mumbai" and select from dropdown
Language: English
Kundli Type: Basic
```

## Success Indicators

✅ **Form submits successfully**
- Button shows "Processing..."
- No error messages in red
- Redirects to PhonePe

✅ **Payment works**
- PhonePe page loads
- Can complete test payment
- Redirects back to your site

✅ **Kundli generated**
- Dashboard shows your kundli
- PDF buttons appear
- Can view/download PDF

## Still Not Working?

1. Check application logs: `application/logs/`
2. Check browser console: Press F12
3. Check network requests: F12 → Network tab
4. Verify PhonePe credentials are correct
5. Make sure MongoDB data directory is writable: `chmod 755 application/data`
6. Make sure uploads directory exists: `chmod 755 uploads/kundlis`

## Contact

If issues persist:
- Email: help@kundlimagic.com
- Include: Order ID, error message, browser console output
