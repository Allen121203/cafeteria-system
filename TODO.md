# TODO: Make Tables Responsive and Scrollable

## Files to Update:
- [ ] resources/views/admin/reports/show.blade.php (multiple tables)
- [ ] resources/views/admin/reservations/index.blade.php
- [ ] resources/views/admin/inventory/index.blade.php
- [ ] resources/views/admin/dashboard.blade.php (expiring items table)
- [ ] resources/views/admin/recipes/index.blade.php
- [ ] resources/views/admin/menus/prices.blade.php
- [ ] resources/views/superadmin/users.blade.php
- [ ] resources/views/superadmin/audit.blade.php
- [ ] resources/views/customer/reservation_details.blade.php

## Changes Needed:
- Wrap tables in responsive container with overflow-x-auto and overflow-y-auto
- Add responsive height classes (e.g., max-h-96 on mobile, larger on desktop)
- Ensure table has min-w-full for proper scrolling
- Adjust padding for mobile devices if necessary
- Test responsiveness across different screen sizes
