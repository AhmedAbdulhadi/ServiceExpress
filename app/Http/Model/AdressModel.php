<?php

	namespace App;

	use Illuminate\Notifications\Notifiable;
	use Illuminate\Database\Eloquent\Model;
//	use Illuminate\Support\Facades\DB;

	class adressModel extends Model
	{
		use Notifiable;
		public $table = "address";
		protected $fillable = [
			'longitude' ,
			'latitude' ,
			'city' ,
			'street' ,
			'country' ,
			'neighborhood' ,
			'building_number' ,
			'apartment_number' ,
			'floor' ,
			'created_at' ,
			'address_type'
		];
		protected $hidden = [
			'password' , 'pivot'
		];

		public static function getAddressByUserIdAddressType ($userID,$addressType)
		{
//		$addressObject=	DB::table ( 'address_user' )->where ( 'user_model_id' , $userID)->where ( 'address_type' , $addressType)->first ();
			$addressObject= new UserModel();
			$addressObject=$addressObject->find ($userID)->address ()->wherePivot ('address_type',$addressType)->first ();
//			$addressObject->address()->wherePivot  ('address_type',$addressType)->first () ;
//		dd($addressObject->toArray());
		return $addressObject;
		}

		public static function getAllAddress ()
		{
			$addressList = adressModel::all ();

			return $addressList;
		}

		public static function getAddressByID ($addressID)
		{
			$addressObject = adressModel::find ( $addressID );

			return $addressObject;
		}

		public function users ()
		{
			return $this->belongsTo ( UserModel::class,'address_user' );

		}

		public function getCreatedAt ()
		{
			return $this->created_at;

		}

		public function getUpdatedAt ()
		{
			return $this->updated_at;

		}

		public function getLongitude ()
		{
			return $this->longitude;

		}

		public function getLatitude ()
		{
			return $this->latitude;
		}

		public function getStreet ()
		{
			return $this->street;
		}

		public function getCity ()
		{
			return $this->city;
		}

		public function getCountry ()
		{
			return $this->country;
		}

		public function getBuildingNumber ()
		{
			return $this->building_number;
		}

		public function getNeighborhood ()
		{
			return $this->neighborhood;
		}

		public function getApartmentNumber ()
		{
			return $this->apartment_number;
		}

		public function getFloor ()
		{
			return $this->floor;
		}

		public function getAddressType ()
		{
			return $this->address_type;
		}

		public function getStatus ()
		{
			return $this->status;
		}

		public function getDeletedAt ()
		{
			return $this->deleted_at;
		}


		public function setCreatedAt ($value)
		{
			$this->attributes['created_at'] = $value;
		}

		public function setUpdatedAt ($value)
		{
			$this->attributes['updated_at'] = $value;
		}

		public function setDeletedAt ($value)
		{
			$this->attributes['deleted_at'] = $value;
		}

		private function setLongitud ($value)
		{
			$this->attributes['longitude'] = $value;

		}

		private function setLatitude ($value)
		{
			$this->attributes['latitude'] = $value;
		}

		private function setStreet ($value)
		{
			$this->attributes['street'] = $value;
		}

		private function setCity ($value)
		{
			$this->attributes['city'] = $value;
		}

		private function setStatus ($value)
		{
			$this->attributes['status'] = $value;
		}

		private function setCountry ($value)
		{
			$this->attributes['country'] = $value;
		}

		private function setBuildingNumber ($value)
		{
			$this->attributes['building_number'] = $value;
		}

		private function setNeighborhood ($value)
		{
			$this->attributes['neighborhood'] = $value;
		}

		private function setApartmentNumber ($value)
		{
			$this->attributes['apartment_number'] = $value;
		}

		private function setFloor ($value)
		{
			$this->attributes['floor'] = $value;
		}

		private function setAddressType ($value)
		{
			$this->attributes['address_type'] = $value;
		}


	}
