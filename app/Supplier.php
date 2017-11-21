<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Notifications\Notifiable;
	use Laravel\Passport\HasApiTokens;
	use Illuminate\Foundation\Auth\User as Authenticatable;

	class Supplier extends Authenticatable
	{
		use Notifiable , HasApiTokens;


		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array
		 */
		protected $fillable = [
			'name' , 'email' , 'password' , 'phone' , 'longitude' , 'latitude'
		];

		/**
		 * The attributes that should be hidden for arrays.
		 *
		 * @var array
		 */
		protected $hidden = [
			'password',"pivot","deleted_at"
		];

		public function services ()
		{
			return $this->belongsToMany ( Services::class ,'services_suppliers')->withTimestamps ();
		}
	}
