<?php


	namespace App\Http\Controllers\api;

	use App\AdminModel;
	use App\Http\Controllers\AdminServices;
//	use App\Http\Controllers\UserServices;
	use App\Http\Controllers\Controller;
	use App\Http\Controllers\ResponseCode;
	use App\Http\Controllers\ResponseDisplay;
	use App\Http\Controllers\ResponseMassage;
	use App\Http\Controllers\ResponseStatus;
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

			$this->middleware ( 'auth:api' )->except ( 'login' , 'logout' , 'store' );


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

			return AdminServices::createAdmin ( $request );

		}


		public function destroy ($id)
		{

			return AdminServices::deleteAdmin ( $id );

		}

		public function update (Request $request , $id)
		{
			return AdminServices::updateAdmin ( $request , $id );
		}


		public function getAdminByPhoneNum (Request $request)
		{
			return AdminServices::getAdminByPhoneNum ( $request );
		}


		/**
		 * @param Request $request
		 * @return mixed
		 */
		public function getDateQuery (Request $request)
		{

			return AdminServices::getDateQuery ( $request );

		}


		public function getAdminSingleDate (Request $request)
		{
			return AdminServices::getAdminSingleDate ( $request );
		}

		public function login ()
		{
			if ( Auth::attempt ( ['email' => request ( 'email' ) , 'password' => request ( 'password' )] ) ) {
				$userObject = Auth::user ();

				if ( Auth::user ()->type == 2 ) {
					$this->content['token'] = $userObject->createToken ( 'Noventapp' )->accessToken;
					$userInfo = AdminModel::all ()->where ( 'email' , request ( 'email' ) )->first ()->toArray ();

					if ( $userInfo['status'] == 1 )
						$userInfo = $this->return_r ( $userInfo , $this->content );
					else {
						$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Deactivated_Admin_Error_en , ResponseStatus::$fail ,
							ResponseCode::$HTTP_UNAUTHORIZED );

						return $objResponse->returnWithOutData ();

					}
				} else {
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_NOT_Admin_Error_en , ResponseStatus::$fail ,
						ResponseCode::$HTTP_UNAUTHORIZED );

					return $objResponse->returnWithOutData ();
				}

//dd($user->getRememberToken ('Noventapp')->accessToken);
				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Login_en , ResponseStatus::$success ,
					ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $userInfo );
			} else {
				$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_LOGIN_USER_EMAIL_PASSWORD , ResponseStatus::$fail ,
					ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
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


			if ( Auth::check () ) {
				$objResponse = new ResponseDisplay( ResponseMassage::$fail_logout_en , ResponseStatus::$fail , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithOutData ();
			} else {
				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_logout_en , ResponseStatus::$success
					, ResponseCode::$HTTP_OK );

				return $objResponse->returnWithOutData ();
			}
		}

		public function getAdminByEmail (Request $request)
		{

			return AdminServices::getAdminByEmail ( $request );

		}

		public function getAdminEmailPhoneNum (Request $request)
		{
			return AdminServices::getAdminEmailPhoneNum ( $request );
		}

		public function getInactiveAdmin (Request $request)
		{
			return AdminServices::getInactiveAdmin ( $request );
		}
	}