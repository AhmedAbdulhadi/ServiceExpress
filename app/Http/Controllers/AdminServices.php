<?php
	/**
	 * Created by PhpStorm.
	 * User: dark-
	 * Date: 10/24/2017
	 * Time: 4:17 PM
	 */

	namespace App\Http\Controllers;

	use App\login;
	use App\Admin;
	use Carbon\Carbon;
	use Illuminate\Contracts\Pagination\Paginator;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\Response;
	use Novent\Transformers\AdminTransfom;

	use \Validator;

	class AdminServices extends Controller
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

		protected $userTrans;
		/**
		 * @var int
		 */
		protected $statusCode = 200;

		public function __construct (AdminTransfom $userTrans)
		{
			$this->userTrans = $userTrans;
			//$this->middleware('auth.basic', ['only' => 'store']);

		}


		/**
		 * @param string $massage
		 * @return mixed
		 */
		public function respondInternalError ($massage = 'Internal Error')
		{
			// to show 500 error
			return $this->setStatusCode ( self::HTTP_INTERNAL_SERVER_ERROR )->respondWithError ( $massage );
		}

		/**
		 * @param $massage
		 * @return mixed
		 */
		public function respondWithError ($massage , $status = null)
		{
			//response without data
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
			//response created with returned id
			return $this->setStatusCode ( self::HTTP_CREATED )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status )
				, 'code' => $this->statusCode ,
				'data' => $id ,
			] );
		}

		public function responedFound200ForOneUserToken ($massage , $status , $data , $token)
		{
			// with token
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
				'data' => $data , 'token' => $token ,

			] );
		}

		public function respondwithdata ($massage , $status , $data)
		{
			//error with massage with data
			return $this->setStatusCode ( self::HTTP_BAD_REQUEST )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
				'data' => $data
			] );
		}

		public function getAllUser ()
		{
// to get all admins with status true

			$users = Admin::where ( 'status' , true )->get ();

			return $this->responedFound200
			( 'Admin found' , self::success , $this->userTrans->transformCollection ( $users->all () ) );

		}

		public function responedFound200 ($massage , $status , $data)
		{
			//created
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
			// to get one admin

			$users = Admin::where ( 'id' , $id )->where ( 'status' , true )->first ();


			if ( !$users ) {
				return $this->respondNotFound ( 'Admin dose not found' );

			}


			return $this->responedFound200ForOneUser ( 'Admin found' , self::success , $this->userTrans->transform ( $users ) );

		}

		/**
		 * @param string $massage
		 * @return mixed
		 */

		public function respondNotFound ($massage = 'Not Found !')
		{
			//to response with not  found error
			//return $this->setStatusCode (self::HTTP_NOT_FOUND)->respondWithError($massage);
			return $this->setStatusCode ( self::HTTP_NOT_FOUND )->respondWithError ( $massage , 'fail' );
		}

		public function responedFound200ForOneUser ($massage , $status , $data)
		{
			// with one user response
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
			//to create admin
			$type = '2';

			$rules = array (
				'name' => 'required|regex:/^(?!.*\d)[a-z\p{Arabic}\s]+$/iu|min:3|max:30' ,
				'email' => 'required|email|unique:users|unique:suppliers|unique:logins|unique:admins' ,
				'phone' => 'required|phone:JO|unique:users|unique:suppliers|unique:admins' ,
				//				'phonefield' => 'phone:JO,BE,mobile',
				'password' => 'required|min:8|max:30'
			);
			$messages = array (
				'name.regex' => 'The name is invalid. || يرجى ادخال الاسم بالغة الانجليزية او العربية' ,
				'name.required' => 'The name is required. ||  يرجى ادخال الاسم بالغة الانجليزية او العربية' ,
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
				'password.min' => 'the password min is 8 . || يرجى ادخال ما يزيد عن 8 احرف لكلمة السر' ,
				'password.max' => 'the password max is 30 . || يرجى ادخال ما لا يزيد عن 30 حرف لكلمة السر' ,
				//				'phone.phone:KS' => ' enter phone number with jordan code 966'
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();


			if ( $validator->fails () )
				//				return $this->setStatusCode (404)->respondwithErrorMessage (
				//					'some thing wrong', 'fail', $errors->first('name'));

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


			else




				$user = Admin::create ( [
					'name' => $request->input ( 'name' ) ,
					'email' => $request->input ( 'email' ) ,
					'phone' => $request->input ( 'phone' ) ,
					'password' => bcrypt ( $request->input ( 'password' ) ) ,
					//missing long wo lat

				] )->id;

			login::create ( [
				'email' => $request->input ( 'email' ) ,
				'password' => bcrypt ( $request->input ( 'password' ) ) ,
				'type' => $type
			] );


			return $this->responedCreated200 ( ' successfully Created !' , self::success , $user );

		}

		public function respondwithErrorMessage ($status , $data)
		{
			// with error massage
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

					'status' => $this->status ( $status ) ,
					'code' => $this->statusCode ,

				] );
		}

		public function responedCreated200 ($massage , $status , $id = null)
		{
			//created response
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'id' => $id ,
				'status' => $this->status ( $status )
				, 'code' => $this->statusCode
			] );
		}

		public function delete_user ($id)
		{
			// to delete admin
			$now = Carbon::now ( 'GMT+2' );
			$user = admin::find ( $id );
			if ( !$user ) {
				return $this->respondWithError ( 'Supplier for id:' . $id . ' is not Exiting' , self::fail );
			} else {
				DB::table ( 'admins' )
					->where ( 'id' , $id )
					->update ( ['status' => false , 'deleted_at' => $now] );
				$email = DB::table ( 'admins' )
					->where ( 'id' , $id )->first ();
				DB::table ( 'logins' )->where ( 'email' , $email->email )
					->update ( ['status' => false , 'deleted_at' => $now] );

				return $this->responed_Destroy200 ( 'admin was deleted ' , self::success );

			}


		}

		public function responed_Destroy200 ($massage , $status)
		{
			// to response for delete admin
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status )
				, 'code' => $this->statusCode
			] );
		}

		public function update_user (Request $request , $id)
		{
			// to update admin
			$rules = array (
				'name' => 'regex:/^(?!.*\d)[a-z\p{Arabic}\s]+$/iu|min:3|max:30' ,
				'email' => 'email|unique:users|unique:suppliers' ,
				'phone' => 'phone:JO|unique:users|unique:admins' ,

				'password' => 'min:8|max:30'
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
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
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


			$findid = admin::find ( $id );

			$name = $request->input ( 'name' );
			$email = $request->input ( 'email' );
			$phone = $request->input ( 'phone' );
			$password = $request->input ( 'password' );
			$now = Carbon::now ( 'GMT+2' );

			$admin = Admin::where ( 'email' , $email );
			$email_exists = $admin->first ();// !== null
			$email_for_admin = $admin->where ( 'id' , $id )->first ();

			if ( !$findid )
				return $this->respondWithError ( 'admin not found ' , self::fail );
			else {

				$new_name = DB::table ( 'admins' )->where ( 'id' , $id )->first ();

				if ( $new_name->name !== $name and $name !== null ) {
					DB::table ( 'admins' )
						->where ( 'id' , $id )
						->update ( ['name' => $request->input ( 'name' ) , 'updated_at' => $now] );

				}

				if ( !$email_exists or $email_for_admin ) {
					if ( ($email !== null) ) {

						//to find id in supplier and edit email
						DB::table ( 'admins' )
							->where ( 'id' , $id )
							->update ( ['email' => $request->input ( 'email' ) , 'updated_at' => $now] );
						//to edit supplier email in logins table
						DB::table ( 'logins' )->where ( 'email' , $new_name->email )
							->update ( ['email' => $request->input ( 'email' ) , 'updated_at' => $now] );

					}
				} else return $this->respondwithErrorMessage ( self::fail , 'this email is exists || هذا البريد الالكتروني موجود ' );


				if ( $new_name->phone !== $phone and $phone !== null ) {
					DB::table ( 'admins' )
						->where ( 'id' , $id )
						->update ( ['phone' => $request->input ( 'phone' ) , 'updated_at' => $now] );

				}
				if ( $new_name->password !== $password and $password !== null ) {

					//to get email supplier to chane password in logins table
					$email = DB::table ( 'admins' )
						->where ( 'id' , $id )->first ();

					DB::table ( 'admins' )
						->where ( 'id' , $id )
						->update ( ['password' => bcrypt ( $request->input ( 'password' ) ) ,
							'updated_at' => $now] );

					//to edit supplier password in logins table
					DB::table ( 'logins' )->where ( 'email' , $email->email )
						->update ( ['password' => bcrypt ( $request->input ( 'password' ) ) ,
							'updated_at' => $now] );


				}
				if ( ($request->input ( 'status' ) == 0 or $request->input ( 'status' )) == 1 and $request->input ( 'status' ) !== null ) {
					DB::table ( 'admins' )
						->where ( 'id' , $id )
						->update ( ['status' => $request->input ( 'status' ) , 'updated_at' => $now] );
				}


				$data = admin::find ( $id );

				return $this->responedFound200ForOneUser ( 'admin was updated' , self::success , $this->userTrans->transform ( $data ) );

			}

		}

		public function get_phone_Query (Request $request)
		{
			//get admin with phone number
			$rules = array (

				'phone' => 'required|phone:JO' ,

			);
			$messages = array (
				'phone.phone:JO' => ' enter phone number with jordan code 962' ,
				'phone.phone' => ' enter valid phone number such as 962785555555' ,
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );

			$errors = $validator->errors ();


			if ( $validator->fails () )


				if ( $errors->first ( 'phone' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'phone' ) );


			$phone = $request->input ( 'phone' );
			$user_phone = admin::where ( 'phone' , $phone )->where ( 'status' , true )->first ();
			$user_phoneDeac = admin::where ( 'phone' , $phone )->where ( 'status' , false )->first ();

			if ( $user_phone == null and $user_phoneDeac == null )
				return $this->respondWithError ( 'admin Not Found' , self::fail );
			elseif ( $user_phoneDeac )
				return $this->respondDeactivate ( 'this admin is deactivate' , self::fail );
			else
				return $this->responedFound200ForOneUser ( 'admin found' , self::success ,
					$this->userTrans->transform ( $user_phone ) );
		}

		public function respondDeactivate ($massage , $status = null)
		{
			//this admin is not active
			return $this->setStatusCode ( self::HTTP_FORBIDDEN )->respond ( [

				'massage' => $massage ,
				'code' => $this->statusCode
				, 'status' => $this->status ( $status )

			] );
		}

		public function get_one_user_date (Request $request)
		{

			// get admin  with date


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

			$user = admin::whereDate ( 'created_at' , '=' , date ( "Y-m-d" , strtotime ( Input::get ( 'date' ) ) ) )->first ();

			if ( !$user )
				return $this->respondWithError ( 'admin not found' , self::fail );
			else
				return $this->responedFound200ForOneUser ( 'admin found' , self::success ,
					$this->userTrans->transform ( $user ) );
		}

		public function get_date_Query (Request $request)
		{
			//to get admin with flight date
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
			$user = admin::whereBetween ( 'created_at' , [$start , $end] )->where ( 'status' , Input::get ( 'status' ) )->get ();
			if ( $user->first () === null )
				return $this->respondWithError ( 'admin not found' , self::fail );
			else
				return $this->responedFound200 ( 'admin found' , self::success ,
					$this->userTrans->transformCollection ( $user->toArray () ) );


		}

		public function get_user_by_email (Request $request)
		{
			//get admin by email
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
			$user_compare_email = admin::where ( 'email' , $user_email )->where ( 'status' , true )->first ();
			$user_deactive = admin::where ( 'email' , $user_email )->where ( 'status' , false )->first ();
			if ( $user_compare_email == null and $user_deactive == null )
				return $this->respondWithError ( 'admin Not Found' , self::fail );
			elseif ( $user_deactive )
				return $this->respondDeactivate ( 'this admin is deactivate' , self::fail );
			else
				return $this->responedFound200ForOneUser ( 'admin found' , self::success ,
					$this->userTrans->transform ( $user_compare_email ) );

		}

		public function get_user_email_phonenum (Request $request)
		{
			$rules = array (
				'email' => 'required|email' ,
				'phone' => 'required|phone:JO' ,
			);
			$messages = array (
				'email.required' => ' must enter a email ' ,
				'email.email' => ' enter valid email' ,
				'phone.phone:JO' => ' enter phone number with jordan code 962' ,
				'phone.phone' => ' enter valid phone number such as 962785555555' ,

			);
			$validator = Validator::make ( $request->all () , $rules , $messages );

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

			$user_compare_email_phone = admin::where ( 'email' , $user_email )->where ( 'phone' , $user_phone )
				->where ( 'status' , true )->first ();

			$user_deactive = admin::where ( 'email' , $user_email )->where ( 'phone' , $user_phone )
				->where ( 'status' , false )->first ();
//						dd($user_deactive);
			if ( $user_compare_email_phone == null and $user_deactive == null )
				return $this->respondWithError ( 'admin Not Found' , self::fail );
			elseif ( $user_deactive )
				return $this->respondDeactivate ( 'this admin is deactivate' , self::fail );
			else
				return $this->responedFound200ForOneUser ( 'admin found' , self::success ,
					$this->userTrans->transform ( $user_compare_email_phone ) );


		}

		public function get_inactive_users (Request $request)
		{
			//get inactive admin
			$status = $request->input ( 'status' );
			$users = admin::where ( 'status' , $status )->get ();


			return $this->responedFound200
			( 'admin found' , self::success , $this->userTrans->transformCollection ( $users->all () ) );
		}


	}