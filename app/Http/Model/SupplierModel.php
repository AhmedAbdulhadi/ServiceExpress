<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Notifications\Notifiable;
	use Laravel\Passport\HasApiTokens;

	class SupplierModel extends Model
	{
		use Notifiable , HasApiTokens;
		protected $table = 'suppliers';

		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array
		 */
		protected $fillable = [
			'name' , 'email' , 'password' , 'phone' , 'longitude' , 'latitude' , 'exp_year' , 'bio','status'
		];

		/**
		 * The attributes that should be hidden for arrays.
		 *
		 * @var array
		 */
		protected $hidden = [
			'password' , "pivot" , "deleted_at"
		];

		public function getCreatedAt ()
		{
			return $this->created_at;

		}

		public function getUpdatedAt ()
		{
			return $this->updated_at;

		}

		public function getName ()
		{
			return $this->name;

		}

		public function getEmail ()
		{
			return $this->email;
		}

		public function getPassword ()
		{
			return $this->password;
		}

		public function getPhone ()
		{
			return $this->phone;
		}

		public function getStatus ()
		{
			return $this->status;
		}

		public function getLongitude ()
		{
			return $this->longitude;
		}

		public function getLatitude ()
		{
			return $this->latitude;
		}

		public function getExpYear ()
		{
			return $this->exp_year;
		}

		public function getBio ()
		{
			return $this->bio;
		}

		public function setCreatedAt ($value)
		{
			$this->attributes['created_at'] = $value;
		}

		public function setUpdatedAt ($value)
		{
			$this->attributes['updated_at'] = $value;
		}

		public function services ()
		{
			return $this->belongsToMany   ( ServicesModel::class , 'services_suppliers' )->withTimestamps ();
		}

		private function setName ($value)
		{
			$this->attributes['name'] = $value;

		}

		private function setEmail ($value)
		{
			$this->attributes['email'] = $value;
		}

		private function setPassword ($value)
		{
			$this->attributes['password'] = $value;
		}

		private function setPhone ($value)
		{
			$this->attributes['phone'] = $value;
		}

		private function setStatus ($value)
		{
			$this->attributes['status'] = $value;
		}

		private function setLongitude ($value)
		{
			$this->attributes['longitude'] = $value;
		}

		private function setLatitude ($value)
		{
			$this->attributes['latitude'] = $value;
		}

		private function setExpYear ($value)
		{
			$this->attributes['exp_year'] = $value;
		}

		private function setBio ($value)
		{
			$this->attributes['bio'] = $value;
		}
		public function getSupplierByID ($id)
		{

			$supplierObject = SupplierModel::where ( 'id' , $id )->first ();

			return $supplierObject;

		}
		public function getSupplierServicesInSectionByID ($id,$sectionID)
		{

			$supplierObject = SupplierModel::find ($id)->services ;
			$supplierObject=$supplierObject->where('section_model_id',$sectionID);

			return $supplierObject ;

		}
		public function getServicesForAllSupplier($true)
		{
			$listSupplierServices=SupplierModel::with ('services')->get () ;
			return $listSupplierServices;
		}
		public function getSupplierByStatus ($status)
		{
			$listSupplier = SupplierModel::where ( 'status' , $status )->get ();

			return $listSupplier;
		}
		public function getSupplierByPhoneNum ($phone)
		{
			$supplierObject = SupplierModel::where ( 'phone' , $phone )->first ();

			return $supplierObject;
		}

		public function getSupplierBySingleDate ($request)
		{
			$listSupplier = SupplierModel::whereDate ( 'created_at' , '=' , $request )->get ();

			return $listSupplier;
		}

		public function getSupplierByFlightDate ($request)

		{

			$fromDate = date ( $request->input ( 'start_date' ) . ' 00:00:00' , time () );
			$toDate = date ( $request->input ( 'end_date' ) . ' 23:00:40' , time () );

			$listSupplier= SupplierModel::whereBetween ( 'created_at' , [$fromDate , $toDate] )
				->where ( 'status' , $request->input ( 'status' ) )->get ();

			return $listSupplier;
		}

		public function getSupplierByEmail ($request)
		{
			$supplierObject = SupplierModel::where ( 'email' , $request->input ( 'email' ) )->get ();

			return $supplierObject;
		}

		public function getSupplierByEmailAndPhone ($request)
		{
			$supplierObject = SupplierModel::where ( 'phone' , $request->input ( 'phone' ) )->where ( 'email' , $request->input ( 'email' ) )->get ();

			return $supplierObject;
		}
		//getUserByID
		//getUserByStatus
		//getUserByPhoneNumber
		//getUser

		public function getInActiveSupplier ($request)
		{

			$listSupplier = SupplierModel::where ( 'status' , $request->input ( 'status' ) )->get ();

			return $listSupplier;
		}

	}
