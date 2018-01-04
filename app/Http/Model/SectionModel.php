<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Notifications\Notifiable;
	class SectionModel extends Model
	{
		protected $table="sections";
		use Notifiable;
		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array
		 */
		protected $fillable = [
			'name_ar','name_en','desc_ar','desc_en','section_id',
			'services_id','image','admin_id','status'
		];
//protected $hidden=[
//	'created_at',"status",
//            "deleted_at",
//            "updated_at",
//];

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

		public function getSectionByID ($id)
		{

			$sectionObject = SectionModel::where ( 'id' , $id )->first ();

			return $sectionObject;

		}

		public function getSectionByStatus ($status)
		{
			$listSection = SectionModel::where ( 'status' , $status )->get ();

			return $listSection;
		}
		public function getSectionByStatuswithRelation ($status)
		{
			$listSection = SectionModel::with ('services')->where ( 'status' , $status )->get ();

			return $listSection;
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
			$this->attributes['phone'] = $value;
		}

		private function setStatus ($value)
		{
			$this->attributes['status'] = $value;
		}

		public function setCreatedAt ($value)
		{
			$this->attributes['created_at'] = $value;
		}

		public function setUpdatedAt($value)
		{
			$this->attributes['updated_at'] = $value;
		}

		public function services ()
		{
//			return $this->belongsToMany ( 'App\ServicesModel' )->withTimestamps ()->withPivot ('services_id','section_id');
			return $this->hasMany (ServicesModel::class);
		}
		public function admin()
		{
			return $this->belongsTo(AdminModel::class);
		}



		//
	}
