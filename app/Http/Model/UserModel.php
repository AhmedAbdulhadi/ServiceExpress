<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Notifications\Notifiable;
	use Laravel\Passport\HasApiTokens;

	class UserModel extends Model
	{
		use HasApiTokens , Notifiable;
		protected $table = 'users';
		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array
		 */


		protected $fillable = [
			'name' , 'email' , 'password' , 'phone' , 'status' , 'created_at' , 'start_date' , 'end_date'
		];

		/**
		 * The attributes that should be hidden for arrays.
		 *
		 * @var array
		 */
		protected $hidden = [
			'password' , 'pivot'
		];

		public function address ()
		{

			return $this->belongsToMany  (adressModel::class,'address_user');
		}

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

		public function getUserByID ($id)
		{

			$userObject = UserModel::where ( 'id' , $id )->first ();

			return $userObject;

		}

		public function getUsersByStatus ($status)
		{

			$listUsers = UserModel::with ('address')->get ();

			return $listUsers;
		}



		public function getUserByPhoneNum ($phone)
		{
			$userObject = UserModel::where ( 'phone' , $phone )->first ();

			return $userObject;
		}

		public function getUserBySingleDate ($request)
		{
			$listUsers = UserModel::whereDate ( 'created_at' , '=' , $request )->get ();

			return $listUsers;
		}

		public function getUsersByFlightDate ($request)

		{

			$fromDate = date ( $request->input ( 'start_date' ) . ' 00:00:00' , time () );
			$toDate = date ( $request->input ( 'end_date' ) . ' 23:00:40' , time () );

			$listUsers = UserModel::whereBetween ( 'created_at' , [$fromDate , $toDate] )
				->where ( 'status' , $request->input ( 'status' ) )->get ();

			return $listUsers;
		}

		public function getUserByEmail ($request)
		{
			$userObject = UserModel::where ( 'email' , $request->input ( 'email' ) )->get ();

			return $userObject;
		}

		public function getUserByEmailAndPhone ($request)
		{
			$userObject = UserModel::where ( 'phone' , $request->input ( 'phone' ) )->where ( 'email' , $request->input ( 'email' ) )->get ();

			return $userObject;
		}

		public function getInActiveUsers ($request)
		{

			$listUsers = UserModel::where ( 'status' , $request->input ( 'status' ) )->get ();

			return $listUsers;
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

		public function setCreatedAt ($value)
		{
			$this->attributes['created_at'] = $value;
		}

		public function setUpdatedAt($value)
		{
			$this->attributes['updated_at'] = $value;
		}


	}
