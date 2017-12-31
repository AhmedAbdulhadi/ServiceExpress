<?php

	namespace App;

//	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Notifications\Notifiable;
	use Illuminate\Support\Facades\App;
	use Laravel\Passport\HasApiTokens;
	use Illuminate\Foundation\Auth\User as Authenticatable;
	class AdminModel extends Authenticatable
	{
		use Notifiable,HasApiTokens;

		protected $table = 'admins';
		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array
		 */


		protected $fillable = [
			'name' , 'email' , 'password' , 'phone' , 'status' , 'created_at' , 'start_date' , 'end_date','role' ,'admin_id'];
//		const CREATED_AT = 'CREATED_AT';
//		const UPDATED_AT = 'UPDATED_AT';
		/**
		 * The attributes that should be hidden for arrays.
		 *
		 * @var array
		 */
		protected $hidden = [
			'password' , 'pivot'
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

		public function getAdminByID ($id)
		{
//			dd('asdasdq1');
			$adminObject = AdminModel::where ( 'id' , $id )->first ();

			return $adminObject;

		}

		public function getAdminByStatus ($status)
		{
			$listAdmins = AdminModel::where ( 'status' , $status )->get ();

			return $listAdmins;
		}

//		protected $dates = ['created_at'];
//		const CREATED_AT = 'created_at';

		public function getAdminByPhoneNum ($phone)
		{
			$adminObject = AdminModel::where ( 'phone' , $phone )->first ();

			return $adminObject;
		}

		public function getAdminBySingleDate ($request)
		{
			$listAdmin = AdminModel::whereDate ( 'created_at' , '=' , $request )->get ();

			return $listAdmin;
		}

		public function getAdminByFlightDate ($request)

		{

			$fromDate = date ( $request->input ( 'start_date' ) . ' 00:00:00' , time () );
			$toDate = date ( $request->input ( 'end_date' ) . ' 23:00:40' , time () );

			$listAdmin = AdminModel::whereBetween ( 'created_at' , [$fromDate , $toDate] )
				->where ( 'status' , $request->input ( 'status' ) )->get ();

			return $listAdmin;
		}

		public function getUserByEmail ($request)
		{
			$adminObject = AdminModel::where ( 'email' , $request->input ( 'email' ) )->get ();

			return $adminObject;
		}

		public function getAdminByEmailAndPhone ($request)
		{
			$adminObject = AdminModel::where ( 'phone' , $request->input ( 'phone' ) )->where ( 'email' , $request->input ( 'email' ) )->get ();

			return $adminObject;
		}
		//getUserByID
		//getUserByStatus
		//getUserByPhoneNumber
		//getUser

		public function getInActiveAdmin ($request)
		{
//			$status = ;
			$listAdmin = AdminModel::where ( 'status' , $request->input ( 'status' ) )->get ();

			return $listAdmin;
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

		private function setPhone($value)
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

		public function sections()
		{
			return $this->hasMany (SectionModel::class);
		}
	}
