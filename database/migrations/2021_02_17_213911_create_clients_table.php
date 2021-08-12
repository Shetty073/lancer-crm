<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('business_name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('contact_no', 22)->nullable();
            $table->string('subject', 2048)->nullable();
            $table->boolean('is_active')->default(1);
            $table->integer('rating')->nullable();
            $table->string('remark', 200)->nullable();
            $table->decimal('carpet_area', 10, 2)->nullable();
            $table->decimal('agreement_value', 20, 2)->nullable();
            $table->decimal('booking_amount', 20, 2)->nullable();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('restrict');
            $table->foreignId('configuration_id')->nullable()->constrained('configurations')->onDelete('set null');
            $table->foreignId('payment_mode_id')->nullable()->constrained('payment_modes')->onDelete('restrict');
            $table->foreignId('closed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
