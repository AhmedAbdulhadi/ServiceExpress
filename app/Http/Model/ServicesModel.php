<?php

	namespace App;

	use App\Http\Controllers\ServicesSer;
	use App\Http\Controllers\UserServices;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Notifications\Notifiable;
	use Illuminate\Support\Facades\DB;

	class ServicesModel extends Model
	{
		use Notifiable;
		public $table = "services";
		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array
		 */
		protected $fillable = [
			'name_ar' , 'name_en' , 'desc_ar' , 'desc_en' , 'section_id' , 'service_id' ,
			'image' , 'status' , 'services_model_id' , 'supplier_model_id'
		];
		protected $hidden = [];

		public static function getSupplierWithServicesID ($serviceID)
		{
			/* GET  SERVICES SUPPLIERS BY SERVICE ID*/
			$servicesList = ServicesModel::with ( 'suppliers' )
				->where ( 'id' , $serviceID )->where ( 'status' , 1 )->get ();

			return $servicesList;

		}

		public function sections ()
		{
			return $this->belongsTo ( SectionModel::class );

		}

		public function suppliers ()
		{

			return $this->belongsToMany ( SupplierModel::class , 'services_suppliers' )->withTimestamps ();

		}

		public function getCreatedAt ()
		{
			return $this->created_at;

		}

		public function getUpdatedAt ()
		{
			return $this->updated_at;

		}

		public function getNameEN ()
		{
			return $this->name_en;

		}

		public function getNameAr ()
		{
			return $this->name_ar;

		}

		public function getDesc_En ()
		{
			return $this->desc_en;
		}

		public function getDescAr ()
		{
			return $this->desc_ar;
		}

		public function getImage ()
		{
			return $this->image;
		}

		public function getStatus ()
		{
			return $this->status;
		}

		public function getServicesById ($id)
		{

			$serviceObject = ServicesModel::where ( 'id' , $id )->first ();

			return $serviceObject;

		}

		public function getServicesByIdWithStatus ($id , $status)
		{

			$serviceObject = ServicesModel::where ( 'id' , $id )->where ( 'status' , $status )->first ();

			return $serviceObject;

		}

//		protected $dates = ['created_at'];
//		const CREATED_AT = 'created_at';
//		public function getUserByPhoneNum ($phone)
//		{
//			$userObject = UserModel::where ( 'phone' , $phone )->first ();
//
//			return $userObject;
//		}
//
//		public function getUserBySingleDate ($request)
//		{
//			$listUsers = UserModel::whereDate ( 'created_at' , '=' , $request )->get ();
//
//			return $listUsers;
//		}
//
//		public function getUsersByFlightDate ($request)
//
//		{
//
//			$fromDate = date ( $request->input ( 'start_date' ) . ' 00:00:00' , time () );
//			$toDate = date ( $request->input ( 'end_date' ) . ' 23:00:40' , time () );
//
//			$listUsers = UserModel::whereBetween ( 'created_at' , [$fromDate , $toDate] )
//				->where ( 'status' , $request->input ( 'status' ) )->get ();
//
//			return $listUsers;
//		}
//
//		public function getUserByEmail ($request)
//		{
//			$userObject = UserModel::where ( 'email' , $request->input ( 'email' ) )->get ();
//
//			return $userObject;
//		}
//		public function getUserByEmailAndPhone ($request)
//		{
//			$userObject = UserModel::where ( 'phone' , $request->input ( 'phone' ) )->where ( 'email' , $request->input ( 'email' ) )->get ();
//			return $userObject;
//		}
		//getUserByID
		//getUserByStatus
		//getUserByPhoneNumber
		//getUser

		public function getServicesByStatus ($status)
		{
			$listServices = ServicesModel::where ( 'status' , $status )->get ();

			return $listServices;
		}

		public function setCreatedAt ($value)
		{
			$this->attributes['created_at'] = $value;
		}

		public function setUpdatedAt ($value)
		{
			$this->attributes['updated_at'] = $value;
		}

		private function setNameEn ($value)
		{
			$this->attributes['name_en'] = $value;

		}

		private function setNameAr ($value)
		{
			$this->attributes['name_ar'] = $value;
		}

		private function setDescAr ($value)
		{
			$this->attributes['desc_ar'] = $value;
		}

		private function setDescEn ($value)
		{
			$this->attributes['desc_en'] = $value;
		}

		private function setStatus ($value)
		{
			$this->attributes['status'] = $value;
		}

	}
