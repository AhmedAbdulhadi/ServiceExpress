<?php

	namespace App\Http\Controllers;

	use App\address;
	use App\Section;
	use App\Supplier;
	use App\User;
	use Carbon\Carbon;
//	use Faker\Provider\Image;
	use Illuminate\Http\Request;

//use App\address;
//use App\User;
//	use App\Http\Controllers\UserServices;
//	use App\Http\Controllers\Controller;
//	use Illuminate\Support\Facades\Response;
//use Novent\Transformers\AddressTransfomer;
	use Illuminate\Support\Facades\Input;
//	use Illuminate\Support\Facades\Input;

	use Novent\Transformers\OrderTrans;
	use Novent\Transformers\orderTransOne;
	use Novent\Transformers\orderTransOneC;
	use \Validator;
	use Illuminate\Support\Facades\DB;
	use App\Order;

//use Illuminate\Http\Request;

	class OrderServices extends Controller
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


		protected $statusCode = 200;
		/**
		 * @var  Novent\Transformers\OrderTrans
		 */
		protected $userTrans;
		protected $userTransOne;
		protected $userTransOneC;


		public function __construct (OrderTrans $userTrans , orderTransOne $userTransOne,orderTransOneC $orderTransOneC)
		{
			$this->userTrans = $userTrans;
			$this->userTransOne = $userTransOne;
			$this->userTransOneC = $orderTransOneC;

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
			return response ()->json ( $data , $this->getStatusCode () , $headers );
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

		public function responedCreated200 ($massage , $status , $id = null)
		{
			return $this->setStatusCode ( self::HTTP_CREATED )->respond ( [
				'massage' => $massage ,
				'id' => $id
//				'status' => $this->status ( $status )
				, 'code' => $this->statusCode ,

			] );
		}

		public function create_order (Request $request)
		{


			$user_id = $request->input ( 'user_id' );
			$supplier_id = $request->input ( 'supplier_id' );
			$service_id = $request->input ( 'service_id' );
			$description = $request->input ( 'description' );
			$status = $request->input ( 'status' );
			$rate = $request->input ( 'rate' );
			$image = $request->file ( 'image' );

			$rules = array (
////
				"user_id" => "required|integer|exists:users,id" ,
				"supplier_id" => "required|integer|exists:suppliers,id" ,
				"service_id" => "required|integer|exists:services,id" ,
				"status" => "required|integer|min:0|max:3" ,
				"rate" => "numeric|min:0|max:5" ,
			);
			$messages = array (
				"user_id.required" => "user_id is required " ,
				"user_id.integer" => " user_id must be of type integer" ,
				"supplier_id.required" => "supplier_id is required" ,
				"supplier_id.integer" => "supplier_id must be integer" ,
				"service_id.required" => "service_id is required" ,
				"service_id.integer" => "service_id must be integer" ,
				"description.required" => "desc is required" ,
//			"description"=>"required|"
			);


			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();


			if ( $validator->fails () )
//
				if ( $errors->first ( 'user_id' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'user_id' ) );

			if ( $errors->first ( 'supplier_id' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'supplier_id' ) );

			if ( $errors->first ( 'service_id' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'service_id' ) );


			if ( $errors->first ( 'status' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'status' ) );
			if ( $errors->first ( 'rate' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'rate' ) );
			else //
			{
				$rated = false;
				if ( $rate )
					$rated = true;

				if ( !$rate )
					$rate = 0;

//						$image = $request->file('image');
//
				/*$input['imagename'] = time () . '.' . $image->getClientOriginalExtension ();
				$destinationPath = public_path ( 'images\orders' );
				$image->move ( $destinationPath , $input['imagename'] );
				$final_path = $destinationPath .'\\'. $input['imagename'];*/

//				$data=Input::all();
//				$png_url = "order-".time().".png";
//				$path = public_path().'img/orders/' . $png_url;
//
//				Image::make(file_get_contents($data->base64_image))->save($path);
				$data = Input::all ();
				if(Input::has('image')) {
					$png_url = "orders-" . time () . ".png";
					$path = public_path ( "images\orders\\" ) . $png_url;
					$data = $data['image'];
					list( $type , $data ) = explode ( ';' , $data );
					list( , $data ) = explode ( ',' , $data );
					$data = base64_decode ( $data );
//				dd($data);
					$success = file_put_contents ( $path , $data );
				}
				else
					$path="";
//dd($path);
//				$success = file_put_contents ( $path , $data );
				$success = null;

				if ( $success or ! $success ) {
					$order = DB::table ( 'orders' )->insertGetId (
						[
							'user_id' => $user_id ,
							'supplier_id' => $supplier_id ,
							'service_id' => $service_id ,
							'desc' => $description ,
							'path' => $path ,
							'delivered_at' => Carbon::now ( 'GMT+3' ) ,
							'status' => $status ,
							'rate' => $rate ,
							'is_rated' => $rated ,
							'created_at' => Carbon::now ( 'GMT+2' )
						]
					);
					$oneOrder = Order::find ( $order );

//				$user=
					return $this->responedFound200ForOneorder ( ' successfully Created !' , self::success
						, $this->userTransOneC->transform ($oneOrder->toArray ()) );
				} else
					return $this->respondWithError ( 'image did not saved ' , self::fail );
			}
		}

		public function respondwithErrorMessage ($status , $data)
		{
			return $this->setStatusCode ( self::HTTP_BAD_REQUEST )->respond ( [
				'massage' => $data ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,

			] );
		}

		public function responedFound200ForOneorder ($massage , $status , $data)
		{

			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
				'data' => $data
			] );
		}

		public function update_order (Request $request , $id)
		{
			/*
		=================================================================================
			don't forget to change  services_id in db to service_id
		=================================================================================

			*/
//			$user_id = $request->input ( 'user_id' );
//			$supplier_id = $request->input ( 'supplier_id' );
//			$service_id = $request->input ( 'service_id' );
//			$description = $request->input ( 'description' );
//			$status= $request->input ( 'status' );
//			$rate= $request->input ( 'rate' );
//			$image = $request->file ( 'image' );

			$rules = array (
////
				"user_id" => "integer|exists:users,id" ,
				"supplier_id" => "integer|exists:suppliers,id" ,
				"service_id" => "integer|exists:services,id" ,
//				"description" => "required" ,
				"status" => "integer|min:0|max:3" ,
				"is_rated" => "integer|min:0|max:1" ,
				"rate" => "numeric|min:0|max:5" ,
			);
			$messages = array (
//				"user_id.required" => "user_id is required " ,
				"user_id.integer" => " user_id must be of type integer" ,
//				"supplier_id.required" => "supplier_id is required" ,
				"supplier_id.integer" => "supplier_id must be integer" ,
//				"service_id.required" => "services_id is required" ,
				"service_id.integer" => "service_id must be integer" ,
				"is_rated.min" => "is rated must be 1 for true 0 for false" ,
				"is_rated.max" => "is rated must be 1 for true 0 for false"
			);


			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();


			if ( $validator->fails () )
//
				if ( $errors->first ( 'user_id' ) )
					return dd (
						self::fail , $errors->first ( 'user_id' ) );

			if ( $errors->first ( 'supplier_id' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'supplier_id' ) );

			if ( $errors->first ( 'service_id' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'service_id' ) );

			if ( $errors->first ( 'description' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'description' ) );

			if ( $errors->first ( 'status' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'status' ) );
			if ( $errors->first ( 'rate' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'rate' ) );
			if ( $errors->first ( 'is_rated' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'is_rated' ) );


			$findid = Order::find ( $id );

			$user_id = $request->input ( 'user_id' );
			$supplier_id = $request->input ( 'supplier_id' );
			$service_id = $request->input ( 'service_id' );
			$description = $request->input ( 'description' );
			$status = $request->input ( 'status' );
			$rate = $request->input ( 'rate' );
			$is_rated = $request->input ( 'is_rated' );
			$now = Carbon::now ( 'GMT+2' );
//			$image = $request->file ( 'image' );
//dd($request->input ( 'description' ));
			if ( !$findid )
				return $this->respondWithError ( 'order not found ' , self::fail );
			else {
//dd(213);
				$new_name = DB::table ( 'orders' )->where ( 'id' , $id )->first ();
				//			var_dump($new_name->email);
//				dd($is_rated );
				if ( $request->input ( 'user_id' ) !== null ) {
//				dd('asx');
					DB::table ( 'orders' )
						->where ( 'id' , $id )
						->update ( ['user_id' => $user_id , 'updated_at' => $now] );

				}
				if ( $new_name->supplier_id !== $supplier_id and $supplier_id !== null ) {
					DB::table ( 'orders' )
						->where ( 'id' , $id )
						->update ( ['supplier_id' => $supplier_id , 'updated_at' => $now] );

				}
				if ( $new_name->service_id !== $service_id and $service_id !== null ) {//change services_id to service_id
					DB::table ( 'orders' )
						->where ( 'id' , $id )
						->update ( ['service_id' => $service_id , 'updated_at' => $now] );

				}
				if ( $description !== null ) {
//					dd('desc');
					DB::table ( 'orders' )
						->where ( 'id' , $id )
						->update ( ['desc' => $description , 'updated_at' => $now] );

				}
				if ( $new_name->status !== $status and $status !== null )
					if ( $status == 0 or $status == 1
						or $status == 2 or $status == 3 ) {
						DB::table ( 'orders' )
							->where ( 'id' , $id )
							->update ( ['status' => $status , 'updated_at' => $now] );
					}
				if ( $new_name->rate !== $rate and $rate !== null )
					if ( $rate ) {
						DB::table ( 'orders' )
							->where ( 'id' , $id )
							->update ( ['rate' => $rate , 'updated_at' => $now] );
					}


				if ( $new_name->is_rated !== $is_rated and $is_rated !== null )
					if ( $is_rated == 0 ) {
						DB::table ( 'orders' )
							->where ( 'id' , $id )
							->update ( ['is_rated' => $is_rated , 'rate' => 0 , 'updated_at' => $now] );
					} else if ( $is_rated == 1 ) {
						DB::table ( 'orders' )
							->where ( 'id' , $id )
							->update ( ['is_rated' => $is_rated , 'updated_at' => $now] );
					}


//					else
//						return $this->respondWithError ( 'status neeed to be 0 for false and 1 for ture' , self::fail );

			}

			$data = Order::find ( $id );

			return $this->responedFound200ForOneUser ( 'Services was updated' , self::success , $data );


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

		public function delete_order ($id)
		{
//			$now = Carbon::now ( 'GMT+2' );
			$order = Order::find ( $id );
			if ( !$order ) {
				return $this->respondWithError ( 'order for id:' . $id . ' is not Exiting' , self::fail );
			} else {
				DB::table ( 'orders' )
					->where ( 'id' , $id )
					->delete ();


				return $this->responed_Destroy200 ( 'Order was deleted ' , self::success );

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


		public function getAllOrder ()
		{
//			dd ('asda order');
//			dd('orders');
			$orders = Order::all ();

//dd($orders->toArray ());
//	dd($orders->find ($orders['id'])->service ($orders["user_id"],$orders["supplier_id"],$orders["service_id"]));

//$arr=[];
			foreach ($orders as $order) {
//	if($order->user_id and $order->supplier_id and $order->service_id)
				$order->order_info = $order->service ( $order->user_id , $order->supplier_id , $order->service_id );
//	$o= $order->service ( $order->user_id , $order->supplier_id , $order->service_id );
//	array_merge ($orders->toArray (),$o);
//dd($orders->toArray ());
			}
			$countOrders = count ( $orders );

			return $this->responedFound200SectionSupplierId
			( 'Order found' , self::success , $this->userTrans->transformCollection ( $orders->all () ) , $countOrders );
		}

		public function responedFound200SectionSupplierId ($massage , $status , $data , $countOrders)
		{
//			$data->toArray();
//			$data2->toArray();
//			$array3 = array_merge ( $data->toArray () , $data2->toArray () );

//			$array3 = array ('assigned_services'=>$data,'unassigned_services'=>$data2);

//
//			foreach ($data as $user) {
//				$user->address=$data2;
//			}
//			foreach ($data2 as $user)
//				$user->is_added = 'false';
//			dd($data2);
//			$is_added= array ('is_added'=>true);
////			$array3 = a($is_added,$data);
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'size' => $countOrders ,
				'code' => $this->statusCode ,
				'data' => $data ,


			] );
		}

		public function getOneOrder ($id)
		{
			$orders = Order::find ( $id );
//			foreach ($orders as $key => $order)

//dd();
			if($orders) {
				$arr = $orders->service ( $orders["user_id"] , $orders["supplier_id"] , $orders["service_id"] );
				$countOrder = count ( $orders );

				return $this->responedFound200SectionSupplierId
				( 'Order found' , self::success ,
					$this->userTransOne->transform ( array_merge ( $orders->toArray () , $arr ) ) , $countOrder );
			}
			else
				return $this->respondWithError ('order not found',self::fail);

//		return $this->respondWithError ('order not found',self::fail);

		}


	}
