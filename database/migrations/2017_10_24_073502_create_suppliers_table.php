<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
	        $table->increments('id');
	        $table->string('name');
	        $table->string('email')->unique();
	        $table->string('password');
	        $table->string  ('phone',12)->unique();
	        $table->boolean ('status')->default(true);
	        $table->double ( 'longitude' )->default(0.0);
	        $table->double ( 'latitude' )->default(0.0);
	        $table->text('bio')->nullable();
	        $table->integer('exp_year')->nullable();
	        $table->boolean('type')->default(false);
	        $table->timestamp ('deleted_at')->nullable();
	        $table->timestamp ('created_at');
	        $table->timestamp ('updated_at')->nullable();
//            $table->rememberToken();
//	        $table->timestamps();
        });




    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
//        Schema::dropIfExists('login');
    }
}
