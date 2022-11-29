<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPdfStatusColumnToEmployeeReportStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_report_statuses', function (Blueprint $table) {
            $table->enum('pdf_status', ['pending', 'processing', 'generated', 'failed'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_report_statuses', function (Blueprint $table) {
            $table->dropColumn('pdf_status');
        });
    }
}
