<?php


	namespace App\Http\Controllers\api;

	use App\Http\Controllers\Controller;
	use App\Http\Controllers\ResponseCode;
	use App\Http\Controllers\ResponseDisplay;
	use App\Http\Controllers\ResponseMassage;
	use App\Http\Controllers\ResponseStatus;
	use App\Http\Controllers\SupplierServices;
//	use App\Http\Controllers\UserServices;
	use App\SupplierModel;
	use Illuminate\Http\JsonResponse;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Lcobucci\JWT\Parser;
	use function MongoDB\BSON\toJSON;
	use Unit\Transformers\SupplierTransform;
	use Psy\Util\Json;
	use Response;
	use Validator;

	//	use Illuminate\Http\Request;


	class SupplierController extends Controller
	{
		/**
		 * @var  Novent\Transformers\SupplierTransform
		 */
		protected $userTrans;

		public function __construct ()
		{
//			$this->userTrans = $userTrans;

			$this->content = array ();

			$this->middleware ( 'auth:api' )->except ( 'login' , 'logout' , 'store' );


		}

		public function index ()
		{

			return SupplierServices::getAllSupplier ();

		}


		/**
		 * @param null $id
		 * @return mixed
		 */
		public function show ($id = null)
		{

			return SupplierServices::getSupplierById ( $id );

		}

		public function store (Request $request)
		{
//			dd($request->input ('name'));

			return SupplierServices::createSupplier ( $request );

		}


		/**
		 * @param $id
		 * @return mixed
		 */
		public function destroy ($id)
		{

			return SupplierServices::deleteSupplier ( $id );

		}

		/**
		 * @param Request $request
		 * @param $id
		 * @return mixed
		 */
		public function update (Request $request , $id)
		{
			return SupplierServices::updateSupplier ( $request , $id );

		}

		/**
		 * @param Request $request
		 * @return mixed
		 */
		public function getPhoneQuery (Request $request)
		{
			return SupplierServices::getPhoneQuery ( $request );

		}


		/**
		 * @param Request $request
		 * @return mixed
		 */
		public function getDateQuery (Request $request)
		{

			return SupplierServices::getDateQuery ( $request );

		}


		public function getSuppliersSingleDate (Request $request)
		{
			return SupplierServices::getSuppliersSingleDate ( $request );
		}

		public function login ()
		{
			if ( Auth::attempt ( ['email' => request ( 'email' ) , 'password' => request ( 'password' )] ) ) {
				$userObject = Auth::user ();

				if ( Auth::user ()->type == 1 ) {

					$this->content['token'] = $userObject->createToken ( 'Noventapp' )->accessToken;
					$userInfo = SupplierModel::all ()->where ( 'email' , request ( 'email' ) )->first ()->toArray ();

					if ( $userInfo['status'] == 1 )
						$userInfo = $this->return_r ( $userInfo , $this->content );
					else {
//						return $this->respondWithError ( 'ACCOUNT IS SUSPENDED || الحساب مقفل' , self::fail );
						$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Deactivated_Supplier_Error_en , ResponseStatus::$fail ,
							ResponseCode::$HTTP_UNAUTHORIZED );

						return $objResponse->returnWithOutData ();
					}
				} else {
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_NOT_Supplier_Error_en , ResponseStatus::$fail ,
						ResponseCode::$HTTP_UNAUTHORIZED );

					return $objResponse->returnWithOutData ();

				}

				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Login_en , ResponseStatus::$success ,
					ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $userInfo );

			} else {
				$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_LOGIN_USER_EMAIL_PASSWORD , ResponseStatus::$fail ,
					ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();

			}

		}

		private function return_r ($supplierID , $token)
		{
			//to spacifay and get the needed result
			//$x for supplier $y for token
			return [
				'supplier_id' => $supplierID['id'] ,
				'token' => $token['token']
			];

		}

		public function logout (Request $request)
		{
//			dd('asd');
			$value = $request->bearerToken ();
			$id = (new Parser())->parse ( $value )->getHeader ( 'jti' );

			$token = DB::table ( 'oauth_access_tokens' )
				->where ( 'id' , '=' , $id )
				->update ( ['revoked' => true] );


//			Auth::guard ()->logout();

			if ( Auth::check () ) {
				$objResponse = new ResponseDisplay( ResponseMassage::$fail_logout_en , ResponseStatus::$fail ,
					ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			} else {
				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_logout_en , ResponseStatus::$success ,
					ResponseCode::$HTTP_OK );

				return $objResponse->returnWithOutData ();
			}
//				return dd ( 'logout success' );
		}

		public function getSupplierByEmail (Request $request)
		{

			return SupplierServices::getSupplierByEmail ( $request );

		}

		public function getSupplierEmailPhoneNum (Request $request)
		{
			return SupplierServices::getSupplierEmailPhoneNum ( $request );
		}

		public function getInactiveSuppliers (Request $request)
		{
			return SupplierServices::getInactiveSuppliers ( $request );
		}

		public function getSupplierByServiceId (Request $request)
		{

			return SupplierServices::getSupplierByServiceId ( $request );
		}
	}