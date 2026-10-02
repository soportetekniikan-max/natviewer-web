<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('admin_role', 30)
                ->nullable()
                ->after('is_admin')
                ->index();
        });

        /*
         * Todos los administradores existentes tenían
         * acceso completo antes de introducir roles.
         *
         * Por compatibilidad, pasan a Super Admin.
         */
        DB::table('users')
            ->where('is_admin', true)
            ->whereNull('admin_role')
            ->update([
                'admin_role' => 'super_admin',
            ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex([
                'admin_role',
            ]);

            $table->dropColumn(
                'admin_role'
            );
        });
    }
};