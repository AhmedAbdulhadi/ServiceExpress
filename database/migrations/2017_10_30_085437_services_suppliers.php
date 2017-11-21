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

		    $table->integer ( 'services_id' )->unsigned ()->index ();
		    $table->foreign ( 'services_id' )->references ( 'id' )->on ( 'services' )->onDelete ( 'cascade' );

		    $table->integer ( 'supplier_id' )->unsigned ()->index ();
		    $table->foreign ( 'supplier_id' )->references ( 'id' )->on ( 'suppliers' )->onDelete ( 'cascade' );


		          $table->primary(['services_id', 'supplier_id']);

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
	    Schema::dropIfExists('services_suppliers');
    }
}
