<?php


	namespace App\Http\Controllers\api;

	use App\Http\Controllers\Controller;
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

			return  SupplierServices::getSupplierById  ($id);

		}

		public function store (Request $request)
		{
//			dd($request->input ('name'));

			return SupplierServices::createSupplier   ($request);

		}


		/**
		 * @param $id
		 * @return mixed
		 */
		public function destroy ($id)
		{

			return SupplierServices::deleteSupplier ($id);

		}

		/**
		 * @param Request $request
		 * @param $id
		 * @return mixed
		 */
		public function update (Request $request , $id)
		{
			return SupplierServices::updateSupplier ($request,$id);

		}

		/**
		 * @param Request $request
		 * @return mixed
		 */
		public function getPhoneQuery (Request $request)
		{
//				if($request->has ('phone'))
			return SupplierServices::getPhoneQuery ($request);

		}


		/**
		 * @param Request $request
		 * @return mixed
		 */
		public function getDateQuery (Request $request)
		{

			return SupplierServices::getDateQuery($request);

		}

//		public function get_user_date ()
//		{
//			return $this->get_user_date ();
//		}

		public function getSuppliersSingleDate (Request $request)
		{
			return SupplierServices::getSuppliersSingleDate($request);
		}

		public function login ()
		{
			if ( Auth::attempt ( ['email' => request ( 'email' ) , 'password' => request ( 'password' )] ) ) {
				$user = Auth::user ();

				if ( Auth::user ()->type == 1 ) {

					$this->content['token'] = $user->createToken ( 'Noventapp' )->accessToken;
					$user_i = SupplierModel::all ()->where ( 'email' , request ( 'email' ) )->first ()->toArray ();

					if ( $user_i['status'] == 1 )
						$user_i = $this->return_r ( $user_i , $this->content );
					else {
						return $this->respondWithError ( 'ACCOUNT IS SUSPENDED || الحساب مقفل' , self::fail );

					}
				} else
					return $this->respondWithError
					( 'the user trying to login is not a SupplierModel || المستخدم الذي يحاول تسجيل الدخول ليس من نوع موزع' , self::fail );

				return $this->responedFound200ForOneUser
				( 'SupplierModel login success ' , self::success , $user_i );

			} else {
				return $this->respondWithError
				( 'wrong email or password || البريد الالكتروني او كلمة المرور غير صحيحة' , self::fail );


			}

		}

		private function return_r ($x , $y)
		{
			//to spacifay and get the needed result
			//$x for supplier $y for token
			return [
				'supplier_id' => $x['id'] ,
				'token' => $y['token']
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

			if ( Auth::check () )
				return $this->respondWithError ( 'logout fail' , self::fail );
			else
				return $this->responedCreated200 ( 'logout success' , self::success );
//				return dd ( 'logout success' );
		}

		public function getSupplierByEmail (Request $request)
		{

			return SupplierServices::getSupplierByEmail ($request);

		}

		public function getSupplierEmailPhoneNum (Request $request)
		{
			return SupplierServices::getSupplierEmailPhoneNum($request);
		}

		public function getInactiveSuppliers (Request $request)
		{
			return SupplierServices::getInactiveSuppliers ($request);
		}
			/*suppliers_services_id_s not updated yet  */
		public function suppliers_services_id_s (Request $request)
		{
//			dd('suppler services');
			return SupplierServices::getSupplierByServiceId($request);
		}
	}