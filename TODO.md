# TODO: Fix Notifications Functionality

## Overview
The notifications system only notifies admins for reservation-related actions. Other changes (menu prices, reports, inventory, recipes, menu CRUD) do not notify admins properly. This needs to be fixed to ensure all relevant changes notify admins/superadmins.

## Steps
1. Update `createAdminNotification` method in `MenuController` to create notifications for each admin/superadmin user instead of the actor. ✅
2. Update `createAdminNotification` method in `ReportsController` to create notifications for each admin/superadmin user instead of the actor. ✅
3. Add `createAdminNotification` method to `InventoryItemController` and call it in `store`, `update`, and `destroy` methods. ✅
4. Add `createAdminNotification` method to `RecipeController` and call it in `store` and `destroy` methods. ✅
5. Add `createAdminNotification` calls in `MenuController` for `store`, `update`, and `destroy` methods (for menu changes). ✅
6. Fix `Notification` model's `scopeUnread` to use `read_at` instead of `read` (since the fillable has `read_at`). ✅

## Files to Edit
- app/Http/Controllers/MenuController.php
- app/Http/Controllers/ReportsController.php
- app/Http/Controllers/InventoryItemController.php
- app/Http/Controllers/RecipeController.php
- app/Models/Notification.php

## Dependencies
- Ensure `App\Models\User` is imported in controllers that need it.
- Ensure `App\Models\Notification` is imported.

## Testing
- After changes, test creating/updating menus, prices, inventory, recipes, reports, and reservations to ensure notifications are created for admins.
