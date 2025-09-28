# Remove Day Functionality from Menus and Add Delete Buttons with Confirmation Modal

## Information Gathered
- DB: 'number' column dropped via migration 2025_09_28_091150_drop_number_from_menus_table.php (safe drop if exists).
- Model: fillable updated to remove 'number'.
- Controller: index updated to always list all menus without grouping; create passes 'day' query; store/update validate and include 'number' in payload/rules.
- View: Uses @if($hasNumber) for day-based 4-column grid vs all menus grid; modals have day select (name="number"); JS handles form.number, openCreate(day param), openEdit(number param); edit calls pass $menu->number.
- Routes: DELETE /admin/menus/{menu} handled by destroy method.

## Plan
- [x] Migration: Edit and run 2025_09_28_091150_drop_number_from_menus_table.php to drop 'number' column.
- [x] Model: Remove 'number' from fillable in app/Models/Menu.php.
- [x] Controller:
  - [x] create: Remove 'day' query param and passing to view.
  - [x] store: Remove $hasDay check, 'number' validation rule, auto-increment, and 'number' from payload.
  - [x] update: Remove $hasDay check, 'number' validation rule, and 'number' from payload.
  - [x] index: Already updated to remove $hasNumber and always use all menus; remove 'hasNumber' from view data (done in previous edit).
- [x] View (resources/views/admin/menus/index.blade.php):
  - [x] Remove @if($hasNumber); always use the all menus grid section.
  - [x] Create modal: Remove day select div.
  - [x] Edit modal: Remove day select div.
  - [x] JS: Remove number from form/editForm objects; update openCreate (remove day param/assignment); update openEdit (remove number param/assignment); adjust blade edit @click to remove number arg.
  - [x] Add delete: In each menu card (both grid sections if needed, but since unified, in all), add delete button @click="openDelete(id)"; add delete modal with form action="{{ route('admin.menus.destroy', id) }}", @csrf @method('DELETE'), confirmation text, submit button.
- [ ] Dependent Files: None.
- [ ] Followup steps: Clear cache, test create (no day field, saves without error), test delete (modal opens, confirms, deletes menu, success message).

## Next Steps
- Update controller methods.
- Update view for no days and add delete modal.
- Test and complete.
