<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'admin_id')) {
                $table->unsignedBigInteger('admin_id')->nullable()->after('status');
            }
            if (!Schema::hasColumn('reports', 'admin_note')) {
                $table->text('admin_note')->nullable();
            }
            if (!Schema::hasColumn('reports', 'action_taken')) {
                $table->string('action_taken')->nullable();
            }
            if (!Schema::hasColumn('reports', 'appeal_message')) {
                $table->text('appeal_message')->nullable();
            }
            if (!Schema::hasColumn('reports', 'appeal_status')) {
                $table->string('appeal_status')->nullable();
            }
            if (!Schema::hasColumn('reports', 'appealed_at')) {
                $table->timestamp('appealed_at')->nullable();
            }
            // reviewed_at আগে থেকেই আছে — স্কিপ
        });

        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('posts', 'deleted_by_admin_id')) {
                $table->unsignedBigInteger('deleted_by_admin_id')->nullable();
            }
            if (!Schema::hasColumn('posts', 'admin_delete_note')) {
                $table->text('admin_delete_note')->nullable();
            }
        });

        Schema::table('job_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('job_posts', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('job_posts', 'deleted_by_admin_id')) {
                $table->unsignedBigInteger('deleted_by_admin_id')->nullable();
            }
            if (!Schema::hasColumn('job_posts', 'admin_delete_note')) {
                $table->text('admin_delete_note')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            foreach (['admin_id','admin_note','action_taken','appeal_message','appeal_status','appealed_at'] as $col) {
                if (Schema::hasColumn('reports', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'deleted_by_admin_id')) $table->dropColumn('deleted_by_admin_id');
            if (Schema::hasColumn('posts', 'admin_delete_note')) $table->dropColumn('admin_delete_note');
            if (Schema::hasColumn('posts', 'deleted_at')) $table->dropSoftDeletes();
        });

        Schema::table('job_posts', function (Blueprint $table) {
            if (Schema::hasColumn('job_posts', 'deleted_by_admin_id')) $table->dropColumn('deleted_by_admin_id');
            if (Schema::hasColumn('job_posts', 'admin_delete_note')) $table->dropColumn('admin_delete_note');
        });
    }
};