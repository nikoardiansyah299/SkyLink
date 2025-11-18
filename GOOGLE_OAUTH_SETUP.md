# Google OAuth Setup Guide

## What Was Added

Your SkyLink login page now supports **Google OAuth authentication**! Users can:
- Click the "Login dengan Google" button on the login page
- Authenticate with their Google account
- Automatically create a new account or link to existing email

## Database Changes

- Added `oauth_provider` column (stores "google" or other provider names)
- Added `oauth_id` column (stores unique Google user ID)
- Both columns are nullable to support traditional username/password login

## Configuration Required

To enable Google OAuth, you need to set up Google OAuth credentials:

### 1. Create Google OAuth Credentials

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Go to **APIs & Services** → **Credentials**
4. Click **Create Credentials** → **OAuth client ID**
5. Choose **Web application**
6. Add Authorized redirect URIs:
   - `http://localhost:8000/auth/google/callback` (for local development)
   - `https://yourdomain.com/auth/google/callback` (for production)
7. Copy the **Client ID** and **Client Secret**

### 2. Update .env File

Open `.env` in your project root and update:

```env
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### 3. Test the Login

1. Start your Laravel development server:
   ```bash
   php artisan serve
   ```

2. Visit: `http://localhost:8000/login`

3. Click the **"Login dengan Google"** button

4. Sign in with your Google account

5. You'll be redirected back and logged in!

## How It Works

1. **User clicks Google button** → Redirected to Google OAuth consent screen
2. **User authorizes** → Google redirects back with an authorization code
3. **Exchange code for token** → Backend trades code for access token
4. **Get user info** → Fetch user's email, name from Google
5. **Find or create user**:
   - If Google ID exists → Log them in
   - If email exists → Link Google account to existing user
   - Otherwise → Create new user account
6. **User is logged in** → Session created, redirected to homepage

## Files Modified/Created

- ✅ `app/Http/Controllers/GoogleOAuthController.php` - OAuth logic
- ✅ `app/Models/User.php` - Added oauth fields to fillable/hidden
- ✅ `config/services.php` - Google OAuth configuration
- ✅ `database/migrations/2025_11_18_000000_add_oauth_to_users_table.php` - Database schema
- ✅ `routes/web.php` - OAuth routes added
- ✅ `resources/views/login.blade.php` - Google login button added
- ✅ `.env` - OAuth credentials placeholders

## Routes

- `GET /auth/google` - Initiates Google OAuth flow
- `GET /auth/google/callback` - Handles Google OAuth callback

## Troubleshooting

**Button doesn't work?**
- Check `GOOGLE_CLIENT_ID` is set in `.env`
- Verify redirect URI matches exactly in Google Console

**"Failed to authenticate with Google"?**
- Check `GOOGLE_CLIENT_SECRET` is correct
- Verify your Google app is in production or approved

**New user not created?**
- Check database migrations ran: `php artisan migrate`
- Ensure `oauth_provider` and `oauth_id` columns exist in users table

For more info: [Laravel with Google OAuth Guide](https://developers.google.com/identity/protocols/oauth2)
