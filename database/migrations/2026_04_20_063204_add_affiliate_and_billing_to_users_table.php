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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'affiliate_code')) {
                $table->string('affiliate_code')->nullable()->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'referred_by')) {
                $table->foreignId('referred_by')->nullable()->constrained('users')->nullOnDelete()->after('affiliate_code');
            }
            if (!Schema::hasColumn('users', 'affiliate_balance')) {
                $table->decimal('affiliate_balance', 10, 2)->default(0)->after('status');
            }
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'next_bill_date')) {
                $table->timestamp('next_bill_date')->nullable()->after('trial_ends_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['affiliate_code', 'referred_by', 'affiliate_balance', 'phone_number', 'next_bill_date']);
        });
    }
};
