<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('idp_action_plans', 'competency_type')) {
            Schema::table('idp_action_plans', function (Blueprint $table) {
                $table->string('competency_type', 50)->default('Manajerial')->nullable()->after('competency');
            });
        }

        // Set all existing IDP records to 'Manajerial'
        DB::table('idp_action_plans')
            ->whereNull('competency_type')
            ->orWhere('competency_type', '')
            ->update(['competency_type' => 'Manajerial']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('idp_action_plans', 'competency_type')) {
            Schema::table('idp_action_plans', function (Blueprint $table) {
                $table->dropColumn('competency_type');
            });
        }
    }
};
