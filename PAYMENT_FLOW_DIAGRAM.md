# PhonePe Payment Flow - Visual Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                    USER CLICKS "GENERATE KUNDLI"                    │
│                  (Frontend: appointment.php form)                   │
└────────────────────────────────┬────────────────────────────────────┘
                                 │
                                 │ AJAX POST with form data
                                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│           PAYMENT CONTROLLER - initiate_payment()                   │
│  - Validates form fields (name, phone, dob, tob, pob, etc.)       │
│  - Stores form data in SESSION                                      │
│  - Sets amount: ₹51 (basic) or ₹101 (detailed)                    │
│  - Generates order ID: KM{timestamp}{random}                       │
└────────────────────────────────┬────────────────────────────────────┘
                                 │
                                 │ Internal API call
                                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│           PHONEPE CONTROLLER - create_payment()                     │
│  - Gets OAuth token from PhonePe                                   │
│  - Creates payment order with PhonePe API                          │
│  - Sets redirect URLs (success, cancel, failure)                   │
│  - Returns PhonePe checkout URL                                    │
└────────────────────────────────┬────────────────────────────────────┘
                                 │
                                 │ Returns checkout URL
                                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    REDIRECT TO PHONEPE PAYMENT                      │
│                  (User completes payment)                           │
└────────────────────────────────┬────────────────────────────────────┘
                                 │
                                 │ After payment
                                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│      PHONEPE REDIRECTS TO payment/payment_confirmation              │
│                    with orderId parameter                           │
└────────────────────────────────┬────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│       PAYMENT CONTROLLER - payment_confirmation()                   │
│                                                                     │
│  STEP 1: VALIDATE ORDER                                            │
│  ✓ Check orderId from URL                                         │
│  ✓ Verify orderId matches session                                 │
│  ✓ Get stored form data from session                              │
│                                                                     │
│  STEP 2: VERIFY PAYMENT ⭐ NEW                                     │
│  ✓ Call PhonePe order_status API                                  │
│  ✓ Check payment status = SUCCESS                                 │
│  ✗ If failed → redirect to form with error                        │
│                                                                     │
│  STEP 3: AUTO ACCOUNT CREATION ⭐ NEW                              │
│  ├─ User already logged in?                                        │
│  │  └─ YES → Use existing user_id                                 │
│  │                                                                  │
│  └─ NO → Check if user exists by phone                            │
│     ├─ EXISTS → Auto-login existing user                          │
│     │           Set session (user_id, user_name, user_email)      │
│     │                                                               │
│     └─ NEW → Create new user account                              │
│              - Generate random secure password                      │
│              - Save to MongoDB users collection                     │
│              - Auto-login new user                                  │
│              - Set session (user_id, user_name, user_email)        │
│                                                                     │
│  STEP 4: GENERATE KUNDLI                                           │
│  ✓ Save kundli data to MongoDB kundlis collection                 │
│  ✓ Link to user_id                                                │
│  ✓ Store: name, birth_date, birth_time, birth_place              │
│  ✓ Store: gender, language, kundli_type, coordinates              │
│                                                                     │
│  STEP 5: CLEANUP & REDIRECT                                        │
│  ✓ Clear temporary session data                                   │
│  ✓ Redirect to: dashboard/kundli/{kundli_id}                      │
└────────────────────────────────┬────────────────────────────────────┘
                                 │
                                 │ Route: dashboard/kundli/123
                                 │ Maps to: dashboard/view_kundli/123
                                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│           DASHBOARD CONTROLLER - view_kundli($id)                   │
│  - Gets kundli from database                                       │
│  - Verifies kundli belongs to logged-in user                      │
│  - Loads view with kundli data                                     │
└────────────────────────────────┬────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│              DASHBOARD - VIEW KUNDLI PAGE                           │
│                                                                     │
│  ✨ Displays:                                                      │
│  - Personal info (name, phone)                                     │
│  - Birth details (date, time, place)                               │
│  - Astrological details (gender, language, type, coordinates)     │
│  - PDF download link (if available)                                │
│  - Action buttons (generate new, back to dashboard)                │
│                                                                     │
│  🎨 User can:                                                      │
│  - View all their kundli details                                   │
│  - Download PDF if available                                       │
│  - Generate another kundli                                         │
│  - Return to dashboard to see all their kundlis                    │
└─────────────────────────────────────────────────────────────────────┘


═══════════════════════════════════════════════════════════════════════
                          ERROR HANDLING
═══════════════════════════════════════════════════════════════════════

Payment Failed?
└─ Redirect to: payment/payment_failure?orderId=XXX
   └─ Shows error page with order ID for support

Payment Cancelled?
└─ Redirect to: payment/payment_cancel?orderId=XXX
   └─ Shows cancellation page with retry option

Payment Verification Failed?
└─ Redirect to: generate-kundli with flash error message
   └─ User can retry payment

Form Data Lost?
└─ Show error: "Form data not found"
   └─ User needs to fill form again


═══════════════════════════════════════════════════════════════════════
                        KEY IMPROVEMENTS MADE
═══════════════════════════════════════════════════════════════════════

✅ Payment Verification
   - Now verifies payment status before processing
   - Prevents kundli generation without successful payment

✅ Smart User Management
   - Checks for existing accounts by phone
   - Auto-creates account only for new users
   - Auto-login for both new and returning users

✅ Session Security
   - Validates order ID matches session
   - Clears temporary data after processing
   - Proper session management for logged-in state

✅ Proper Routing
   - Fixed redirect path to match route configuration
   - Consistent URL structure throughout

✅ Error Handling
   - Proper error messages at each step
   - Graceful fallbacks for API failures
   - User-friendly error pages


═══════════════════════════════════════════════════════════════════════
                          DATABASE SCHEMA
═══════════════════════════════════════════════════════════════════════

USERS Collection:
{
  "_id": "generated_id",
  "name": "User Name",
  "email": "user@example.com" (optional),
  "phone": "9876543210",
  "whatsapp": "9876543210",
  "password": "hashed_password",
  "created_at": timestamp,
  "updated_at": timestamp
}

KUNDLIS Collection:
{
  "_id": "generated_id",
  "user_id": "user_id_reference",
  "name": "User Name",
  "birth_date": "2000-01-01",
  "birth_time": "12:30",
  "birth_place": "Mumbai, India",
  "kundli_data": JSON {
    "gender": "male",
    "language": "en",
    "kundli_type": "basic",
    "lat": "19.076090",
    "long": "72.877426"
  },
  "local_pdf_path": "path/to/pdf" (optional),
  "created_at": timestamp
}


═══════════════════════════════════════════════════════════════════════
                         TESTING CHECKLIST
═══════════════════════════════════════════════════════════════════════

□ Test new user flow
  □ Fill form and generate kundli
  □ Complete payment
  □ Check account is created in users collection
  □ Check user is logged in (session)
  □ Check kundli is saved in kundlis collection
  □ Check redirect to dashboard works
  □ Check kundli details display correctly

□ Test returning user flow
  □ Use same phone number
  □ Complete payment
  □ Check existing account is used (no duplicate)
  □ Check user is logged in
  □ Check new kundli is added to existing account
  □ Check dashboard shows all kundlis

□ Test error scenarios
  □ Cancel payment → check redirect to cancel page
  □ Payment failure → check redirect to failure page
  □ Invalid order ID → check error handling
  □ Session expired → check error handling

□ Test payment verification
  □ Mock successful payment → should create account
  □ Mock failed payment → should not create account
  □ API timeout → should show appropriate error
