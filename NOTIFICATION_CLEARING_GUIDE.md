# Notification Clearing System

## Overview
This system allows the head office (Head Student Affairs) to clear pending notifications that have been sent to clubs. This is useful for managing notification overflow and ensuring clubs don't receive outdated information.

## Features Implemented

### 1. Database Structure
- **Notifications Table**: Stores all notifications sent to clubs
  - `type`: Type of notification (renewal_reminder, general, etc.)
  - `title`: Notification title
  - `message`: Notification content
  - `club_id`: Target club
  - `user_id`: Target club user
  - `is_read`: Whether the notification has been read
  - `read_at`: When the notification was read
  - `data`: Additional JSON data

### 2. Controller Methods (HeadOfficeController)

#### `clearPendingNotifications()`
- Clears ALL pending (unread) notifications
- Requires head_student_affairs role
- Logs the action for audit trail
- Returns JSON response for AJAX calls
- Redirects with success/error message for regular requests

#### `clearNotificationsByType()`
- Clears pending notifications by specific type
- Supports: 'renewal_reminder', 'general', 'all'
- Same security and logging as above

### 3. Routes Added
```php
// In head-office route group
Route::delete('/notifications/clear-pending', [HeadOfficeController::class, 'clearPendingNotifications'])->name('notifications.clear-pending');
Route::delete('/notifications/clear-by-type', [HeadOfficeController::class, 'clearNotificationsByType'])->name('notifications.clear-by-type');
```

### 4. Dashboard Integration
- Added notification statistics to dashboard
- Shows count of pending notifications
- Shows count of pending renewal reminders
- Added notification management section in Quick Actions
- Interactive buttons for clearing notifications

### 5. Frontend Features
- JavaScript functions for AJAX clearing
- Confirmation dialogs before clearing
- Real-time feedback and page reload
- Error handling and user notifications

## Usage

### Via Head Office Dashboard
1. Login as Head Student Affairs
2. Go to Head Office Dashboard
3. In the Quick Actions section, find "Notification Management"
4. Click either:
   - "Clear All Pending" - removes all unread notifications
   - "Clear Renewal Reminders" - removes only renewal reminder notifications

### Via Artisan Commands
```bash
# Check notification status
php artisan notifications:status

# Create test notifications for testing
php artisan notifications:seed 10

# Test clearing specific types
php artisan notifications:test-clear renewal  # Clear renewal reminders
php artisan notifications:test-clear all      # Clear all pending
```

## Security Features
- Role-based access control (head_student_affairs only)
- CSRF protection on all requests
- Audit logging of all clearing actions
- Confirmation dialogs before destructive actions

## API Endpoints

### Clear All Pending Notifications
```
DELETE /head-office/notifications/clear-pending
Headers: X-CSRF-TOKEN, Accept: application/json
Response: { "success": true, "message": "...", "cleared_count": N }
```

### Clear Notifications by Type
```
DELETE /head-office/notifications/clear-by-type
Headers: X-CSRF-TOKEN, Accept: application/json
Body: { "type": "renewal_reminder|general|all" }
Response: { "success": true, "message": "...", "cleared_count": N }
```

## Audit Trail
All clearing actions are logged with:
- Admin name and role
- Number of notifications cleared
- Notification type (if specified)
- Timestamp

Logs can be found in `storage/logs/laravel.log`.

## Testing Commands
- `php artisan notifications:seed {count}` - Create test notifications
- `php artisan notifications:status` - Check current notification counts
- `php artisan notifications:test-clear {type}` - Test clearing functionality

## Example Usage Workflow
1. Head office sends renewal reminders to clubs
2. Some clubs don't read notifications (they pile up)
3. Head office wants to clear old reminders before sending new ones
4. Admin logs into head office dashboard
5. Clicks "Clear Renewal Reminders" 
6. System clears all unread renewal reminders
7. Action is logged for audit purposes
8. Dashboard shows updated counts
