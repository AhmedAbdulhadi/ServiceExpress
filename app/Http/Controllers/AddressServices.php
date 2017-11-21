<?php

	namespace App\Http\Controllers;

	use Carbon\Carbon;
	use Illuminate\Http\Request;
	use App\address;
	use App\User;
//	use App\Http\Controllers\UserServices;
//	use App\Http\Controllers\Controller;
//	use Illuminate\Support\Facades\Response;
	use Novent\Transformers\AddressTransfomer;
	use \Validator;
	use Illuminate\Support\Facades\DB;

//use Illuminate\Http\Request;
	class AddressServices extends Controller
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
		 * @var  Novent\Transformers\AddressTransfomer
		 */
		protected $addressTrans;
		/**
		 * @var int
		 */
		protected $statusCode = 200;

		public function __construct (AddressTransfomer $addressTrans)
		{
			$this->addressTrans = $addressTrans;
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

		public function get_all_address ()
		{
//			$a=  new UserServices($this->addressTrans);
			$address = address::where ( 'status' , '=' , true )->get ();

			return $this->responedFound200
			( 'users found' , self::success , $this->addressTrans->transformCollection ( $address->all () ) );
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

		public function add_address (Request $request)
		{


			$type0 =DB::table ( 'address_user' )->where ( 'user_id' , $request->input ( 'user_id' ) )->where ( 'address_type' , '0' )->first ();
			$type1 =DB::table ( 'address_user' )->where ( 'user_id' , $request->input ( 'user_id' ) )->where ( 'address_type' , '1' )->first ();
			$type2 = DB::table ( 'address_user' )->where ( 'user_id' , $request->input ( 'user_id' ) )->where ( 'address_type' , '2' )->first ();


					if($type0 !==null and $request->input ( 'address_type' )=='0')
						return $this->respondWithError ('address_type 0 is here',self::fail);

			else	if($type1 !==null and $request->input ( 'address_type' )=='1')
				return $this->respondWithError ('address_type 1 is here',self::fail);


else


				//validtion rule
				$rules = array (
					'longitude' => 'required|numeric' ,
					'latitude' => 'required|numeric' ,
					'city' => 'string' ,
					'street' => 'string' ,
					'country' => 'string' ,
					'neighborhood' => 'integer' ,
					'building_number' => 'integer' ,
					'apartment_number' => 'integer' ,
					'floor' => 'integer' ,
					'address_type' => 'required|integer|between:0,2',
					'user_id' => 'required|exists:users,id'
				);
				$messages = array (
					'longitude.required' => 'longitude is required' ,
					'longitude.numeric' => 'Enter valid longitude' ,
					'latitude.required' => 'latitude is required' ,
					'latitude.numeric' => 'Enter valid latitude' ,
					'city.string' => 'Enter valid place' ,
					'street.string' => 'Enter valid place' ,
					'country.string' => 'Enter valid place' ,
					'neighborhood.integer' => 'Enter valid number for neighborhood' ,
					'building_number.integer' => 'Enter valid number for building_number' ,
					'apartment_number.integer' => 'Enter valid number for apartment_number ' ,
					'floor.integer' => 'Enter valid number for floor' ,
				);

				$validator = Validator::make ( $request->all () , $rules , $messages );
				//			$errors= $validator;
				$errors = $validator->errors ();


				if ( $validator->fails () )


					if ( $errors->first ( 'longitude' ) )
						return $this->respondwithErrorMessage (
							self::fail , $errors->first ( 'longitude' ) );
				if ( $errors->first ( 'latitude' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'latitude' ) );
				if ( $errors->first ( 'city' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'city' ) );
				if ( $errors->first ( 'street' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'street' ) );
				if ( $errors->first ( 'country' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'country' ) );
				if ( $errors->first ( 'neighborhood' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'neighborhood' ) );
				if ( $errors->first ( 'building_number' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'building_number' ) );
				if ( $errors->first ( 'apartment_number' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'apartment_number' ) );
				if ( $errors->first ( 'floor' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'floor' ) );
			if ( $errors->first ( 'address_type' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'address_type' ) );
			if ( $errors->first ( 'user_id' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'user_id' ) );

			else


				$address_id = DB::table ( 'address' )->insertGetId (
					[
						'longitude' => $request->input ( 'longitude' ) ,
						'latitude' => $request->input ( 'latitude' ) ,
						'city' => $request->input ( 'city' ) ,
						'street' => $request->input ( 'street' ) ,
						'country' => $request->input ( 'country' ) ,
						'neighborhood' => $request->input ( 'neighborhood' ) ,
						'building_number' => $request->input ( 'building_number' ) ,
						'apartment_number' => $request->input ( 'apartment_number' ) ,
						'floor' => $request->input ( 'floor' ) ,
						'address_type' => $request->input ( 'address_type' ) ,
						'created_at' => Carbon::now ( 'GMT+3' ) ,
						'updated_at' => Carbon::now ( 'GMT+3' ) ,

					]
				);
//dd($address_id);

			//user id
				$user = User::find ( $request->input ( 'user_id' ) );
				$user->address ()->attach ( $address_id , ['address_type' => $request->input ( 'address_type' )] );


				return $this->responedCreated200 ( ' successfully Created !' , self::success , $address_id );

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

		public function update_address (Request $request , $id)
		{
			$rules = array (
				'longitude' => 'numeric' ,
				'latitude' => 'numeric' ,
				'city' => 'string' ,
				'street' => 'string' ,
				'country' => 'string' ,
				'neighborhood' => 'integer' ,
				'building_number' => 'integer' ,
				'apartment_number' => 'integer' ,
				'floor' => 'integer' ,
			);
			$messages = array (

				'longitude.numeric' => 'Enter valid longitude' ,
				'latitude.numeric' => 'Enter valid latitude' ,
				'city.string' => 'Enter valid place' ,
				'street.string' => 'Enter valid place' ,
				'country.string' => 'Enter valid place' ,
				'neighborhood.integer' => 'Enter valid number for neighborhood' ,
				'building_number.integer' => 'Enter valid number for building_number' ,
				'apartment_number.integer' => 'Enter valid number for apartment_number ' ,
				'floor.integer' => 'Enter valid number for floor' ,
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();


			if ( $validator->fails () )

				if ( $errors->first ( 'longitude' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'longitude' ) );
			if ( $errors->first ( 'latitude' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'latitude' ) );
			if ( $errors->first ( 'city' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'city' ) );
			if ( $errors->first ( 'street' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'street' ) );
			if ( $errors->first ( 'country' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'country' ) );
			if ( $errors->first ( 'neighborhood' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'neighborhood' ) );
			if ( $errors->first ( 'building_number' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'building_number' ) );
			if ( $errors->first ( 'apartment_number' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'apartment_number' ) );
			if ( $errors->first ( 'floor' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'floor' ) );


			$findid = address::find ( $id );

			$longitude = $request->input ( 'longitude' );
			$latitude = $request->input ( 'latitude' );

			$street = $request->input ( 'street' );
			$city = $request->input ( 'city' );
			$country = $request->input ( 'country' );

			$building_number = $request->input ( 'building_number' );
			$neighborhood = $request->input ( 'neighborhood' );
			$apartment_number = $request->input ( 'apartment_number' );
			$floor = $request->input ( 'floor' );
			$now = Carbon::now ( 'GMT+2' );

			if ( !$findid )
				return $this->respondWithError ( 'address  not found ' , self::fail );
			else {

				$update_address = DB::table ( 'address' )->where ( 'id' , $id )->first ();
				//			var_dump($new_name->email);
				if ( $update_address->longitude !== $longitude and $longitude !== null ) {
					DB::table ( 'address' )
						->where ( 'id' , $id )
						->update ( ['longitude' => $request->input ( 'longitude' ) , 'updated_at' => $now] );
				}

				if ( $update_address->latitude !== $latitude and $latitude !== null ) {
					DB::table ( 'address' )
						->where ( 'id' , $id )
						->update ( ['latitude' => $request->input ( 'latitude' ) , 'updated_at' => $now] );
				}

				if ( $update_address->street !== $street and $street !== null ) {
					DB::table ( 'address' )
						->where ( 'id' , $id )
						->update ( ['street' => $request->input ( 'street' ) , 'updated_at' => $now] );
				}
				if ( $update_address->city !== $city and $city !== null ) {

					DB::table ( 'address' )
						->where ( 'id' , $id )
						->update ( ['city' => $request->input ( 'city' ) ,
							'updated_at' => $now] );


				}
				if ( $update_address->country !== $country and $country !== null ) {

					DB::table ( 'address' )
						->where ( 'id' , $id )
						->update ( ['country' => $request->input ( 'country' ) , 'updated_at' => $now] );


				}
				if ( $update_address->building_number !== $building_number and $building_number !== null ) {

					DB::table ( 'address' )
						->where ( 'id' , $id )
						->update ( ['building_number' => $request->input ( 'building_number' ) ,
							'updated_at' => $now] );


				}
				if ( $update_address->neighborhood !== $neighborhood and $neighborhood !== null ) {

					DB::table ( 'address' )
						->where ( 'id' , $id )
						->update ( ['neighborhood' => $request->input ( 'neighborhood' ) , 'updated_at' => $now] );


				}
				if ( $update_address->apartment_number !== $apartment_number and $apartment_number !== null ) {

					DB::table ( 'address' )
						->where ( 'id' , $id )
						->update ( ['apartment_number' => $request->input ( 'apartment_number' ) ,
							'updated_at' => $now] );


				}
				if ( $update_address->floor !== $floor and $floor !== null ) {

					DB::table ( 'address' )
						->where ( 'id' , $id )
						->update ( ['floor' => $request->input ( 'floor' ) ,
							'updated_at' => $now] );

				}
				if($request->input ( 'status' )  == 0 or $request->input ( 'status' )  == 1 ) {
					DB::table ( 'address' )
						->where ( 'id' , $id )
						->update ( ['status' => $request->input ( 'status' ) , 'updated_at' => $now] );
				}
				else
					return $this->respondWithError ('status neeed to be 0 for false and 1 for ture',self::fail);

				$data = address::find ( $id );

				return $this->responedFound200ForOneAddress ( 'address was updated' , self::success , $this->addressTrans->transform ( $data ) );

			}

		}

		public function responedFound200ForOneAddress ($massage , $status , $data)
		{

			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
				'data' => $data
			] );

		}
	}
