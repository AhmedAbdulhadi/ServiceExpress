<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServicesSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create ( 'services_suppliers' , function (Blueprint $table) {
//		    $table->increments ( 'id' );

		    $table->integer ( 'services_model_id' )->unsigned ()->index ();
		    $table->foreign ( 'services_model_id' )->references ( 'id' )->on ( 'services' )->onDelete ( 'cascade' );

		    $table->integer ( 'supplier_model_id' )->unsigned ()->index ();
		    $table->foreign ( 'supplier_model_id' )->references ( 'id' )->on ( 'suppliers' )->onDelete ( 'cascade' );


		          $table->primary(['services_model_id', 'supplier_model_id']);

		    $table->boolean ( 'status' )->default ( true );
		    $table->timestamp ( 'deleted_at' )->nullable ();
//		    $table->timestamps ();
		    $table->timestamp ('created_at');
		    $table->timestamp ('updated_at')->nullable();
	    } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('services_suppliers');
    }
}
