<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('business_name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('contact_no', 22)->nullable();
            $table->string('subject', 2048);
            $table->boolean('is_lost')->default(0);
            $table->foreignId('enquiry_status_id')->nullable()->constrained('enquiry_statuses')->onDelete('restrict');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
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
        Schema::dropIfExists('enquiries');
    }
}
