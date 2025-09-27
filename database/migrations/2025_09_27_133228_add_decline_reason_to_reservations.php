// database/migrations/xxxx_xx_xx_xxxxxx_add_decline_reason_to_reservations.php
<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class {
    public function up(): void {
        Schema::table('reservations', function (Blueprint $t) {
            if (!Schema::hasColumn('reservations','decline_reason')) {
                $t->text('decline_reason')->nullable();
            }
        });
    }
    public function down(): void {
        Schema::table('reservations', function (Blueprint $t) {
            if (Schema::hasColumn('reservations','decline_reason')) {
                $t->dropColumn('decline_reason');
            }
        });
    }
};
