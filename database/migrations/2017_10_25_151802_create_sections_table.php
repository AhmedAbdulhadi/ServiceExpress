<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer ('admin_model_id');
            $table->string('name_ar');
            $table->string('name_en');
	        $table->text('desc_en');
	        $table->text('desc_ar');
	        $table->string('image');
	        $table->boolean ('status')->default ( true );
	        $table->timestamp ( 'deleted_at' )->nullable ();
//            $table->timestamps();
	        $table->timestamp ('created_at');
	        $table->timestamp ('updated_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections');
    }
}
