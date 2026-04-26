<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('ssl_status')->default('none')->after('last_check_at'); // none, pending, active, failed
            $table->timestamp('ssl_verified_at')->nullable()->after('ssl_status');
            $table->text('ssl_error_log')->nullable()->after('ssl_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['ssl_status', 'ssl_verified_at', 'ssl_error_log']);
        });
    }
};
