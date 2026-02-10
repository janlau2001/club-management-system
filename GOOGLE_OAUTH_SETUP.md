# Google OAuth Setup Guide

This guide will walk you through setting up Google OAuth authentication for your Laravel application.

## Prerequisites

- A Google account
- Access to Google Cloud Console
- Your Laravel application running locally or on a server

## Step 1: Create a Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Click on the project dropdown at the top of the page
3. Click "New Project"
4. Enter a project name (e.g., "Club Management System")
5. Click "Create"
6. Wait for the project to be created and select it

## Step 2: Enable Google+ API (Optional but Recommended)

1. In your Google Cloud Console, go to "APIs & Services" > "Library"
2. Search for "Google+ API"
3. Click on it and press "Enable"
4. This allows you to access additional user profile information

## Step 3: Configure OAuth Consent Screen

1. Go to "APIs & Services" > "OAuth consent screen"
2. Select **User Type**:
   - **External**: For public Gmail accounts (recommended for this application)
   - **Internal**: Only if you have a Google Workspace organization
3. Click "Create"

### Fill in the OAuth Consent Screen:

**App Information:**
- **App name**: Club Management System (or your preferred name)
- **User support email**: Your email address
- **App logo**: (Optional) Upload your app logo

**App Domain:**
- **Application home page**: Your application URL (e.g., `http://localhost:8000`)
- **Application privacy policy link**: (Optional but recommended)
- **Application terms of service link**: (Optional but recommended)

**Authorized Domains:**
- Add your domain if deploying to production (e.g., `yourapp.com`)
- For localhost testing, you can skip this

**Developer Contact Information:**
- Add your email address

4. Click "Save and Continue"

### Scopes:

1. Click "Add or Remove Scopes"
2. Select the following scopes:
   - `userinfo.email`
   - `userinfo.profile`
   - `openid`
3. Click "Update"
4. Click "Save and Continue"

### Test Users (for Testing Mode):

1. Click "Add Users"
2. Add Gmail addresses that you want to allow during testing (up to 100 users)
3. Click "Save and Continue"

4. Review the summary and click "Back to Dashboard"

## Step 4: Create OAuth Credentials

1. Go to "APIs & Services" > "Credentials"
2. Click "Create Credentials" > "OAuth client ID"
3. Select **Application type**: "Web application"
4. Enter a **Name**: "Club Management OAuth Client" (or any name)

### Authorized JavaScript Origins:

Add your application URLs:
```
http://localhost:8000
http://127.0.0.1:8000
```

For production, add:
```
https://yourdomain.com
```

### Authorized Redirect URIs:

Add your callback URLs:
```
http://localhost:8000/club/auth/google/callback
http://127.0.0.1:8000/club/auth/google/callback
```

For production, add:
```
https://yourdomain.com/club/auth/google/callback
```

5. Click "Create"
6. A dialog will appear with your **Client ID** and **Client Secret**
7. **Important**: Copy both values immediately

## Step 5: Configure Your Laravel Application

1. Open your `.env` file
2. Add the following variables:

```env
GOOGLE_CLIENT_ID=your-client-id-here.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret-here
GOOGLE_REDIRECT_URI="${APP_URL}/club/auth/google/callback"
```

3. Replace `your-client-id-here` and `your-client-secret-here` with the values from Step 4

### Example .env configuration:

```env
APP_URL=http://localhost:8000

GOOGLE_CLIENT_ID=123456789-abcdefghijklmnop.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-AbCdEfGhIjKlMnOpQrStUvWx
GOOGLE_REDIRECT_URI="${APP_URL}/club/auth/google/callback"
```

## Step 6: Verify Configuration

1. Ensure `config/services.php` has the Google configuration:

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

2. Clear your configuration cache:

```bash
php artisan config:clear
php artisan cache:clear
```

## Step 7: Test the OAuth Flow

1. Navigate to your registration page: `http://localhost:8000/club/register`
2. Click the "Sign in with Google" button
3. You should be redirected to Google's login page
4. Sign in with a Gmail account (must be added as a test user if in Testing mode)
5. Grant the requested permissions
6. You should be redirected back to your application with the user logged in

## Publishing Your OAuth App (Production)

When ready to go live:

1. Go to "OAuth consent screen" in Google Cloud Console
2. Click "Publish App"
3. If your app requests sensitive or restricted scopes, you'll need to submit it for Google's verification
4. The verification process can take 4-6 weeks

### For Apps Using Only Basic Scopes:

If you only use `.../auth/userinfo.email`, `.../auth/userinfo.profile`, and `openid` (which is our case), your app can remain in "Testing" mode with up to 100 test users, or you can publish it immediately without verification.

## Troubleshooting

### Error: "redirect_uri_mismatch"

**Cause**: The redirect URI in your request doesn't match any authorized redirect URIs.

**Solution**:
1. Go to Google Cloud Console > Credentials
2. Click on your OAuth 2.0 Client ID
3. Verify the redirect URI exactly matches what's in your `.env` file
4. Common issues:
   - Trailing slash difference (`/callback` vs `/callback/`)
   - HTTP vs HTTPS
   - localhost vs 127.0.0.1
   - Port number mismatch

### Error: "Access blocked: This app's request is invalid"

**Cause**: OAuth consent screen not configured properly.

**Solution**:
1. Complete the OAuth consent screen configuration
2. Ensure you've added test users if in Testing mode
3. Verify all required fields are filled

### Error: "This app is blocked"

**Cause**: The Google account trying to sign in is not added as a test user (in Testing mode).

**Solution**:
1. Go to "OAuth consent screen" > "Test users"
2. Add the Gmail address you're trying to use
3. Or publish the app if ready for production

### Gmail SMTP Configuration (for Verification Emails)

If you want to send verification emails via Gmail:

1. Enable 2-factor authentication on your Gmail account
2. Generate an App Password:
   - Go to Google Account > Security > 2-Step Verification > App passwords
   - Generate a password for "Mail"
3. Update your `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Security Best Practices

1. **Never commit credentials**: Ensure `.env` is in your `.gitignore`
2. **Use environment variables**: Never hardcode credentials in your code
3. **Rotate secrets**: If credentials are exposed, regenerate them immediately in Google Cloud Console
4. **Restrict redirect URIs**: Only add URIs you actually use
5. **Monitor usage**: Check the Google Cloud Console regularly for unusual activity

## Additional Resources

- [Google OAuth 2.0 Documentation](https://developers.google.com/identity/protocols/oauth2)
- [Laravel Socialite Documentation](https://laravel.com/docs/socialite)
- [Google Cloud Console](https://console.cloud.google.com/)

## Support

If you encounter issues:
1. Check the error message in your Laravel logs (`storage/logs/laravel.log`)
2. Verify all configuration values are correct
3. Clear cache: `php artisan config:clear && php artisan cache:clear`
4. Check Google Cloud Console for API errors or quota limits
