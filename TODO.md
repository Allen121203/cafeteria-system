# TODO: Make Admin View Content Width Fixed and Responsive

## Overview
Replace `max-w-7xl` with `container` in admin view content wrappers to achieve fixed widths at responsive breakpoints (sm:640px, md:768px, lg:1024px, xl:1280px, 2xl:1536px) while maintaining responsiveness across devices.

## Files to Edit
- [x] resources/views/admin/dashboard.blade.php
- [x] resources/views/admin/reservations/index.blade.php
- [x] resources/views/admin/reports/show.blade.php
- [x] resources/views/admin/reports/index.blade.php
- [x] resources/views/admin/recipes/index.blade.php
- [x] resources/views/admin/menus/prices.blade.php
- [x] resources/views/admin/inventory/index.blade.php
- [x] resources/views/admin/calendar.blade.php

## Steps
1. Update each file's content wrapper from `max-w-7xl mx-auto` to `container mx-auto`.
2. Verify changes are applied correctly.
3. Test responsiveness on different screen sizes.
