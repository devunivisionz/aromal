<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeReportStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_report_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('report_id');
            $table->integer('emp_id');
            $table->string('emp_name');
            $table->enum('status', ['processing', 'completed', 'failed'])->nullable();
            $table->tinyInteger('pdf_created')->default(0);
            $table->text('pdf_file_name')->nullable();
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
        Schema::dropIfExists('employee_report_statuses');
    }
}
