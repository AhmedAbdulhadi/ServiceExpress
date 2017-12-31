<?php
	/**
	 * Created by PhpStorm.
	 * User: dark-
	 * Date: 9/17/2017
	 * Time: 10:28 AM
	 */

	namespace App\Http\Controllers;

	use App\address;
	use App\login;
	use App\User;
	use Carbon\Carbon;
	use Illuminate\Contracts\Pagination\Paginator;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\Response;
	use Novent\Transformers\addressTrans;
	use Novent\Transformers\AddressTransfomer;
	use Novent\Transformers\userAddress;
	use Novent\Transformers\userTransfomer;
	use \Validator;
	use Illuminate\Support\Facades\Auth;


	class UserServices extends Controller
	{
		const HTTP_OK = 200;
		const HTTP_CREATED = 201;

		//HTML code 200
		const HTTP_ACCEPTED = 202;
		const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
		const HTTP_NO_CONTENT = 204;
		const HTTP_BAD_REQUEST = 400;
		const HTTP_UNAUTHORIZED = 401;

		//HTML code 400
		const HTTP_PAYMENT_REQUIRED = 402;
		const HTTP_FORBIDDEN = 403;
		const HTTP_NOT_FOUND = 404;
		const HTTP_INTERNAL_SERVER_ERROR = 500;
		const HTTP_NOT_IMPLEMENTED = 501;

		//HTML code 500
		const HTTP_BAD_GATEWAY = 502;
		const HTTP_SERVICE_UNAVAILABLE = 503;
		const HTTP_GATEWAY_TIMEOUT = 504;
		const success = 'success';
		const fail = 'fail';
		/**
		 * @var  Novent\Transformers\userTransfomer
		 */
		protected $userTrans;
		protected $userAddres;
		/**
		 * @var int
		 */
		protected $statusCode = 400;

		public function __construct (userTransfomer $userTrans , addressTrans $userAddress)
		{
			$this->userTrans = $userTrans;
			$this->userAddres = $userAddress;
			//$this->middleware('auth.basic', ['only' => 'store']);

		}


		/**
		 * @param string $massage
		 * @return mixed
		 */
		public function respondInternalError ($massage = 'Internal Error')
		{
			return $this->setStatusCode ( self::HTTP_INTERNAL_SERVER_ERROR )->respondWithError ( $massage );
		}

		/**
		 * @param $massage
		 * @return mixed
		 */
		public function respondWithError ($massage , $status = null)
		{
			$splitName = explode ( '||' , $massage , 2 );

			$first = $splitName[0];
			$last = !empty( $splitName[1] ) ? $splitName[1] : '';
			if ( $last )
				return $this->setStatusCode ( self::HTTP_BAD_REQUEST )->respond ( [
					'massage' => $first ,
					'massage_ar' => $last ,
					'status' => $this->status ( $status ) ,
					'code' => $this->statusCode ,

				] );
			else
				return $this->setStatusCode ( self::HTTP_BAD_REQUEST )->respond ( [
					'massage' => $first ,
//				'massage_ar'=>$last,
					'status' => $this->status ( $status ) ,
					'code' => $this->statusCode ,

				] );

		}

		/**
		 * @param $data
		 * @param array $headers
		 * @return mixed
		 */


		public function respond ($data , $headers = [])
		{
//			return Response::json ( $data , $this->getStatusCode () , $headers );
			return response ()->json ( $data , $this->getStatusCode () , $headers );
		}

		/**
		 * @return  mixed
		 */
		public function getStatusCode ()
		{
			return $this->statusCode;
		}

		/**
		 * @param mixed $statusCode
		 * @return $this
		 */

		public function setStatusCode ($statusCode)
		{
			$this->statusCode = $statusCode;

			return $this;
		}

		public function status ($status)
		{
			return $status;
		}

		/**
		 * @param $massage
		 * @return mixed
		 */
		public function responedCreated ($massage , $status , $id)
		{
			return $this->setStatusCode ( self::HTTP_CREATED )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status )
				, 'code' => $this->statusCode ,
				'data' => $id ,
			] );
		}

		public function responedFound200ForOneUserToken ($massage , $status , $data , $token)
		{
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
				'data' => $data , 'token' => $token ,

			] );
		}

		public function respondwithdata ($massage , $status , $data)
		{
			return $this->setStatusCode ( self::HTTP_BAD_REQUEST )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
				'data' => $data
			] );
		}

		public function getAllUser ()
		{
dd(Auth::user ()->getAuthIdentifier ());
			// get all users with status true
			$users = User::where ( 'status' , true )->get ();


			$address = User::with ( 'address' )->get ()->toArray ();

			return $this->responedFound200
			( 'users found' , self::success , $this->userTrans->transformCollection ( $address ) );

		}

		public function responedFound200 ($massage , $status , $data)
		{
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
				'size' => count ( $data ) ,
				'data' => $data
			] );
		}

		public function get_one_user ($id = null)
		{
			// get 1 user with address

			$users = User::where ( 'id' , $id )->where ( 'status' , true )->first ();


			if ( !$users ) {
				return $this->respondNotFound ( 'user dose not found' );

			}

			$address = User::with ( 'address' )->where ( 'id' , $id )->get ()->first ();

			$x = $address->toArray ();

			return $this->responedFound200ForOneUser ( 'user found' , self::success , $x );


		}

		/**
		 * @param string $massage
		 * @return mixed
		 */

		public function respondNotFound ($massage = 'Not Found !')
		{
			//return $this->setStatusCode (self::HTTP_NOT_FOUND)->respondWithError($massage);
			return $this->setStatusCode ( self::HTTP_NOT_FOUND )->respondWithError ( $massage , 'fail' );
		}

		public function responedFound200ForOneUser ($massage , $status , $data)
		{
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
//						'size'=>count($data),
				'data' => $data
			] );
		}

		public function create_user (Request $request)
		{
			//create user

			$type = '0';


			$rules = array (
				'name' => 'required|regex:/^(?!.*\d)[a-z\p{Arabic}\s]+$/iu|min:3|max:30' ,
				'email' => 'required|email|unique:users|unique:suppliers|unique:admins' ,
				'phone' => 'required|phone:JO|unique:users|unique:suppliers' ,
				//				'phonefield' => 'phone:JO,BE,mobile',
				'password' => 'required|min:8|max:30'
			);
			$messages = array (
				'name.regex' => 'The name is invalid. || يرجى ادخال الاسم بلبغة الانجليزية او العربية' ,
				'name.required' => 'The name is required. || يرجى ادخال الاسم بالغة الانجليزية' ,
				'name.min' => 'The name min is 3. || اقل عدد احرف للأسم 3' ,
				'name.max' => 'The name min is 30 || اكثر عدد احرف مسموح هو 30' ,

				'email.required' => 'The email is important for my life || البريد الالكتروني مهم جداً ' ,
				'email.email' => 'take your time and add Real email || الرجاء ادخال بريد الالكتروني فعال ' ,
				'email.unique' => 'this email is already exiting || البريد الالكتروني مستخدم بالفعل' ,

				'phone.required' => 'The phone is important for my life || يرجى ادخال رقم الهاتف' ,
				'phone.unique' => 'this phone number is already exiting || رقم الهاتف مستخدم بالفعل' ,
				'phone.phone:JO' => ' enter phone number with jordan code 962 || الرجاء ادخال رقم يبدأ 962 الاردن' ,
				'phone.phone' => ' enter valid phone number such as 962785555555 || الرجاء ا دخال رقم صحيح مثل 962785555555 ' ,

				'password.required' => 'the password is required . || يرجى ادخال كلمة السر' ,
				'password.min' => 'the password min is 8 . || يرجى ادخال ما يزيد عن 8 احرف لكلمة المرور' ,
				'password.max' => 'the password max is 30 . || يرجى ادخال ما لا يزيد عن 30 حرف لكلمة المرور' ,
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();


			if ( $validator->fails () )

				if ( $errors->first ( 'name' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'name' ) );
			if ( $errors->first ( 'email' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'email' ) );
			if ( $errors->first ( 'phone' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'phone' ) );
			if ( $errors->first ( 'password' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'password' ) );


			else {
				$user = User::create ( [
					'name' => $request->input ( 'name' ) ,
					'email' => $request->input ( 'email' ) ,
					'phone' => $request->input ( 'phone' ) ,
					'password' => bcrypt ( $request->input ( 'password' ) ) ,

				] )->id;

				login::create ( [
					'email' => $request->input ( 'email' ) ,
					'password' => bcrypt ( $request->input ( 'password' ) ) ,
					'type' => $type
				] );

				if ( Auth::attempt ( ['email' => request ( 'email' ) , 'password' => request ( 'password' )] ) ) {

					$users = Auth::user ();

					$this->content['token'] = $users->createToken ( 'Noventapp' )->accessToken;
				}

				$user_i = $this->return_r ( $user , $this->content );

				return $this->responedCreated200S ( ' successfully Created !' , self::success , $user_i );
			}
		}

		public function respondwithErrorMessage ($status , $data)
		{
			$splitName = explode ( '||' , $data , 2 );

			$first = $splitName[0];
			$last = !empty( $splitName[1] ) ? $splitName[1] : '';
			if ( $last )
				return $this->setStatusCode ( self::HTTP_BAD_REQUEST )->respond ( [
					'massage' => $first ,
					'massage_ar' => $last ,
					'status' => $this->status ( $status ) ,
					'code' => $this->statusCode ,

				] );
			else
				return $this->setStatusCode ( self::HTTP_BAD_REQUEST )->respond ( [
					'massage' => $first ,
//				'massage_ar'=>$last,
					'status' => $this->status ( $status ) ,
					'code' => $this->statusCode ,

				] );
		}

		private function return_r ($x , $y)
		{

			//to spacifay and get the needed result
			//$x for user $y for token
			return [
				'user_id' => $x ,
				'token' => $y['token'],
				'path' =>'com.example.novapp_tasneem.serviceexpress.userFragments.userProfileFragment'
			];

		}

		public function responedCreated200S ($massage , $status , $data)
		{
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status )
				, 'code' => $this->statusCode ,
				'data' => $data ,
			] );
		}

		public function responedCreated200 ($massage , $status , $id = null)
		{
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'id' => $id ,
				'status' => $this->status ( $status )
				, 'code' => $this->statusCode
			] );
		}

		public function delete_user ($id)
		{
			//delete users and status will be 0
			$now = Carbon::now ( 'GMT+2' );
			$user = User::find ( $id );
			if ( !$user ) {
				return $this->respondWithError ( 'User for id:' . $id . ' is not Exiting' , self::fail );
			} else {
				DB::table ( 'users' )
					->where ( 'id' , $id )
					->update ( ['status' => false , 'deleted_at' => $now] );
				$email = DB::table ( 'users' )
					->where ( 'id' , $id )->first ();
				DB::table ( 'logins' )->where ( 'email' , $email->email )
					->update ( ['status' => false , 'deleted_at' => $now] );

//					->update(['deleted_at'=>Carbon::now('GMT+3')]);
				return $this->responed_Destroy200 ( 'user was deleted ' , self::success );

			}


		}

		public function responed_Destroy200 ($massage , $status)
		{
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status )
				, 'code' => $this->statusCode
			] );
		}

		public function update_user (Request $request , $id)
		{
			// update user
			$rules = array (
				'name' => 'regex:/^(?!.*\d)[a-z\p{Arabic}\s]+$/iu|min:3|max:30' ,
				'email' => 'email|unique:admins|unique:suppliers' ,
				'phone' => 'phone:JO|unique:users|unique:suppliers' ,
				'password' => 'min:8|max:30' ,

				'address.longitude' => 'numeric' ,
				'address.latitude' => 'numeric' ,
				'address.city' => 'string' ,
				'address.street' => 'string' ,
				'address.country' => 'string' ,
				'address.neighborhood' => 'string' ,
				'address.building_number' => 'integer' ,
				'address.apartment_number' => 'integer' ,
				'address.floor' => 'integer' ,

			);
			$messages = array (
				'name.regex' => 'The name is invalid. || يرجى ادخال الاسم بالغة الانجليزية او العربية' ,
				'name.min' => 'The name min is 3. || اقل عدد احرف للأسم 3' ,
				'name.max' => 'The name min is 30 || اكثر عدد احرف مسموح هو 30' ,

				'email.email' => 'take your time and add Real email || الرجاء ادخال بريد الالكتروني فعال ' ,
				'email.unique' => 'this email is already exiting || البريد الالكتروني مستخدم بالفعل' ,

				'phone.unique' => 'this phone number is already exiting || رقم الهاتف مستخدم بالفعل' ,
				'phone.phone:JO' => ' enter phone number with jordan code 962 || الرجاء ادخال رقم يبدأ 962 الاردن' ,
				'phone.phone' => ' enter valid phone number such as 962785555555 || الرجاء ا دخال رقم صحيح مثل 962785555555 ' ,

				'password.min' => 'the password min is 8 . || يرجى ادخال ما يزيد عن 8 احرف لكلمة المرور' ,
				'password.max' => 'the password max is 30 . || يرجى ادخال ما لا يزيد عن 30 حرف لكلمة المرور' ,
				'address.longitude.numeric' => 'Enter valid longitude ||  يرجى ادخال خط العرض بشكل صحيح' ,

//				'latitude.required' => 'latitude is required || يرجى ادخال خط الطول ' ,
				'address.latitude.numeric' => 'Enter valid latitude || يرجى ادخال خط الطول بشكل الصحيح' ,

				'address.city.string' => 'Enter valid city || يرجى ادخال اسم المدينة بالشكل الصحيح' ,
//				'city.required' => 'city is required  || يرجى ادخال اسم المدينة' ,

				'address.street.string' => 'Enter valid street || يرجى ادخال اسم الشارع بشكل الصحيح' ,
//				'street.required' => 'street is required || يرجى ادخال اسم الشارع'  ,

				'address.country.string' => 'Enter valid country || يرجى ادخال اسم الدولة بشكل صحيح ' ,
//				'country.required' => 'country is required || يرجى ادخال اسم الدولة' ,

				'address.neighborhood.string' => 'Enter valid number for neighborhood || يرجى ادخال رقم الحي رقم صحيح' ,
				'address.building_number.integer' => 'Enter valid number for building_number || يرجى ادخال رقم المبنى بشكل الصحيح' ,
				'address.apartment_number.integer' => 'Enter valid number for apartment_number || يرجى ادخال رقم الشقة بشكل الصحيح' ,
				'address.floor.integer' => 'Enter valid number for floor || يرجى ادخال رقم الطابق بشكل صحيح' ,

			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'name' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'name' ) );
			if ( $errors->first ( 'email' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'email' ) );
			if ( $errors->first ( 'phone' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'phone' ) );
			if ( $errors->first ( 'password' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'password' ) );
			if ( $errors->first ( 'address.longitude' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'address.longitude' ) );
			if ( $errors->first ( 'address.latitude' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'address.latitude' ) );
			if ( $errors->first ( 'address.city' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'address.city' ) );
			if ( $errors->first ( 'address.street' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'address.street' ) );
			if ( $errors->first ( 'address.country' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'address.country' ) );

			if ( $errors->first ( 'address.building_number' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'address.building_number' ) );

			if ( $errors->first ( 'address.apartment_number' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'address.apartment_number' ) );

			if ( $errors->first ( 'address.floor' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'address.floor' ) );



			$findid = User::find ( $id );

			$name = $request->input ( 'name' );
			$email = $request->input ( 'email' );
			$phone = $request->input ( 'phone' );
			$password = $request->input ( 'password' );
			$status = $request->input ( 'status' );
			$address = $request->input ( 'address' );
			$now = Carbon::now ( 'GMT+2' );

			$user = User::where ( 'email' , $email );
			$email_exists = $user->first ();// !== null
			$email_for_user = $user->where ( 'id' , $id )->first ();

			$user_address = $findid->address ()->get ()->first ();
