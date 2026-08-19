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
        Schema::table('tbl_pengujian', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_pengujian', 'execution_time_ms')) {
                $table->decimal('execution_time_ms', 10, 2)->nullable()->after('min_confidence');
            }
            if (!Schema::hasColumn('tbl_pengujian', 'total_frequent_itemsets')) {
                $table->integer('total_frequent_itemsets')->nullable()->after('execution_time_ms');
            }
            if (!Schema::hasColumn('tbl_pengujian', 'total_rules')) {
                $table->integer('total_rules')->nullable()->after('total_frequent_itemsets');
            }
            if (!Schema::hasColumn('tbl_pengujian', 'api_status')) {
                $table->string('api_status', 100)->nullable()->default('200 OK')->after('total_rules');
            }
        });

        Schema::table('tbl_nilai_kombinasi', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_nilai_kombinasi', 'lift_ratio')) {
                $table->decimal('lift_ratio', 8, 4)->nullable()->default(0)->after('confidence');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pengujian', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['execution_time_ms', 'total_frequent_itemsets', 'total_rules', 'api_status'] as $col) {
                if (Schema::hasColumn('tbl_pengujian', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        Schema::table('tbl_nilai_kombinasi', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_nilai_kombinasi', 'lift_ratio')) {
                $table->dropColumn('lift_ratio');
            }
        });
    }
};
