<?php


	namespace App\Http\Controllers\api;

	use App\AdminModel;
	use App\Http\Controllers\AdminServices;
//	use App\Http\Controllers\UserServices;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Lcobucci\JWT\Parser;
	use Unit\Transformers\AdminTransfom;
	use Response;
	use Validator;

	//	use Illuminate\Http\Request;


	class AdminController extends Controller
	{

		protected $userTrans;

		public function __construct (AdminTransfom $userTrans)
		{
			$this->userTrans = $userTrans;

			$this->content = array ();

			$this->middleware ( 'auth:api' )->except ( 'login' , 'logout' ,'store');


		}

		public function index ()
		{

			return AdminServices::getAllAdmin ();

		}


		/**
		 * @param null $id
		 * @return mixed
		 */
		public function show ($id = null)
		{

			return AdminServices::getOneAdminById ( $id );

		}

		public function store (Request $request)
		{

			return AdminServices::createAdmin ($request);

		}


		public function destroy ($id)
		{

			return AdminServices::deleteAdmin ($id);

		}

		public function update (Request $request , $id)
		{
			return AdminServices::updateAdmin ($request,$id);
		}


		public function getAdminByPhoneNum (Request $request)
		{
			return AdminServices::getAdminByPhoneNum ($request);
		}


		/**
		 * @return mixed
		 */
		public function getDateQuery (Request $request)
		{

			return AdminServices::getDateQuery  ($request);

		}



		public function getAdminSingleDate (Request $request)
		{
			return AdminServices::getAdminSingleDate ($request);
		}

		public function login ()
		{
			if ( Auth::attempt ( ['email' => request ( 'email' ) , 'password' => request ( 'password' )] ) ) {
				$user = Auth::user ();

				if ( Auth::user ()->type == 2 ) {
					$this->content['token'] = $user->createToken ( 'Noventapp' )->accessToken;
					$user_i = AdminModel::all ()->where ( 'email' , request ( 'email' ) )->first ()->toArray ();

					if ( $user_i['status'] == 1 )
						$user_i = $this->return_r ( $user_i , $this->content );
					else {
						return $this->respondWithError ( 'ACCOUNT IS SUSPENDED || الحساب مقفل ' , self::fail );

					}
				} else
					return $this->respondWithError
					( 'the user trying to login is not a AdminModel || المستخدم الذي يحاول تسجيل الدخول ليس من نوع مسؤول' , self::fail );

//dd($user->getRememberToken ('Noventapp')->accessToken);
				return $this->responedFound200ForOneUser
				( 'AdminModel login success' , self::success , $user_i );
			} else {
				return $this->respondWithError
				( 'wrong email or password || البريد الالكتروني او كلمة المرور غير صحيحة' , self::fail );
			}

		}

		private function return_r ($x , $y)
		{
			//to spacifay and get the needed result
			//$x for admin $y for token
			return [
				'admin_id' => $x['id'] ,
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
		}

		public function getAdminByEmail (Request $request)
		{

			return AdminServices::getAdminByEmail  ( $request );

		}

		public function getAdminEmailPhoneNum (Request $request)
		{
			return AdminServices::getAdminEmailPhoneNum  ( $request );
		}

		public function getInactiveAdmin (Request $request)
		{
			return AdminServices::getInactiveAdmin  ( $request );
		}
	}