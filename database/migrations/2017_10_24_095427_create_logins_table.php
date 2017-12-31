<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create ( 'logins' , function (Blueprint $table) {
		    $table->increments ( 'id' );
		    $table->string ( 'email' )->unique();
		    $table->string ( 'password' );
		    $table->enum  ( 'type',['0','1','2']);
		    $table->timestamp ( 'deleted_at' )->nullable ();
		    $table->boolean ('status')->default(true);
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
        Schema::dropIfExists('logins');
    }
}
