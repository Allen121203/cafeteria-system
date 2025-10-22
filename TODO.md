# Reports System Refactor TODO

## Phase 1: Create ReportsService
- [ ] Create app/Services/ReportsService.php
- [ ] Move all data processing logic from controller to service
- [ ] Implement consistent date filtering using whereBetween()
- [ ] Add proper error handling and validation

## Phase 2: Refactor ReportsController
- [ ] Update ReportsController to use ReportsService
- [ ] Remove duplicate code between generate() and exportPdf()
- [ ] Simplify controller methods
- [ ] Add proper dependency injection

## Phase 3: Fix Export Classes
- [ ] Fix SalesReportExport map() method to export all item rows
- [ ] Update all export classes to use ReportsService
- [ ] Ensure consistent data structure across exports
- [ ] Test Excel export functionality

## Phase 4: Simplify Views
- [ ] Refactor show.blade.php to reduce conditional complexity
- [ ] Update pdf.blade.php for better maintainability
- [ ] Create reusable components for report tables
- [ ] Improve error handling in views

## Phase 5: Testing & Validation
- [ ] Test all report types (reservation, sales, inventory, crm)
- [ ] Verify PDF and Excel exports work correctly
- [ ] Test date filtering consistency
- [ ] Validate data accuracy across all reports

## Phase 6: Code Cleanup
- [ ] Remove any unused code
- [ ] Add proper PHPDoc comments
- [ ] Ensure consistent code style
- [ ] Update any related documentation
