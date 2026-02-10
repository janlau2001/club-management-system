# Registration Cleanup System - Verification

## Cleanup Triggers Across All Steps

### Step 1: Email Registration (officer-email-registration.blade.php)
**Status:** ✅ No cleanup needed
- **Why:** No database record exists yet until form submission
- Officer is only created AFTER clicking "Register" and sending the email
- If user leaves this page, nothing to cleanup

### Step 2: Personal Information (officer-personal-info.blade.php)
**Status:** ✅ Full cleanup implemented

#### Manual Cleanup:
- **Cancel Button** → Triggers `cancelRegistration()` function
  - Calls DELETE `/club/registration/cleanup/{officer}`
  - Redirects to login page
  - Officer record is deleted from database

#### Automatic Cleanup:
1. **Page Close/Tab Close:**
   ```javascript
   window.addEventListener('beforeunload', function(e) {
       if (!formSubmitted) {
           navigator.sendBeacon(cleanupUrl, formData);
       }
   });
   ```

2. **Inactivity (5 minutes):**
   ```javascript
   document.addEventListener('visibilitychange', function() {
       if (document.hidden && !formSubmitted) {
           setTimeout(() => cleanup(), 300000); // 5 minutes
       }
   });
   ```

### Step 3: Club Registration (club-registration.blade.php)
**Status:** ✅ Full cleanup implemented

#### Manual Cleanup:
- **Cancel Button** → Triggers `cancelRegistration()` function
  - Calls DELETE `/club/registration/cleanup/{officer}`
  - Redirects to login page
  - Officer AND ClubRegistrationRequest records deleted

#### Automatic Cleanup:
1. **Page Close/Tab Close:**
   ```javascript
   window.addEventListener('beforeunload', function(e) {
       if (!registrationSubmitted) {
           navigator.sendBeacon(cleanupUrl, formData);
       }
   });
   ```

2. **Inactivity (5 minutes):**
   ```javascript
   document.addEventListener('visibilitychange', function() {
       if (document.hidden && !registrationSubmitted) {
           setTimeout(() => cleanup(), 300000); // 5 minutes
       }
   });
   ```

## Backend Cleanup Handler

**Route:** `DELETE /club/registration/cleanup/{officer}`  
**Controller:** `ClubAuthController@cleanupIncompleteRegistration`

**Logic:**
```php
public function cleanupIncompleteRegistration(Officer $officer)
{
    // Only cleanup if registration is not completed or submitted
    if ($officer->registration_status !== 'completed' && 
        $officer->registration_status !== 'submitted') {
        
        // Delete associated club registration request if exists
        if ($officer->clubRegistrationRequest) {
            $officer->clubRegistrationRequest->delete();
        }
        
        // Delete the officer
        $officer->delete();
        
        return response()->json(['success' => true]);
    }
    
    return response()->json(['success' => false]);
}
```

## Scheduled Cleanup

**Command:** `php artisan registrations:cleanup`  
**Schedule:** Runs hourly via Laravel scheduler  
**Threshold:** Removes registrations older than 24 hours

**How to run manually:**
```bash
php artisan registrations:cleanup --hours=1
```

## Testing Checklist

### Test Step 2 Cleanup:
- [ ] Click Cancel button → Database clears ✅
- [ ] Close browser tab → Database clears (via sendBeacon) ✅
- [ ] Navigate to another site → Database clears ✅
- [ ] Hide tab for 5+ minutes → Database clears ✅
- [ ] Submit form successfully → Database NOT cleared ✅

### Test Step 3 Cleanup:
- [ ] Click Cancel button → Database clears ✅
- [ ] Close browser tab → Database clears (via sendBeacon) ✅
- [ ] Navigate to another site → Database clears ✅
- [ ] Hide tab for 5+ minutes → Database clears ✅
- [ ] Submit form successfully → Database NOT cleared ✅

## Verification Query

Check for incomplete registrations:
```sql
SELECT id, email, registration_status, created_at 
FROM officers 
WHERE registration_status != 'completed' 
  AND registration_status != 'submitted'
ORDER BY created_at DESC;
```

Expected: Should be empty after any cancellation/close/inactivity
