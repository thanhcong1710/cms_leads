<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsReportOverdueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_report_overdue', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->index();
            $table->integer('owner_id')->index();
            $table->integer('branch_id')->nullable()->index();
            $table->dateTime('next_care_date')->nullable();
            $table->dateTime('actual_care_date')->nullable();
            $table->string('overdue_month', 7)->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_report_overdue');
    }
}
