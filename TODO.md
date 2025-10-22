# Email Verification Fix Plan

## Issue Analysis
- Customer email verification is failing because the verification link route requires authentication
- When users click verify from email (not logged in), the request is blocked by auth middleware
- Database shows `email_verified_at` remains null for customers

## Root Cause
- `verify-email/link` route is inside `Route::middleware('auth')->group()` in `routes/auth.php`
- Standard Laravel verification routes don't require auth for the verification link itself

## Solution Steps
1. Move the `verify-email/link` route outside the auth middleware group in `routes/auth.php`
2. Test the verification process to ensure it works without authentication
3. Verify that admins/superadmins still bypass verification as intended
4. Confirm database updates `email_verified_at` correctly

## Files to Modify
- `routes/auth.php`: Move verification link route outside auth group

## Testing
- Register a new customer account
- Check email for verification link
- Click link without being logged in
- Verify `email_verified_at` is set in database
- Attempt login and confirm success
