<?php
	/**
	 * Created by PhpStorm.
	 * User: dark-
	 * Date: 10/24/2017
	 * Time: 4:17 PM
	 */

	namespace App\Http\Controllers;


	use App\Admin;
	use App\Section;
	use App\section_services;
	use App\Services;
	use Carbon\Carbon;
	use Illuminate\Contracts\Pagination\Paginator;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\Response;
	use Novent\Transformers\section_servicesTra;
	use Novent\Transformers\sectionTransform;

	use \Validator;

	class SectionServices extends Controller
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
		protected $use;
		protected $services_Trans;
		/**
		 * @var int
		 */
		protected $statusCode = 200;

		public function __construct (sectionTransform $userTrans , section_servicesTra $use)
		{
			$this->middleware ( 'auth:api' );
			$this->userTrans = $userTrans;
			$this->use = $use;

		}

		public static function get_one_sections ($id = null)
		{
			//get section with id to apply on services class
			$section = section_services::where ( 'section_id' , $id )->where ( 'status' , 1 )->get ();

			return ($section);

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

		public function getAllSection ()
		{
			//get all section with status true
			$Section = Section::where ( 'status' , true )->get ();

			return $this->responedFound200
			( 'Section found' , self::success , $this->userTrans->transformCollection ( $Section->all () ) );

		}

		public function responedFound200 ($massage , $status , $data)
		{
			// response for found
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
				'size' => count ( $data ) ,
				'data' => $data
			] );
		}
