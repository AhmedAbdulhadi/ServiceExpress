<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
	        $table->increments('id');
	        $table->string('name');
	        $table->string('email')->unique();
	        $table->string('password');
	        $table->string  ('phone',12)->unique();
	        $table->boolean ('status')->default(true);
	        $table->boolean('role')->default(false);
	        $table->timestamp ('deleted_at')->nullable();
//	        $table->timestamps ();
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
        Schema::dropIfExists('admins');
    }
}
