<?php

	use Illuminate\Support\Facades\Schema;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreateAddressTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up ()
		{
			Schema::create ( 'address' , function (Blueprint $table) {
				$table->increments ( 'id' );
				$table->double ( 'longitude' );
				$table->double ( 'latitude' );
				$table->string ( 'street' , 30 )->default ( " " )->nullable ();
				$table->string ( 'city' , 30 )->default ( " " )->nullable ();
				$table->string ( 'country' , 30 )->default ( " " )->nullable ();
				$table->integer ( 'building_number' )->default ( 0 )->nullable ();
				$table->integer ( 'neighborhood' )->default ( 0 )->nullable ();
				$table->integer ( 'apartment_number' )->default ( 0 )->nullable ();
				$table->integer ( 'floor' )->default ( 0 )->nullable ();
				$table->enum  ( 'address_type' ,['0','1','2']);
				$table->boolean ( 'status' )->default ( true );
				$table->timestamp ( 'deleted_at' )->nullable ();
				$table->timestamps ();
			} );
			Schema::create ( 'address_user' , function (Blueprint $table) {
				$table->increments ( 'id' );
				$table->integer ( 'user_id' )->unsigned ()->index ();
				$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' )->onDelete ( 'cascade' );
				$table->integer ( 'address_id' )->unsigned ()->index ();
				$table->foreign ( 'address_id' )->references ( 'id' )->on ( 'address' )->onDelete ( 'cascade' );
				$table->enum  ( 'address_type',['0','1','2'])->references ( 'address_type' )->on ( 'address' );
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
		public function down ()
		{
			Schema::dropIfExists ( 'address' );
			Schema::dropIfExists ( 'address_user' );
		}
	}