// cheack it if project crushed
//		public function get_one_services ($id = null)
//		{
////
//			$Section = Section::where ( 'id' , $id )->where ( 'status' , true )->first ();
//
//
//			if ( !$Section ) {
//				return $this->respondNotFound ( 'Section dose not found' );
//
//			}
//
//
//			return $this->responedFound200ForOneUser ( 'Service found' , self::success , $this->userTrans->transform ( $Section ) );
//
//			//				]);
//		}

		public function create_Section (Request $request)
		{
			$admin_id = $request->input ( 'admin_id' );
			$find = Admin::find ( $admin_id );
			$rules = array (
				'admin_id' => 'required|integer|exists:admins,id' ,
				'name_en' => 'required|regex:/^[\p{L}\s\.-]+$/|min:3|max:30' ,
				'name_ar' => 'required|regex:/^(?!.*\d)[a-z\p{Arabic}\s]+$/iu|min:3|max:50' ,
				'desc_en' => 'required|min:3|max:140' ,
				'desc_ar' => 'required|min:3|max:140' ,

				'image' => 'string' ,
			);
			$messages = array (
				'name_en.regex' => 'please Enter valid Name || يرجى ادخال الاسم بالغة الانجليزية' ,

				'name_en.required' => 'Enter name. || يرجى ادخال الاسم بالغة الانجليزية ' ,
				'name_en.min' => 'The name_en min is 3. || اقل عدد احرف للأسم 3 احرف ' ,
				'name_en.max' => 'The name_en min is 30 || اكثر عدد احرف مسموح هو 30 حرف' ,
//				'name_ar.regex' => 'name_ar please Enter Name with only real char' ,

				'name_ar.required' => 'Enter Name . || يرجى ادخال الاسم بالغة العربية ' ,
				'name_ar.regex' => 'Enter valid name. || يرجى ادخال الاسم بالغة العربية ' ,
				'name_ar.min' => 'The name_ar min is 3. || اقل عدد احرف للأسم 3 احرف ' ,
				'name_ar.max' => 'The name_ar min is 30 || اكثر عدد احرف مسموح هو 30 حرف' ,

				'desc_en.required' => 'Enter description_en . || يرجى ادخال الوصف بالغة الانجليزية' ,
				'desc_en.min' => 'The description_en  min is 3. || اقل عدد حروف للوصف هو 3 احرف' ,
				'desc_en.max' => 'The description_en  max is 140|| اكثر عدد احرف للوصف هو 140 حرف' ,

				'desc_ar.required' => 'Enter description_ar. || يرجى ادخال الوصف بالغة العربية' ,
				'desc_ar.min' => 'The Enter description_ar min is 3. || اقل عدد حروف للوصف هو 3 احرف' ,
				'desc_ar.max' => ' The Enter description_ar max is 140 ||  اكثر عدد احرف للوصف هو 140 حرف' ,
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'admin_id' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'admin_id' ) );
			if ( $errors->first ( 'name_en' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'name_en' ) );
			if ( $errors->first ( 'name_ar' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'name_ar' ) );
			if ( $validator->fails () )
				if ( $errors->first ( 'desc_en' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'desc_en' ) );
			if ( $errors->first ( 'desc_ar' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'desc_ar' ) );

			if ( $errors->first ( 'image' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'image' ) );

			else {
				$image_path = url ( '/icons/404.png' );

				if ( $request->input ( 'image' ) ) {
					if ( file_exists ( base_path ( 'icons//' . $request->input ( 'image' ) ) ) ) {
						$image_path = url ( 'icons/' . $request->input ( 'image' ) );
					} else {
						$image_path = url ( '/icons/404.png' );
					}

				} elseif ( !$request->input ( 'image' ) )

					$image_path = url ( '/icons/404.png' );

				$section = Section::create ( [
					'name_en' => $request->input ( 'name_en' ) ,
					'name_ar' => $request->input ( 'name_ar' ) ,
					'desc_en' => $request->input ( 'desc_en' ) ,
					'desc_ar' => $request->input ( 'desc_ar' ) ,
					'image' => $image_path ,
				] )->id;

				return $this->responedCreated200 ( ' successfully Created !' , self::success , $section );
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

		public function responedCreated200 ($massage , $status , $id = null)
		{
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'id' => $id ,
				'status' => $this->status ( $status )
				, 'code' => $this->statusCode
			] );
		}

		public function delete_Section ($id)
		{
			// delete section with response
			$now = Carbon::now ( 'GMT+2' );
			$Section = Section::find ( $id );
			if ( !$Section ) {
				return $this->respondWithError ( 'Section for id:' . $id . ' is not Exiting' , self::fail );
			} else {
				DB::table ( 'sections' )
					->where ( 'id' , $id )
					->update ( ['status' => false , 'deleted_at' => $now] );


				return $this->responed_Destroy200 ( 'Section was deleted ' , self::success );

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

		public function update_section (Request $request , $id)
		{
			// update section info

			$rules = array (

				'name_en' => 'regex:/^[\p{L}\s\.-]+$/|min:3|max:30' ,
				'name_ar' => 'regex:/^(?!.*\d)[a-z\p{Arabic}\s]+$/iu|min:3|max:50' ,
				'desc_en' => 'min:3|max:140' ,
				'desc_ar' => 'min:3|max:140' ,
				'image' => 'string' ,
			);

			$messages = array (
				'name_en.regex' => 'please Enter valid Name || يرجى ادخال الاسم بالغة الانجليزية' ,

				'name_en.min' => 'The name_en min is 3. || اقل عدد احرف للأسم 3 احرف ' ,
				'name_en.max' => 'The name_en min is 30 || اكثر عدد احرف مسموح هو 30 حرف' ,

				'name_ar.regex' => 'Enter valid name. || يرجى ادخال الاسم بالغة العربية ' ,
				'name_ar.min' => 'The name_ar min is 3. || اقل عدد احرف للأسم 3 احرف ' ,
				'name_ar.max' => 'The name_ar min is 30 || اكثر عدد احرف مسموح هو 30 حرف' ,


				'desc_en.min' => 'The description_en  min is 3. || اقل عدد حروف للوصف هو 3 احرف' ,
				'desc_en.max' => 'The description_en  max is 140|| اكثر عدد احرف للوصف هو 140 حرف' ,


				'desc_ar.min' => 'The Enter description_ar min is 3. || اقل عدد حروف للوصف هو 3 احرف' ,
				'desc_ar.max' => ' The Enter description_ar max is 140 ||  اكثر عدد احرف للوصف هو 140 حرف' ,
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );

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


			$findid = Section::find ( $id );

			$name_en = $request->input ( 'name_en' );
			$name_ar = $request->input ( 'name_ar' );
			$desc_en = $request->input ( 'desc_en' );
			$desc_ar = $request->input ( 'desc_ar' );
			$status = $request->input ( 'status' );
			$image = $request->input ( 'image' );

			$now = Carbon::now ( 'GMT+2' );

			if ( !$findid )
				return $this->respondWithError ( 'Section not found ' , self::fail );
			else {

				$new_name = DB::table ( 'sections' )->where ( 'id' , $id )->first ();

				if ( $new_name->name_en !== $name_en and $name_en !== null ) {
					DB::table ( 'sections' )
						->where ( 'id' , $id )
						->update ( ['name_en' => $request->input ( 'name_en' ) , 'updated_at' => $now] );

				}
				if ( $new_name->name_ar !== $name_ar and $name_ar !== null ) {
					DB::table ( 'sections' )
						->where ( 'id' , $id )
						->update ( ['name_ar' => $request->input ( 'name_ar' ) , 'updated_at' => $now] );

				}

				if ( $new_name->desc_en !== $desc_en and $desc_en !== null ) {
					DB::table ( 'sections' )
						->where ( 'id' , $id )
						->update ( ['desc_en' => $request->input ( 'desc_en' ) , 'updated_at' => $now] );

				}

				if ( $new_name->desc_ar !== $desc_ar and $desc_ar !== null ) {
					DB::table ( 'sections' )
						->where ( 'id' , $id )
						->update ( ['desc_ar' => $request->input ( 'desc_ar' ) , 'updated_at' => $now] );

				}


				if ( $image !== null ) {
					$image_path = url ( '/icons/404.png' );

					if ( $request->input ( 'image' ) ) {
						if ( file_exists ( base_path ( 'icons//' . $request->input ( 'image' ) ) ) ) {
//						dd('File is exists.');
							$image_path = url ( 'icons/' . $request->input ( 'image' ) );
						} else {
							$image_path = url ( '/icons/404.png' );
						}
					} elseif ( !$request->input ( 'image' ) )
						$image_path = url ( '/icons/404.png' );

					DB::table ( 'sections' )
						->where ( 'id' , $id )
						->update ( ['image' => $image_path , 'updated_at' => $now] );
				}

				if ( $new_name->status !== $status and $status !== null )
					if ( $request->input ( 'status' ) == 0 or $request->input ( 'status' ) == 1 ) {
						DB::table ( 'sections' )
							->where ( 'id' , $id )
							->update ( ['status' => $request->input ( 'status' ) , 'updated_at' => $now] );
					} else
						return $this->respondWithError ( 'status neeed to be 0 for false and 1 for ture' , self::fail );
			}


			$data = Section::find ( $id );

			return $this->responedFound200ForOneUser ( 'Section was updated' , self::success , $this->userTrans->transform ( $data ) );

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

		public function get_one_user ($id = null , Request $request)
		{
			//to get section with services
			$section = Section::where ( 'id' , $id )->where ( 'status' , true )->first ();
			if ( !$section ) {
				return $this->respondNotFound ( 'Section dose not found' );

			} elseif ( $section and !Input::has ( 'service' ) )
				return $this->responedFound200ForOneUser ( 'Section found' , self::success ,
					$this->userTrans->transform ( $section ) );


			elseif ( $section and ($request->input ( 'service' ) == 1 or $request->input ( 'service' ) == true) )
				$sectionWithServices = Section::with ( 'services' )->where ( 'id' , $id )->where ( 'status' ,
					$request->input ( 'service' ) )->get ();

			return $this->responedFound200ForOneUser ( 'Section found' , self::success , $sectionWithServices->toArray () );


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

		public function get_sectionid (Request $request , $id)
		{
			// get sections services
			$rules = array (
				'services' => 'required|min:0|max:1' ,

			);
			$messages = array (

				'services.required' => 'services required for me || يرجى ادخال services' ,
				'services.min' => 'services must be 0 or 1 || يرجى ادخال 0 او 1' ,
				'services.max' => 'services must be 0 or 1 || يرجى ادخال 0 او 1' ,
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );

			$errors = $validator->errors ();

			if ( $validator->fails () )
				if ( $errors->first ( 'services' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'services' ) );
			if ( $errors->first ( 'services' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'services' ) );

			$section_services = Section::find ( $request->input ( $id ) );

			if ( !is_object ( $section_services ) and !is_array ( $section_services ) )
				return $this->respondWithError ( 'section services not found' , self::fail );
			else
				return $this->responedFound200ServicesC ( 'Services Found' , self::success ,
					$this->userTrans->transformCollection ( $section_services->services->toArray () ) );


		}

		public function responedFound200ServicesC ($massage , $status , $data)
		{
			// created with spacifc data and massage and status code
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
				'size' => count ( $data ) ,
				'data' => $data
			] );
		}

		public function section_with_services (Request $request)
		{
			//get all sections with services
			$service = $request->input ( 'service' );


			$section = Section::with ( 'services' )->where ( 'status' , $service )->get ();


			return $this->responedFound200ServicesCC ( 'Sections Found' , self::success , $section->toArray () );


		}

		public function responedFound200ServicesCC ($massage , $status , $data)
		{
			// diffrant response
			return $this->setStatusCode ( self::HTTP_OK )->respond ( [
				'massage' => $massage ,
				'status' => $this->status ( $status ) ,
				'code' => $this->statusCode ,
				'size' => count ( $data ) ,
				'data' => $data

			] );
		}


	}