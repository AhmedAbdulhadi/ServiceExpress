<?php

	namespace App\Http\Controllers;

//	use App\User;
	use App\login;
//	use App\Supplier;
	use App\Services;
	use App\Supplier;
	use Carbon\Carbon;
	use Illuminate\Contracts\Pagination\Paginator;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\Response;
	use Novent\Transformers\SupplierTransform;

	use \Validator;

	class SupplierServices extends Controller
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
		 * @var  Novent\Transformers\SupplierTransform
		 */
		protected $userTrans;
		/**
		 * @var int
		 */
		protected $statusCode = 200;

		public function __construct (SupplierTransform $userTrans)
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
			return $this->setStatusCode ( self::HTTP_INTERNAL_SERVER_ERROR )->respondWithError ( $massage );
		}

		/**
		 * @param $massage
		 * @return mixed
		 */
		public function respondWithError ($massage , $status = null)
		{
			return $this->setStatusCode ( self::HTTP_BAD_REQUEST )->respond ( [

				'massage' => $massage ,
				'code' => $this->statusCode
				, 'status' => $this->status ( $status )

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
			return response()->json($data, $this->getStatusCode (),$headers);

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


			$users = Supplier::where ( 'status' , true )->get ();

			/*return	IlluResponse::json([
				'data'=>$this->userTrans->transformCollection  ($users->all ())
			],200);*/
//return $users->all ();
//					dd($users->count ());
			return $this->responedFound200
			( 'Supplier found' , self::success , $this->userTrans->transformCollection ( $users->all () ) );

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

			$users = Supplier::where ( 'id' , $id )->where ( 'status' , true )->first ();


			if ( !$users ) {
				return $this->respondNotFound ( 'Supplier dose not found' );

			}
			//	if($id == $users->id or $pnum==$users->phone)

			/*	if(! $users)
				{
					$userarray = array(
					'id'=>$usernumber->id,
					'name'=>$usernumber->name,
					'email'=>$usernumber->email,
					'phone'=>$usernumber->phone,
					'created_at'=>$usernumber->created_at,
				//	'phone'=>$usernumber->phone,
					);
					/*return IlluResponse::json([
						'data'=>$this->userTrans->transform ($userarray)
					],200);*/
			//		return	`UserServices:: responedFound200('user found','Passed',$this->userTrans->transform ($userarray));
			//	}*/
			//	else
			//		if(!$usernumber)
			//				return IlluResponse::json([
			/*'data'=>$this->userTrans->transform ($users)*/

			return $this->responedFound200ForOneUser ( 'Supplier found' , self::success , $this->userTrans->transform ( $users ) );

			//				]);
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
			$type = '1';
			/*$this->validate ($request,[
							'name'=>'required|max:3'
						]);*/

			//				$request= $this->checkRequestType();
//			if (\Request::is('api/suppliers')) {

//			}
			$rules = array (
				'name' => 'required|regex:/^[\p{L}\s\.-]+$/|min:3|max:30' ,
				'email' => 'required|email|unique:users|unique:suppliers|unique:logins' ,
				'phone' => 'required|phone:JO|unique:users|unique:suppliers' ,
				//				'phonefield' => 'phone:JO,BE,mobile',
				'password' => 'required|min:8|max:30',
				'longitude' => 'required|numeric' ,
				'latitude' => 'required|numeric' ,
			);
			$messages = array (
				'name.required' => 'The name is really really really important.' ,
				'name.min' => 'The name min is 3.' ,
				'name.max' => 'The name min is 30' ,
				'email.required' => 'The email is important for my life ' ,
				'email.email' => 'take your time and add Real email' ,
				'email.unique' => 'this email is already exiting' ,
				'phone.unique' => 'this phone number is already exiting' ,
				'phone.phone:JO' => ' enter phone number with jordan code 962' ,
				'phone.phone' => ' enter valid phone number such as 962785555555' ,
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
			if ( $errors->first ( 'longitude' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'longitude' ) );
			if ( $errors->first ( 'latitude' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'latitude' ) );


			else

				//				$email = DB::table ('users')->where ('phone', $request->input ('email'))->first ();
				//				$phone = DB::table ('users')->where ('phone', $request->input ('phone'))->first ();

				$user = Supplier::create ( [
					'name' => $request->input ( 'name' ) ,
					'email' => $request->input ( 'email' ) ,
					'phone' => $request->input ( 'phone' ) ,
					'password' => bcrypt ( $request->input ( 'password' ) ) ,
					'longitude'=>$request->input ( 'longitude' ) ,
					'latitude'=>$request->input ( 'latitude' )
					//missing long wo lat

				] )->id;

			login::create ( [
				'email' => $request->input ( 'email' ) ,
				'password' => bcrypt ( $request->input ( 'password' ) ) ,
				'type' => $type
			] );

			//return $this->responedCreated ('Lesson successfully Created !');
			return $this->responedCreated200 ( ' successfully Created !' , self::success , $user );

		}

		public function respondwithErrorMessage ($status , $data)
		{
			return $this->setStatusCode ( self::HTTP_BAD_REQUEST )->respond ( [
				'massage' => $data ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,

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
			$now= Carbon::now ( 'GMT+2' );
			$user = Supplier::find ( $id );
			if ( !$user ) {
				return $this->respondWithError ( 'Supplier for id:' . $id . ' is not Exiting' , self::fail );
			} else {
				DB::table ( 'suppliers' )
					->where ( 'id' , $id )
					->update ( ['status' => false , 'deleted_at' =>$now ] );
				$email = DB::table ( 'suppliers' )
					->where ( 'id' , $id )->first ();
				DB::table ( 'logins' )->where ( 'email' , $email->email )
					->update ( ['status' => false , 'deleted_at' => $now] );

//					->update(['deleted_at'=>Carbon::now('GMT+3')]);
				return $this->responed_Destroy200 ( 'supplier was deleted ' , self::success );

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
			$rules = array (
				'name' => 'regex:/^[\p{L}\s\.-]+$/|min:3|max:30' ,
				'email' => 'email|unique:users|unique:suppliers|unique:logins' ,
				'phone' => 'phone:JO|unique:users' ,
				//				'phonefield' => 'phone:JO,BE,mobile',
				'password' => 'min:8|max:30',
				'longitude' => 'numeric' ,
				'latitude' => 'numeric' ,
			);
			$messages = array (
				'name.regex' => 'please Enter Name with only real char' ,
				'name.min' => 'The name min is 3.' ,
				'name.max' => 'The name min is 30' ,
				'email.email' => 'take your time and add Real email' ,
				'email.unique' => 'this email is already exiting' ,
				'phone.unique' => 'this phone is already exiting' ,
				'phone.phone:JO' => ' enter phone number with jordan code 962' ,
				'phone.phone' => ' enter valid phone number' ,
				//				'phone.phone:KS' => ' enter phone number with jordan code 966'

				'password.min' => 'the min of password 8 ' ,
				'password.max' => 'the max of password 30 '
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
			if ( $errors->first( 'phone' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'phone' ) );
			if ( $errors->first ( 'password' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'password' ) );
			if ( $errors->first ( 'longitude' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'longitude' ) );
			if ( $errors->first ( 'latitude' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'latitude' ) );

			$findid = Supplier::find ( $id );
			/*	DB::table('users')
							->where('id', $id)
							->update(['name' => $request->input('name'),
								'email' => $request->input('email'),
								'phone' => $request->input('phone')]);*/

			$name = $request->input ( 'name' );
			$email = $request->input ( 'email' );
			$phone = $request->input ( 'phone' );
			$password = $request->input ( 'password' );
			$status = $request->input ( 'status' );
			$now = Carbon::now ('GMT+2');

			if ( !$findid )
				return $this->respondWithError ( 'supplier not found ' , self::fail );
			else {

				$new_name = DB::table ( 'suppliers' )->where ( 'id' , $id )->first ();
				//			var_dump($new_name->email);
				if ( $new_name->name !== $name and $name !== null ) {
					DB::table ( 'suppliers' )
						->where ( 'id' , $id )
						->update ( ['name' => $request->input ( 'name' ) , 'updated_at' => $now] );

				}

				if ( $new_name->email !== $email and $email !== null ) {
					//to get email address for supplier with the id

					$e = DB::table ( 'logins' )->where ( 'email' , $new_name->email )->first ();
					$ee = DB::table ( 'suppliers' )->where ( 'email' , $email )->first ();


					//to find id in supplier and edit email
					DB::table ( 'suppliers' )
						->where ( 'id' , $id )
						->update ( ['email' => $request->input ( 'email' ) , 'updated_at' => $now] );
					//to edit supplier email in logins table
					DB::table ( 'logins' )->where ( 'email' , $new_name->email )
						->update ( ['email' => $request->input ( 'email' ) , 'updated_at' => $now] );

//					else return $this->respondWithError ('email already exists',self::fail);

				}


				if ( $new_name->phone !== $phone and $phone !== null ) {
					DB::table ( 'suppliers' )
						->where ( 'id' , $id )
						->update ( ['phone' => $request->input ( 'phone' )] )
						->update ( ['updated_at' => $now] );
				}
				if($request->input ( 'status' )  == 0 or $request->input ( 'status' )  == 1 ) {
					DB::table ( 'suppliers' )
						->where ( 'id' , $id )
						->update ( ['status' => $request->input ( 'status' ) , 'updated_at' => $now] );
				}
				else
					return $this->respondWithError ('status neeed to be 0 for false and 1 for ture',self::fail);
				if ( $new_name->password !== $password and $password !== null ) {

					//to get email supplier to chane password in logins table
					$email = DB::table ( 'suppliers' )
						->where ( 'id' , $id )->first ();

						DB::table ( 'suppliers' )
						->where ( 'id' , $id )
						->update ( ['password' => bcrypt ( $request->input ( 'password' ) ) ,
							'updated_at' => $now]);

						//to edit supplier password in logins table
					DB::table ( 'logins' )->where ( 'email' , $email->email )
						->update ( ['password' =>  bcrypt ( $request->input ( 'password' )),
							'updated_at' => $now] );


				}


				$data = Supplier::find ( $id );

				return $this->responedFound200ForOneUser ( 'supplier was updated' , self::success , $this->userTrans->transform ( $data ) );

			}

		}

		public function get_phone_Query (Request $request)
		{
//					dd($request->input ('phone'));
//					$s= substr($request->input ('phone'), 0, 1);
////					dd($s);
//					if($s ==9)
//					return	$this ->respondWithError  ('please don\'t use + in phone ',self::fail);
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
				//				return $this->setStatusCode (404)->respondwithErrorMessage (
				//					'some thing wrong', 'fail', $errors->first('name'));

				if ( $errors->first ( 'phone' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'phone' ) );


			$phone = $request->input ( 'phone' );
			$user_phone = Supplier::where ( 'phone' , $phone )->where ( 'status' , true )->first ();
			$user_phoneDeac = Supplier::where ( 'phone' , $phone )->where ( 'status' , false )->first ();

			if ( $user_phone == null and $user_phoneDeac == null )
				return $this->respondWithError ( 'supplier Not Found' , self::fail );
			elseif ( $user_phoneDeac )
				return $this->respondDeactivate ( 'this supplier is deactivate' , self::fail );
			else
				return $this->responedFound200ForOneUser ( 'supplier found' , self::success ,
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

//				$date = Input::get ('date');


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
//				$date_after_formate= date("Y-m-d",strtotime(Input::get ('datee')));
//				$user= User::where('created_at',date("Y-m-d",strtotime(Input::get ('datee'))))->first ();
//			User::
			$user = Supplier::whereDate ( 'created_at' , '=' , date ( "Y-m-d" , strtotime ( Input::get ( 'date' ) ) ) )->first ();

			if ( !$user )
				return $this->respondWithError ( 'supplier not found' , self::fail );
			else    //if ($user !== null)
				return $this->responedFound200ForOneUser ( 'supplier found' , self::success ,
					$this->userTrans->transform ( $user ) );
		}

		public function get_date_Query (Request $request)
		{
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
			$user = Supplier::whereBetween ( 'created_at' , [$start , $end] )->where ( 'status' , Input::get ( 'status' ) )->get ();
			if ( $user->first () === null )
				return $this->respondWithError ( 'supplier not found' , self::fail );
			else    //if ($user !== null)
				return $this->responedFound200 ( 'supplier found' , self::success ,
					$this->userTrans->transformCollection ( $user->toArray () ) );


		}

		public function get_user_by_email (Request $request)
		{
			$rules = array (

				'email' => 'required|email' ,

			);
			$messages = array (
				'email.required' => ' must enter a email ' ,
				'email.email' => ' enter valid email' ,
				//				'phone.phone:KS' => ' enter phone number with jordan code 966'
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();


			if ( $validator->fails () )
				//				return $this->setStatusCode (404)->respondwithErrorMessage (
				//					'some thing wrong', 'fail', $errors->first('name'));

				if ( $errors->first ( 'email' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'email' ) );


			$user_email = $request->input ( 'email' );
			$user_compare_email = Supplier::where ( 'email' , $user_email )->where ( 'status' , true )->first ();
			$user_deactive = Supplier::where ( 'email' , $user_email )->where ( 'status' , false )->first ();
			if ( $user_compare_email == null and $user_deactive == null )
				return $this->respondWithError ( 'supplier Not Found' , self::fail );
			elseif ( $user_deactive )
				return $this->respondDeactivate ( 'this supplier is deactivate' , self::fail );
			else
				return $this->responedFound200ForOneUser ( 'supplier found' , self::success ,
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

			$user_compare_email_phone = suppliers::where ( 'email' , $user_email )->where ( 'phone' , $user_phone )
				->where ( 'status' , true )->first ();

			$user_deactive = suppliers::where ( 'email' , $user_email )->where ( 'phone' , $user_phone )
				->where ( 'status' , false )->first ();
//						dd($user_deactive);
			if ( $user_compare_email_phone == null and $user_deactive == null )
				return $this->respondWithError ( 'supplier Not Found' , self::fail );
			elseif ( $user_deactive )
				return $this->respondDeactivate ( 'this supplier is deactivate' , self::fail );
			else
				return $this->responedFound200ForOneUser ( 'supplier found' , self::success ,
					$this->userTrans->transform ( $user_compare_email_phone ) );


		}

		public function get_inactive_users (Request $request)
		{
			$status = $request->input ( 'status' );
			$users = Supplier::where ( 'status' , $status )->get ();

			/*return	IlluResponse::json([
				'data'=>$this->userTrans->transformCollection  ($users->all ())
			],200);*/

//					dd($users->count ());
			return $this->responedFound200
			( 'supplier found' , self::success , $this->userTrans->transformCollection ( $users->all () ) );
		}

		/**
		 * @param Paginator $lessons
		 * @param $data
		 * @return mixed
		 */
		protected function respondWithPagnation (Paginator $lessons , $data)
		{
			$d = $data;
			//$d=array_count_values  ($data);
			$data = array_merge ( $data ,
				[
					'paginator' => [
						'total_count' => $lessons->Total () ,
						'total_page' => ceil ( $lessons->Total () / $lessons->perPage () ) ,
						'Curant_page' => $lessons->currentPage () ,
						'limit' => $lessons->perPage () ,
						//		'object_array'=>$d
					]
				] );

			return $this->respond ( $data );
		}
public function suppliers_services_id(Request $request)
{

	$service_id=$request->input ( 'service_id' );
	if ( 1 == 1 ){
		$service = Services::with ( 'suppliers' )->where('id',$service_id)->where ('status',1)->get ();

		return $this->responedFound200SupplierWithService_id ( 'sucsess' , self::success , $service->all () );
	}
	else
		return $this->respondWithError ('must enter status 1 od true found ',self::fail);

}



		public function responedFound200SupplierWithService_id($massage , $status , $data)
		{
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
				'services_count' => count ( $data ) ,
				'data' =>$data

			] );
		}
	}