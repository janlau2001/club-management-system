# Automatic Registration Cleanup System

## Overview
This system automatically cleans up incomplete club registrations when users abandon or cancel the registration process. This prevents orphaned data in the database and ensures data integrity.

## How It Works

### 1. Scheduled Automatic Cleanup
A scheduled task runs every hour to clean up old incomplete registrations.

**Command:**
```bash
php artisan registrations:cleanup
```

**What it does:**
- Finds officers created more than 24 hours ago
- Checks if `registration_status` is NOT 'completed'
- Deletes associated `ClubRegistrationRequest` records
- Deletes the incomplete `Officer` records

**Scheduling:**
The command is automatically scheduled in `routes/console.php`:
```php
Schedule::command('registrations:cleanup')->hourly();
```

To run the Laravel scheduler, add this to your server's cron:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

**Manual execution:**
```bash
# Clean up registrations older than 24 hours (default)
php artisan registrations:cleanup

# Clean up registrations older than specific hours
php artisan registrations:cleanup --hours=12
```

### 2. Automatic Cleanup on Page Leave
JavaScript code monitors user behavior and triggers cleanup when appropriate.

**Triggers:**
- **Window close/tab close:** Immediately sends cleanup request
- **Navigate away:** Cleanup triggered before leaving page
- **Extended inactivity:** If page is hidden for 5 minutes
- **Browser crash:** `beforeunload` event catches this

**Implementation:**
Located in:
- `resources/views/club/officer-personal-info.blade.php` (Step 2)
- `resources/views/club/club-registration.blade.php` (Step 3)

**How it works:**
```javascript
// Track if form was submitted
let formSubmitted = false;

// On page unload (close/navigate)
window.addEventListener('beforeunload', function(e) {
    if (!formSubmitted) {
        // Send cleanup request using sendBeacon (reliable during unload)
        navigator.sendBeacon(cleanupUrl, formData);
    }
});

// On visibility change (tab switch)
document.addEventListener('visibilitychange', function() {
    if (document.hidden && !formSubmitted) {
        // Wait 5 minutes, then cleanup if still hidden
        setTimeout(() => cleanup(), 300000);
    }
});
```

### 3. Manual Cancel Button
Users can explicitly cancel registration at any point.

**Location:** Available in Step 2 (Personal Info) and Step 3 (Club Registration)

**What happens:**
- Officer record is deleted (if not completed)
- Associated ClubRegistrationRequest is deleted
- Session data is cleared
- User is redirected to login page

## API Endpoints

### Automatic Cleanup Endpoint
```
DELETE /club/registration/cleanup/{officer}
```

**Parameters:**
- `{officer}` - Officer ID

**Response:**
```json
{
    "success": true,
    "message": "Incomplete registration cleaned up."
}
```

**When called:**
- Checks if `registration_status` is NOT 'completed' or 'submitted'
- Deletes ClubRegistrationRequest if exists
- Deletes Officer record

### Manual Cancel Endpoint
```
POST /club/club-registration/{officer}/cancel
```

**Parameters:**
- `{officer}` - Officer ID

**Response:**
- Redirects to login page with success message

## Registration Statuses

The system tracks registration progress using the `registration_status` field:

| Status | Description | Cleanup Eligible? |
|--------|-------------|-------------------|
| `pending_email_verification` | Email sent, awaiting verification | ✅ Yes |
| `email_verified` | Email verified, no personal info yet | ✅ Yes |
| `pending_club_registration` | Personal info saved, awaiting club details | ✅ Yes |
| `submitted` | Registration complete and submitted | ❌ No |
| `completed` | Fully processed and approved | ❌ No |

## Database Schema

### Officers Table Fields
```php
'email' => string
'password' => hashed string
'name' => string|null (nullable)
'department' => string|null (nullable)
'club_status' => string|null (nullable)
'year_level' => string|null (nullable)
'registration_status' => string|null
'email_verified_at' => timestamp|null
'created_at' => timestamp
```

### ClubRegistrationRequest Relationship
```php
Officer->hasOne(ClubRegistrationRequest)
ClubRegistrationRequest->belongsTo(Officer)
```

## Testing

### Test Scheduled Cleanup
```bash
# Clean up registrations older than 1 hour (for testing)
php artisan registrations:cleanup --hours=1

# Expected output:
# Cleaned up X incomplete registration(s) older than 1 hours.
```

### Test Automatic Cleanup
1. Start registration (Step 1 - Email)
2. Verify email (Step 2 - Personal Info)
3. Close browser tab/window
4. Check database - officer record should be gone

### Test Manual Cancel
1. Start registration process
2. Fill in personal info
3. Click "Cancel Registration" button
4. Check database - officer record should be deleted

## Monitoring

### Check for Incomplete Registrations
```sql
SELECT id, email, registration_status, created_at 
FROM officers 
WHERE registration_status != 'completed' 
  AND registration_status != 'submitted'
  AND created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR);
```

### View Cleanup Logs
Laravel logs cleanup operations. Check:
```bash
tail -f storage/logs/laravel.log | grep "cleanup"
```

## Configuration

### Adjust Cleanup Timing

**Scheduled cleanup frequency:**
Edit `routes/console.php`:
```php
// Every hour (default)
Schedule::command('registrations:cleanup')->hourly();

// Every 30 minutes
Schedule::command('registrations:cleanup')->everyThirtyMinutes();

// Daily at 2 AM
Schedule::command('registrations:cleanup')->dailyAt('02:00');
```

**Inactivity timeout:**
Edit JavaScript in blade files:
```javascript
// 5 minutes = 300000 milliseconds (default)
setTimeout(() => cleanup(), 300000);

// 10 minutes
setTimeout(() => cleanup(), 600000);
```

**Age threshold:**
Run command with custom hours:
```bash
php artisan registrations:cleanup --hours=48  # 2 days
php artisan registrations:cleanup --hours=12  # 12 hours
```

## Security Considerations

1. **CSRF Protection:** All cleanup endpoints require valid CSRF token
2. **Authorization:** No authentication required for cleanup (registration not yet complete)
3. **Data Integrity:** Foreign key constraints prevent orphaned records
4. **Race Conditions:** Checked with `registration_status` before deletion

## Troubleshooting

### Cleanup not running automatically
- Verify Laravel scheduler is running: `php artisan schedule:work`
- Check cron job is configured correctly
- Test manual execution: `php artisan registrations:cleanup`

### JavaScript cleanup not working
- Check browser console for errors
- Verify CSRF token is present in meta tag
- Test with `sendBeacon` support: Modern browsers only

### Database errors during cleanup
- Check foreign key constraints are properly defined
- Verify cascade deletes are configured
- Run migrations: `php artisan migrate:fresh`

## Future Enhancements

Potential improvements:
1. Email notification before cleanup (24-hour warning)
2. Grace period for incomplete registrations
3. Archive instead of delete (soft deletes)
4. Admin dashboard for monitoring cleanup activity
5. Configurable cleanup rules per registration step
