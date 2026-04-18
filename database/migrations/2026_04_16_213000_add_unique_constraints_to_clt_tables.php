<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layups', function (Blueprint $table) {
            $table->unique(['supplier_id', 'name']);
        });

        Schema::table('layers', function (Blueprint $table) {
            $table->unique(['layup_id', 'layer_order']);
        });
    }

    public function down(): void
    {
        Schema::table('layers', function (Blueprint $table) {
            $table->dropUnique(['layup_id', 'layer_order']);
        });

        Schema::table('layups', function (Blueprint $table) {
            $table->dropUnique(['supplier_id', 'name']);
        });
    }
};