//				dd($user_address->first()->toArray());
//			return $user_address[0]['id'];
//			die();
//			dd($user_address->toArray ());
			if ( !$findid )
				return $this->respondWithError ( 'User not found ' , self::fail );
			else {


				$new_name = DB::table ( 'users' )->where ( 'id' , $id )->first ();
				//			var_dump($new_name->email);
				if ( $new_name->name !== $name and $name !== null ) {
					DB::table ( 'users' )
						->where ( 'id' , $id )
						->update ( ['name' => $request->input ( 'name' ) , 'updated_at' => $now] );
				}

				if ( !$email_exists or $email_for_user ) {
					if ( ($email !== null) ) {
						//to get email address for supplier with the id


						//to find id in supplier and edit email
						DB::table ( 'users' )
							->where ( 'id' , $id )
							->update ( ['email' => $request->input ( 'email' ) , 'updated_at' => $now] );

						//to edit supplier email in logins table
						DB::table ( 'logins' )->where ( 'email' , $new_name->email )
							->update ( ['email' => $request->input ( 'email' ) , 'updated_at' => $now] );
					}
				} else return $this->respondwithErrorMessage ( self::fail , 'this email is exists || هذا البريد الالكتروني موجود ' );

				if ( $new_name->phone !== $phone and $phone !== null ) {
					DB::table ( 'users' )
						->where ( 'id' , $id )
						->update ( ['phone' => $request->input ( 'phone' ) , 'updated_at' => $now] );
				}


				if ( $new_name->status !== $status and $status !== null )
					if ( $request->input ( 'status' ) == 0 or $request->input ( 'status' ) == 1 ) {
						DB::table ( 'users' )
							->where ( 'id' , $id )
							->update ( ['status' => $request->input ( 'status' ) , 'updated_at' => $now] );
					} else
						return $this->respondWithError ( 'status neeed to be 0 for false and 1 for ture' , self::fail );
				if ( $new_name->password !== $password and $password !== null ) {

					//to get email user to change password in logins table
					$email = DB::table ( 'users' )
						->where ( 'id' , $id )->first ();

					$e = DB::table ( 'logins' )->where ( 'email' , $email->email )->first ();
					if ( $email and $e !== null ) {
						DB::table ( 'users' )
							->where ( 'id' , $id )
							->update ( ['password' => bcrypt ( $request->input ( 'password' ) ) ,
								'updated_at' => $now] );

						//to edit supplier password in logins table
						DB::table ( 'logins' )->where ( 'email' , $email->email )
							->update ( ['password' => bcrypt ( $request->input ( 'password' ) ) ,
								'updated_at' => $now] );
					} else
						return $this->respondWithError ( 'email already exists' , self::fail );

				}


				if ( $address ) {
					if ( $user_address['address_type'] == 0) //
					{
						$add = address::find ( $user_address['id'] );
						if ( !empty( $address['longitude'] ) ) {
							$add->longitude = $address['longitude'];
							$add->save ();
						}

						if ( !empty( $address['latitude'] ) ) {
							$add->latitude = $address['latitude'];
							$add->save ();
						}

						if ( !empty( $address['street'] ) ) {
							$add->street = $address['street'];
							$add->save ();
						}

						if ( !empty( $address['city'] ) ) {
							$add->city = $address['city'];
							$add->save ();
						}

						if ( !empty( $address['country'] ) ) {
							$add->country = $address['country'];
							$add->save ();
						}

						if ( !empty( $address['building_number'] ) ) {
							$add->building_number = $address['building_number'];
							$add->save ();
						}

						if ( !empty( $address['neighborhood'] ) ) {
							$add->neighborhood = $address['neighborhood'];
							$add->save ();
						}

						if ( !empty( $address['apartment_number'] ) ) {
							$add->apartment_number = $address['apartment_number'];
							$add->save ();
						}

						if ( !empty( $address['floor'] ) ) {
							$add->floor = $address['floor'];
							$add->save ();
						}

						if(!empty($address['status'])){
							$add['status'] = $address['status'];
							$add->save ();
						}

						$add->updated_at = $now;

						$add->save ();
					}
				}
				if(!$user_address)
				{
//						dd($address);
//					$user x = User::find ( $request->input ( 'user_id' ) );
					$user=$findid;
					$add = new address();
					$address_id=$add->insertGetId ($address);
//					dd($x);
					$user->address ()->attach ( $address_id );
//					$add->save ();
				}

				$data = User::find ( $id );

				return $this->responedFound200ForOneUser ( 'user was updated' , self::success , $this->userTrans->transform ( $data ) );

			}

		}

		public function get_phone_Query (Request $request)
		{
			// get user by phone number
			$rules = array (

				'phone' => 'required|phone:JO' ,

			);
			$messages = array (
				'phone.phone:JO' => ' enter phone number with jordan code 962' ,
				'phone.phone' => ' enter valid phone number such as 962785555555' ,
				//				'phone.phone:KS' => ' enter phone number with jordan code 966'
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'phone' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'phone' ) );


			$phone = $request->input ( 'phone' );
			$user_phone = User::where ( 'phone' , $phone )->where ( 'status' , true )->first ();
			$user_phoneDeac = User::where ( 'phone' , $phone )->where ( 'status' , false )->first ();

			if ( $user_phone == null and $user_phoneDeac == null )
				return $this->respondWithError ( 'user Not Found' , self::fail );
			elseif ( $user_phoneDeac )
				return $this->respondDeactivate ( 'this user is deactivate' , self::fail );
			else
				return $this->responedFound200ForOneUser ( 'user found' , self::success ,
					$this->userTrans->transform ( $user_phone ) );
		}

		public function respondDeactivate ($massage , $status = null)
		{
			return $this->setStatusCode ( self::HTTP_FORBIDDEN )->respond ( [

				'massage' => $massage ,
				'code' => $this->statusCode
				, 'status' => $this->status ( $status )

			] );
		}

		public function get_one_user_date (Request $request)
		{
			// get user with date


			$rules = array (
				'date' => 'required|date_format:Y-m-d' ,

			);
			$messages = array (
				'date.date_format' => 'enter valid for  2017-09-05' ,
				'date.required' => 'start date required for me' ,

			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();

			if ( $validator->fails () )
				if ( $errors->first ( 'date' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'date' ) );

			$user = User::whereDate ( 'created_at' , '=' , date ( "Y-m-d" , strtotime ( Input::get ( 'date' ) ) ) )->first ();

			if ( !$user )
				return $this->respondWithError ( 'user not found' , self::fail );
			else    //if ($user !== null)
				return $this->responedFound200ForOneUser ( 'user found' , self::success ,
					$this->userTrans->transform ( $user ) );
		}

		public function get_date_Query (Request $request)
		{
			// get user with flight date
			$rules = array (
				'start_date' => 'required|date_format:Y-m-d' ,
				'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date' ,
				'status' => 'required'
			);
			$messages = array (
				'start_date.date_format' => 'enter valid for  2017-09-05' ,
				'start_date.required' => 'start date required for me' ,
				'status.required' => ' status required' ,
				'end_date.date_format' => 'enter valid for end date   2017-09-05' ,
				'end_date.required' => 'end date required for me' ,
				'start_date.before' => 'start date must be before end date' ,
				'end_date.after' => 'end date must be after start date' ,
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'start_date' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'start_date' ) );
			if ( $errors->first ( 'end_date' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'end_date' ) );
			if ( $errors->first ( 'status' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'status' ) );


			$start = date ( "Y-m-d" , strtotime ( Input::get ( 'start_date' ) ) );
			$end = date ( "Y-m-d" , strtotime ( Input::get ( 'end_date' ) . "+1 day" ) );
			$user = User::whereBetween ( 'created_at' , [$start , $end] )->where ( 'status' , Input::get ( 'status' ) )->get ();
			if ( $user->first () === null )
				return $this->respondWithError ( 'user not found' , self::fail );
			else    //if ($user !== null)
				return $this->responedFound200 ( 'user found' , self::success ,
					$this->userTrans->transformCollection ( $user->toArray () ) );


		}

		public function get_user_by_email (Request $request)
		{
			// get user by email
			$rules = array (

				'email' => 'required|email' ,

			);
			$messages = array (
				'email.required' => ' must enter a email ' ,
				'email.email' => ' enter valid email' ,

			);

			$validator = Validator::make ( $request->all () , $rules , $messages );

			$errors = $validator->errors ();


			if ( $validator->fails () )

				if ( $errors->first ( 'email' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'email' ) );


			$user_email = $request->input ( 'email' );
			$user_compare_email = User::where ( 'email' , $user_email )->where ( 'status' , true )->first ();
			$user_deactive = User::where ( 'email' , $user_email )->where ( 'status' , false )->first ();
			if ( $user_compare_email == null and $user_deactive == null )
				return $this->respondWithError ( 'user Not Found' , self::fail );
			elseif ( $user_deactive )
				return $this->respondDeactivate ( 'this user is deactivate' , self::fail );
			else
				return $this->responedFound200ForOneUser ( 'user found' , self::success ,
					$this->userTrans->transform ( $user_compare_email ) );

		}

		public function get_user_email_phonenum (Request $request)
		{
			// get user by phone and email
			$rules = array (
				'email' => 'required|email' ,
				'phone' => 'required|phone:JO' ,
			);
			$messages = array (
				'email.required' => ' must enter a email ' ,
				'email.email' => ' enter valid email' ,
				'phone.phone:JO' => ' enter phone number with jordan code 962' ,
				'phone.phone' => ' enter valid phone number such as 962785555555' ,
				//				'phone.phone:KS' => ' enter phone number with jordan code 966'
			);
			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'email' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'email' ) );
			if ( $errors->first ( 'phone' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'phone' ) );


			$user_phone = $request->input ( 'phone' );
			$user_email = $request->input ( 'email' );

			$user_compare_email_phone = User::where ( 'email' , $user_email )->where ( 'phone' , $user_phone )
				->where ( 'status' , true )->first ();

			$user_deactive = User::where ( 'email' , $user_email )->where ( 'phone' , $user_phone )
				->where ( 'status' , false )->first ();
//						dd($user_deactive);
			if ( $user_compare_email_phone == null and $user_deactive == null )
				return $this->respondWithError ( 'user Not Found' , self::fail );
			elseif ( $user_deactive )
				return $this->respondDeactivate ( 'this user is deactivate' , self::fail );
			else
				return $this->responedFound200ForOneUser ( 'user found' , self::success ,
					$this->userTrans->transform ( $user_compare_email_phone ) );


		}

		public function get_inactive_users (Request $request)
		{
			// get inactive user
			$status = $request->input ( 'status' );
			$users = User::where ( 'status' , $status )->get ();

			return $this->responedFound200
			( 'users found' , self::success , $this->userTrans->transformCollection ( $users->all () ) );
		}


	}