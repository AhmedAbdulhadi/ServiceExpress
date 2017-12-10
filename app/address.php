<?php

	namespace App;

	use Illuminate\Notifications\Notifiable;
	use Illuminate\Database\Eloquent\Model;

	class address extends Model
	{
		use Notifiable;
		public $table = "address";
		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array
		 */
		protected $fillable = [
			'longitude',
			'latitude',
			'city',
			'street',
			'country',
			'neighborhood',
			'building_number',
			'apartment_number',
			'floor',
			'created_at',
			'address_type'
		];
		/**
		 * The attributes that should be hidden for arrays.
		 *
		 * @var array
		 */
		protected $hidden = [
			'password','pivot'
		];

		public function users ()
		{
			return $this->belongsTo ('App\User');

		}
	}
