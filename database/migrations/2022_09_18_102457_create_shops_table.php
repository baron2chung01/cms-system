<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->uuid('shops_uuid');
            $table->uuid('regions_uuid');
            $table->string('name');
            $table->string('shops_code');
            $table->string('phone');
            $table->string('whatsapp');
            $table->string('contact_person');
            $table->string('address');
            $table->float('latitude');
            $table->float('longitude');
            $table->text('desc');
            $table->text('remarks');
            $table->json('payment_methods');
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
};