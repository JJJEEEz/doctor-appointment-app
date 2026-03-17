<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (! Schema::hasColumn('appointments', 'whatsapp_confirmation_sent_at')) {
                $table->timestamp('whatsapp_confirmation_sent_at')->nullable()->after('prescriptions');
            }

            if (! Schema::hasColumn('appointments', 'whatsapp_reminder_sent_at')) {
                $table->timestamp('whatsapp_reminder_sent_at')->nullable()->after('whatsapp_confirmation_sent_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'whatsapp_reminder_sent_at')) {
                $table->dropColumn('whatsapp_reminder_sent_at');
            }

            if (Schema::hasColumn('appointments', 'whatsapp_confirmation_sent_at')) {
                $table->dropColumn('whatsapp_confirmation_sent_at');
            }
        });
    }
};
