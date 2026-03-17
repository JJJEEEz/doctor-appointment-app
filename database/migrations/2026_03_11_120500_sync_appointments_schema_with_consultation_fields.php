<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if (!Schema::hasTable('appointments')) {
            return;
        }

        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'date')) {
                $table->date('date')->nullable()->after('doctor_id');
            }

            if (!Schema::hasColumn('appointments', 'duration')) {
                $table->unsignedInteger('duration')->default(15)->after('end_time');
            }

            if (!Schema::hasColumn('appointments', 'reason')) {
                $table->text('reason')->nullable()->after('duration');
            }

            if (!Schema::hasColumn('appointments', 'diagnosis')) {
                $table->text('diagnosis')->nullable()->after('status');
            }

            if (!Schema::hasColumn('appointments', 'treatment')) {
                $table->text('treatment')->nullable()->after('diagnosis');
            }

            if (!Schema::hasColumn('appointments', 'consultation_notes')) {
                $table->text('consultation_notes')->nullable()->after('treatment');
            }

            if (!Schema::hasColumn('appointments', 'prescriptions')) {
                $table->json('prescriptions')->nullable()->after('consultation_notes');
            }
        });

        if (Schema::hasColumn('appointments', 'appointment_date')) {
            DB::statement('UPDATE appointments SET date = appointment_date WHERE date IS NULL');
        }

        if (Schema::hasColumn('appointments', 'notes')) {
            DB::statement('UPDATE appointments SET reason = notes WHERE reason IS NULL');
        }

        if ($driver !== 'sqlite' && Schema::hasColumn('appointments', 'status')) {
            $statusType = DB::table('information_schema.columns')
                ->where('table_schema', DB::getDatabaseName())
                ->where('table_name', 'appointments')
                ->where('column_name', 'status')
                ->value('DATA_TYPE');

            if ($statusType !== 'tinyint') {
                Schema::table('appointments', function (Blueprint $table) {
                    $table->tinyInteger('status_tmp')->default(1)->after('reason');
                });

                DB::statement("\n                    UPDATE appointments\n                    SET status_tmp = CASE\n                        WHEN status IN ('Programado', '1') THEN 1\n                        WHEN status IN ('Completado', '2') THEN 2\n                        WHEN status IN ('Cancelado', '0') THEN 0\n                        ELSE 1\n                    END\n                ");

                Schema::table('appointments', function (Blueprint $table) {
                    $table->dropColumn('status');
                });

                DB::statement('ALTER TABLE appointments CHANGE status_tmp status TINYINT NOT NULL DEFAULT 1');
            }
        }

        DB::table('appointments')
            ->whereNull('date')
            ->update(['date' => now()->toDateString()]);

        DB::table('appointments')
            ->where(function ($query) {
                $query->whereNull('duration')->orWhere('duration', '<=', 0);
            })
            ->update(['duration' => 15]);

        if (Schema::hasColumn('appointments', 'appointment_date')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('appointment_date');
            });
        }

        if (Schema::hasColumn('appointments', 'notes')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('notes');
            });
        }

        if ($driver !== 'sqlite') {
            DB::statement('ALTER TABLE appointments MODIFY date DATE NOT NULL');
        }
    }

    public function down(): void
    {
        // Migration de compatibilidad: no se revierte para evitar perdida de datos.
    }
};
