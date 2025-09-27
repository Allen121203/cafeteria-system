<?php

/**
 * Permission config disabled.
 *
 * This application uses a single `role` string column on users and does not
 * require the spatie/laravel-permission package. Keep this file present so any
 * code that references config('permission') doesn't break, but return an
 * inert array to avoid attempting to load Spatie classes.
 */

return [];
