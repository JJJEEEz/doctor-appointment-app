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
        Schema::table('patients', function (Blueprint $table) {
            $table->text('known_allergies')->nullable()->after('address');
            $table->text('chronic_diseases')->nullable()->after('known_allergies');
            $table->text('surgical_history')->nullable()->after('chronic_diseases');
            $table->text('family_history')->nullable()->after('surgical_history');
            $table->text('observations')->nullable()->after('family_history');

            $table->string('emergency_contact_name')->nullable()->after('observations');
            $table->string('emergency_contact_phone', 20)->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'known_allergies',
                'chronic_diseases',
                'surgical_history',
                'family_history',
                'observations',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship',
            ]);
        });
    }
};
