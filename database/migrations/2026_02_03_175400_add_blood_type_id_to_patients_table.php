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
        if (!Schema::hasColumn('patients', 'blood_type_id')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->foreignId('blood_type_id')
                    ->nullable()
                    ->constrained('blood_types')
                    ->onDelete('restrict')
                    ->after('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('patients', 'blood_type_id')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->dropConstrainedForeignId('blood_type_id');
            });
        }
    }
};
