<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToCampaignTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_recipients', function (Blueprint $table) {
            // Chỉ thêm nếu chưa có cột status
            if (!Schema::hasColumn('campaign_recipients', 'status')) {
                $table->string('status')->default('pending');
            }

            if (!Schema::hasColumn('campaign_recipients', 'error_message')) {
                $table->text('error_message')->nullable();
            }
        });

        Schema::table('campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('campaigns', 'total_recipients')) {
                $table->integer('total_recipients')->default(0);
            }

            if (!Schema::hasColumn('campaigns', 'sent_count')) {
                $table->integer('sent_count')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_tables', function (Blueprint $table) {
            //
        });
    }
}
