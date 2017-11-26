<?php
	/**
	 * Created by PhpStorm.
	 * User: dark-
	 * Date: 10/24/2017
	 * Time: 4:17 PM
	 */

	namespace App\Http\Controllers;

//	use App\login;
	use App\Section;
	use App\Services;
	use App\Supplier;
	use Carbon\Carbon;
	use Illuminate\Contracts\Pagination\Paginator;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Response;
	use Novent\Transformers\servicesTransform;
	use Novent\Transformers\SupplierTransform;
	use \Validator;
//	use Illuminate\Support\Facades\Auth;
//	use Illuminate\Support\Facades\Input;
//	use Novent\Transformers\section_servicesTra;


	class ServicesC extends Controller
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
		protected $use;

		public function __construct (servicesTransform $userTrans , SupplierTransform $use)
		{
			$this->userTrans = $userTrans;
			//$this->middleware('auth.basic', ['only' => 'store']);
			$this->use = $use;
			$this->middleware ( 'auth:api' );

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

		public function getAllServices ()
		{
//			dd('asdas   ');
//dd(Auth::id ());
//dd('ahmad');
			$Service = Services::where ( 'status' , true )->get ();
//			$section = Services::has  ('sections')->get ();
			$section = Services::with ( 'sections' )->where ( 'status' , '=' , '1' )->get ();
			$services = Section::with ( 'services' )->where ( 'status' , '=' , '1' )->get ();

			return $this->responedFound200
			( 'Service found' , self::success , $this->userTrans->transformCollection ( $Service->all () ) );

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

		public function get_one_services ($id = null)
		{

			$Service = Services::where ( 'id' , $id )->where ( 'status' , true )->first ();


			if ( !$Service ) {
				return $this->respondNotFound ( 'Service dose not found' );

			}


			return $this->responedFound200ForOneUser ( 'Service found' , self::success , $this->userTrans->transform ( $Service ) );

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

		public function respondDeactivate ($massage , $status = null)
		{
			return $this->setStatusCode ( self::HTTP_FORBIDDEN )->respond ( [

				'massage' => $massage ,
				'code' => $this->statusCode
				, 'status' => $this->status ( $status )

			] );
		}

		/**
		 * @param Request $request
		 * @return mixed
		 */
		public function create_Service (Request $request)
		{

//			$user=Auth::user();
//			dd($user);
			$rules = array (
				'section_id' => 'required|integer' ,
				'name_en' => 'required|regex:/^[\p{L}\s\.-]+$/|min:3|max:30' ,
				'name_ar' => 'required|min:3|max:50' ,
				'desc_en' => 'required|min:3|max:140' ,
				'desc_ar' => 'required|min:3|max:140' ,
				'image' => 'string' ,
			);
			$messages = array (
				'name_en.regex' => 'please Enter Name with only real char' ,
				'name_en.min' => 'The name min is 3.' ,
				'name_en.max' => 'The name max is 30' ,
				'name_ar.regex' => 'name_ar please Enter Name with only real char' ,
				'name_ar.min' => 'The name min is 3.' ,
				'name_ar.max' => 'The name max is 30' ,
				'desc_en.min' => 'The name min is 3.' ,
				'desc_en.max' => 'The name max is 30' ,
				'desc_ar.min' => 'The name min is 3.' ,
				'desc_ar.max' => ' The name max is 30' ,
//				'phone.phone' => ' enter valid phone number' ,
//				//				'phone.phone:KS' => ' enter phone number with jordan code 966'
//
//				'password.min' => 'the min of password 8 ' ,
//				'password.max' => 'the max of password 30 '
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'section_id' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'section_id' ) );
			if ( $errors->first ( 'name_en' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'name_en' ) );
			if ( $errors->first ( 'name_ar' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'name_ar' ) );
			if ( $errors->first ( 'desc_en' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'desc_en' ) );
			if ( $errors->first ( 'desc_ar' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'desc_ar' ) );

			if ( $errors->first ( 'image' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'image' ) );


//			if ( $errors->first ( 'password' ) )
//				return $this->respondwithErrorMessage (
//					self::fail , $errors->first ( 'password' ) );


			else {
				$image_path=url  ( '/icons/404.png');

				if($request->input ( 'image' ))
//				if (fileExists ( public_path ( "icons\\" .$request->input ( 'icon' ))))
				{
					if(file_exists(base_path  ( 'icons\\'.$request->input ( 'image' ) )))
					{
//						dd(url   ( '/icons/'.$request->input ( 'image' ) ));
						$image_path=url  ( 'icons/'.$request->input ( 'image' ) );
					}
					else
					{
						$image_path=url ( '/icons/404.png');
					}
				}
				elseif(! $request->input ( 'image' ))
//				dd(url ( '/icons/default.png'));
					$image_path=url ( '/icons/404.png');

				$user = Section::find ( $request->input ( 'section_id' ) );

//					dd ( $Service );
//					$user = Section::find (1 );
//					$user->services ()->attach ( 1 );
				if ( $user ) {
					$Service = Services::create ( [
						'name_en' => $request->input ( 'name_en' ) ,
						'name_ar' => $request->input ( 'name_ar' ) ,
						'desc_en' => $request->input ( 'desc_en' ) ,
						'desc_ar' => $request->input ( 'desc_ar' ) ,
						'image' => $image_path ,
					] )->id;
//					dd($Service);
					$user->services ()->attach ( $Service );

					return $this->responedCreated200 ( ' successfully Created !' , self::success , $Service );
				} else
					return $this->respondWithError ( 'section not found' , self::fail );
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

		public function responedCreated200 ($massage , $status , $id = null)
		{
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'id' => $id ,
				'status' => $this->status ( $status )
				, 'code' => $this->statusCode
			] );
		}

		public function delete_services ($id)
		{
			$now = Carbon::now ( 'GMT+2' );
			$Service = Services::find ( $id );
			if ( !$Service ) {
				return $this->respondWithError ( 'Service for id:' . $id . ' is not Exiting' , self::fail );
			} else {
				DB::table ( 'services' )
					->where ( 'id' , $id )
					->update ( ['status' => false , 'deleted_at' => $now] );

//				$email = DB::table ( 'admins' )
//					->where ( 'id' , $id )->first ();
//
//				DB::table ( 'logins' )->where ( 'email' , $email->email )
//					->update ( ['status' => false , 'deleted_at' => Carbon::now ( 'GMT+3' )] );

//					->update(['deleted_at'=>Carbon::now('GMT+3')]);
				return $this->responed_Destroy200 ( 'Services was deleted ' , self::success );

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

		public function update_services (Request $request , $id)
		{
			$rules = array (
//				'section_id' => 'required|integer' ,
				'name_en' => 'regex:/^[\p{L}\s\.-]+$/|min:3|max:30' ,
				'name_ar' => 'min:3|max:50' ,
				'desc_en' => 'min:3|max:140' ,
				'desc_ar' => 'min:3|max:140' ,
				'image' => 'string' ,
			);
			$messages = array (
				'name_en.regex' => 'please Enter Name with only real char' ,
				'name_en.min' => 'The name min is 3.' ,
				'name_en.max' => 'The name max is 30' ,
				'name_ar.regex' => 'name_ar please Enter Name with only real char' ,
				'name_ar.min' => 'The name min is 3.' ,
				'name_ar.max' => 'The name max is 30' ,
				'desc_en.min' => 'The name min is 3.' ,
				'desc_en.max' => 'The name max is 30' ,
				'desc_ar.min' => 'The name min is 3.' ,
				'desc_ar.max' => ' The name max is 30' ,
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'name_en' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'name_en' ) );
			if ( $errors->first ( 'name_ar' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'name_ar' ) );
			if ( $errors->first ( 'desc_en' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'desc_en' ) );
			if ( $errors->first ( 'desc_ar' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'desc_ar' ) );
			if ( $errors->first ( 'image' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'image' ) );


			$findid = Services::find ( $id );


			$name_en = $request->input ( 'name_en' );
			$name_ar = $request->input ( 'name_ar' );
			$desc_en = $request->input ( 'desc_en' );
			$desc_ar = $request->input ( 'desc_ar' );
			$status = $request->input ( 'status' );
			$image = $request->input ( 'image' );
			$now = Carbon::now ( 'GMT+2' );

			if ( !$findid )
				return $this->respondWithError ( 'Services not found ' , self::fail );
			else {

				$new_name = DB::table ( 'services' )->where ( 'id' , $id )->first ();
				//			var_dump($new_name->email);
				if ( $new_name->name_en !== $name_en and $name_en !== null ) {
					DB::table ( 'services' )
						->where ( 'id' , $id )
						->update ( ['name_en' => $request->input ( 'name_en' ) , 'updated_at' => $now] );

				}
				if ( $new_name->name_ar !== $name_ar and $name_ar !== null ) {
					DB::table ( 'services' )
						->where ( 'id' , $id )
						->update ( ['name_ar' => $request->input ( 'name_ar' ) , 'updated_at' => $now] );

				}
				if ( $new_name->desc_en !== $desc_en and $desc_en !== null ) {
					DB::table ( 'services' )
						->where ( 'id' , $id )
						->update ( ['desc_en' => $request->input ( 'desc_en' ) , 'updated_at' => $now] );

				}
				if ( $new_name->desc_ar !== $desc_ar and $desc_ar !== null ) {
					DB::table ( 'services' )
						->where ( 'id' , $id )
						->update ( ['desc_ar' => $request->input ( 'desc_ar' ) , 'updated_at' => $now] );

				}
				if ( $image !== null )
				{
					$image_path=url  ( '/icons/404.png');
					if($request->input ( 'image' ))
					{
						if(file_exists(base_path  ( 'icons\\'.$request->input ( 'image' ) )))
						{

							$image_path=url  ( 'icons/'.$request->input ( 'image' ) );					}
						else
						{
							$image_path=url ( '/icons/404.png');
						}

					}
					elseif(! $request->input ( 'image' ))

						$image_path=url ( '/icons/404.png');

					DB::table ( 'services' )
						->where ( 'id' , $id )
						->update ( ['image' => $image_path, 'updated_at' => $now] );
				}
				if ( $new_name->status !== $status and $status !== null )
					if ( $request->input ( 'status' ) == 0 or $request->input ( 'status' ) == 1 ) {
						DB::table ( 'services' )
							->where ( 'id' , $id )
							->update ( ['status' => $request->input ( 'status' ) , 'updated_at' => $now] );
					} else
						return $this->respondWithError ( 'status neeed to be 0 for false and 1 for ture' , self::fail );

			}


			$data = Services::find ( $id );

			return $this->responedFound200ForOneUser ( 'Services was updated' , self::success , $this->userTrans->transform ( $data ) );

		}


		public function get_one_services_by_section_id (Request $request)
		{
//dd('asd1');
			//				$date = Input::get ('date');


			$rules = array (
				'section_id' => 'required|integer' ,

			);
			$messages = array (
				'section_id.required' => 'section_id is required ' ,
				'section_id.integer' => 'section_id must be integer number' ,

			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();

			if ( $validator->fails () )
				if ( $errors->first ( 'section_id' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'section_id' ) );
			if ( $errors->first ( 'section_id' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'section_id' ) );


//			$user = Services::whereDate ( 'created_at' , '=' , date ( "Y-m-d" , strtotime ( Input::get ( 'date' ) ) ) )->first ();

			$s = Section::find ( $request->input ( 'section_id' ) );

			if ( !is_object ( $s ) and !is_array ( $s ) )
				return $this->respondWithError ( 'section services not found' , self::fail );
			else
				return $this->responedFound200ServicesC ( 'Services Found' , self::success ,
					$this->userTrans->transformCollection ( $s->services->toArray () ) );

		}

		public function responedFound200ServicesC ($massage , $status , $data)
		{
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
				'services_count' => count ( $data ) ,
				'data' => $data
			] );
		}

		public function services_supplier_id_s (Request $request)
		{
			$supp_id = $request->input ( 'supplier_id' );
//	dd('asdasd');
			$services = Supplier::with ( 'services' )->where ( 'id' , $supp_id )
				->where ( 'status' , 1 )->get ();
//	dd($services);
			if ( !$services->first () )
				return $this->respondWithError ( 'services for suppliers id ' . $supp_id . '  not found' , self::fail );
			else
				return $this->responedFound200Services_withSuppliers_id ( 'found services with suppliers' , self::success , $services->all () );

		}

		public function responedFound200Services_withSuppliers_id ($massage , $status , $data)
		{
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
//				'services_count' => count ( $data ) ,
				'data' => $data

			] );
		}

		public function services_suppilers (Request $request)
		{
			if ( $request->input ( 'suppliers' ) == 1 or $request->input ( 'suppliers' ) == 'true' ) {
//			$service = $request->input ( 'suppliers' );{
				$service = Services::with ( 'suppliers' )->where ( 'status' , 1 )->get ();
//			dd($service->toArray ());
//			$services = Services::where('status',1)->get();

//			dd($services->toArray ());

//dd($service[0]['suppliers']['name']);
//			foreach($services as $serve)
//			{
//				$a=$serve;
//				foreach($serve->suppliers  as $user)
//				{
//					$b=$user;
//				}
//			}
////dd($b->toArray());
//			$a=$a->toArray ();
//			return (string) $service;


//			return $this->responedFound200ServicesCCC ('sucsess',self::success,$this->userTrans->transform($a),
//				$this->use->transform ($b));
				return $this->responedFound200ServicesCCC ( 'sucsess' , self::success , $service->all () );
			} else
				return $this->respondWithError ( 'must enter status 1 od true found ' , self::fail );


		}

		public function responedFound200ServicesCCC ($massage , $status , $data)
		{
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
				'services_count' => count ( $data ) ,
				'data' => $data

			] );
		}


		/**
		 * @param Request $request
		 * @return mixed
		 */
		public function services_section_supplier_id (Request $request)
		{
//dd('services_supp');
			$section_id = $request->input ( 'section_id' );
			$supplier_id = $request->input ( 'supplier_id' );


			$rules = array (
				'section_id' => 'required|integer' ,
				'supplier_id' => 'required|integer' ,

			);
			$messages = array (
				'section_id.required' => 'section_id is required ' ,
				'section_id.integer' => 'section_id must be integer number' ,

			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();

			if ( $validator->fails () )
				if ( $errors->first ( 'section_id' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'section_id' ) );
			if ( $errors->first ( 'supplier_id' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'supplier_id' ) );

			$users = DB::table ( 'section_services' )
				->select ( 'section_services.services_id' , 'services_suppliers.supplier_id' , 'section_services.section_id' )
				->leftJoin ( 'services_suppliers' , 'section_services.services_id' , '=' , 'services_suppliers.services_id' )
				->where ( 'section_services.section_id' , $section_id )
				->where ( 'services_suppliers.supplier_id' , $supplier_id )
				->get ();

//here
//			$ar= [];
//			$a = json_decode($users, true);
//			foreach($a as   $v )
//			{

//		echo		$i['services_id'];

			$section_services = DB::table ( 'section_services' )
				->select ( 'section_services.services_id' , 'services_suppliers.supplier_id' , 'section_services.section_id' )
				->leftJoin ( 'services_suppliers' , 'section_services.services_id' , '=' , 'services_suppliers.services_id' )
				->where ( 'section_services.section_id' , '=' , $section_id )
//				->whereRaw ( 'section_services.services_id' , '!=' , $se)
				->get ();

//			}

			foreach ($users as $u) {

				foreach ($section_services as $key => $s) {

					if ( $u->services_id == $s->services_id )
						if ( $u->supplier_id == $s->supplier_id )
							$section_services->forget ( $key );

				}

			}

			if ( !$users->first () )
				return $this->respondWithError ( 'section and supplier for section '
					. $section_id
					. '   and supplier id     ' .
					$supplier_id
					. '  Not Found' , self::fail );
			else
				return $this->responedFound200SectionSupplierId ( 'section with services found ' ,
					self::success , $users , $section_services );
		}


		/*SELECT section_services.services_id ,
		section_services.section_id, services_suppliers.supplier_id from section_services
		left JOIN services_suppliers ON section_services.services_id=services_suppliers.services_id
		WHERE section_services.section_id=1 AND services_suppliers.supplier_id=1
		*/

		public function responedFound200SectionSupplierId ($massage , $status , $data , $data2)
		{
//			$data->toArray();
//			$data2->toArray();
			$array3 = array_merge ( $data->toArray () , $data2->toArray () );

//			$array3 = array ('assigned_services'=>$data,'unassigned_services'=>$data2);


			foreach ($data as $user) {
				$user->is_added = 'true';
			}
			foreach ($data2 as $user)
				$user->is_added = 'false';
//			dd($data2);
//			$is_added= array ('is_added'=>true);
////			$array3 = a($is_added,$data);
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
				'data' => $array3 ,


			] );
		}

		public function assign_services (Request $request)
		{
//dd('ass');
			$service_id = $request->input ( 'services_id' );
			$supplier_id = $request->input ( 'supplier_id' );

			$rules = array (
				'services_id' => 'required|integer|exists:services,id' ,
				'supplier_id' => 'required|integer|exists:suppliers,id' ,

			);
			$messages = array (
				'services_id.required' => 'services_id is required ' ,
				'services_id.integer' => 'services_id must be integer number' ,
				'supplier_id.required' => 'supplier_id is required ' ,
				'supplier_id.integer' => 'service_id must be integer number' ,

			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();

			if ( $validator->fails () )
				if ( $errors->first ( 'services_id' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'services_id' ) );
			if ( $errors->first ( 'supplier_id' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'supplier_id' ) );

			if ( $service_id != null and $supplier_id != null ) {
//				dd('qwe');
				$find_service = Services::find ( $service_id );
				$find_supplier = Supplier::find ( $supplier_id );
				$db = DB:: table ( 'services_suppliers' )
					->where ( 'services_id' , $service_id )
					->where ( 'supplier_id' , $supplier_id );
				if ( $db->first () == null )
					if ( $find_supplier )
						if ( $find_service )
							$find_service->suppliers ()->attach ( $supplier_id );
						else
							return $this->respondWithError ( 'services not found' , self::fail );
					else
						return $this->respondWithError ( 'supplier not found' , self::fail );
				else
					return $this->respondWithError ( 'service for supplier is already added' , self::fail );

			}

			return $this->responedCreated200 ( 'supplier and services are added' , self::success );
		}


		public function unAssign_services (Request $request)
		{
//			dd('ana assigned');
			$service_id = $request->input ( 'service_id' );
			$supplier_id = $request->input ( 'supplier_id' );

			$rules = array (
				'service_id' => 'required|integer' ,
				'supplier_id' => 'required|integer' ,

			);
			$messages = array (
				'service_id.required' => 'service_id is required ' ,
				'service_id.integer' => 'service_id must be integer number' ,
				'supplier_id.required' => 'service_id is required ' ,
				'supplier_id.integer' => 'service_id must be integer number' ,

			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();

			if ( $validator->fails () )
				if ( $errors->first ( 'supplier_id' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'supplier_id' ) );
			if ( $errors->first ( 'service_id' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'service_id' ) );

			if ( $service_id != null and $supplier_id != null ) {
				$find_supplier = Supplier::find ( $supplier_id );
				$find_service = Services::find ( $service_id );
				$db = DB:: table ( 'services_suppliers' )->where ( 'service_id' , $service_id )->where ( 'supplier_id' , $supplier_id );
//				dd('asdaqax');
				if ( $db->first () !== null ) {

					$find_service->suppliers ()->detach ( $supplier_id );

					return $this->responed_Destroy200 ( 'supplier  :'.$supplier_id.' and services : '.$service_id
						.' are deleted' , self::success );
				} else
					return $this->respondWithError ( 'service : '.$service_id
						.' and supplier  :'.$supplier_id.' dose not found' , self::fail );

			}

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
	}
