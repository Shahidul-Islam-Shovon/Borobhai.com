<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'was_warned')) {
                $table->boolean('was_warned')->default(false)->after('action_taken');
            }
        });
    }
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'was_warned')) $table->dropColumn('was_warned');
        });
    }
};