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
        Schema::create('shops_reviews', function (Blueprint $table) {
            $table->id();
            $table->uuid('shops_reviews_uuid');
            $table->uuid('shops_uuid');
            $table->text('comment');
            $table->float('rating');
            $table->float('product_desc');
            $table->float('services_quality')->default(0);
            $table->float('product_categories')->default(0);
            $table->float('logistic_services')->default(0);
            $table->float('geographical_location')->default(0);
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('shops_reviews');
    }
};