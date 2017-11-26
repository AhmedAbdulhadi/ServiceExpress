<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
	        $table->string('name_ar');
	        $table->string('name_en');
	        $table->text('desc_en');
	        $table->text('desc_ar');
	        $table->boolean ('status')->default ( true );

	        $table->string('image');
	        $table->timestamp ( 'deleted_at' )->nullable ();
            $table->timestamps();
        });
	    Schema::create ( 'section_services' , function (Blueprint $table) {
		    $table->increments ( 'id' );

		    $table->integer ( 'services_id' )->unsigned ()->index ();
		    $table->foreign ( 'services_id' )->references ( 'id' )->on ( 'services' )->onDelete ( 'cascade' );

		    $table->integer ( 'section_id' )->unsigned ()->index ();
		    $table->foreign ( 'section_id' )->references ( 'id' )->on ( 'sections' )->onDelete ( 'cascade' );

		    $table->boolean ( 'status' )->default ( true );
		    $table->timestamp ( 'deleted_at' )->nullable ();
		    $table->timestamps ();
	    } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
        Schema::dropIfExists('section_services');
    }
}
