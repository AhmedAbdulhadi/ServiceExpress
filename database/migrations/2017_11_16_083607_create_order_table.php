<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create ( 'orders' , function (Blueprint $table) {
		    $table->increments ( 'id' );
		    $table->timestamp ( 'delivered_at' )->default(null);
		    $table->enum( 'status' ,[0,1,2,3])->default(0);


		    $table->integer ( 'user_id' );
		    $table->integer ( 'supplier_id' );
		    $table->integer ( 'service_id' );
		    $table->text ( 'desc' )->nullable();
		    $table->string ( 'path',500 )->nullable();



		    $table->boolean('is_rated')->default(false);
		    $table->double ('rate','5')->default(0)->nullable();

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
        Schema::dropIfExists('orders');
    }
}
