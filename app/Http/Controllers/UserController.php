<?php

	namespace App\Http\Controllers\api;

	use App\Http\Controllers\Controller;
	use App\Http\Controllers\ResponseCode;
	use App\Http\Controllers\ResponseDisplay;
	use App\Http\Controllers\ResponseMassage;
	use App\Http\Controllers\ResponseStatus;
	use App\Http\Controllers\UserServices;
	use App\UserModel;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Lcobucci\JWT\Parser;
	use Response;
	use Unit\Transformers\userTransfomer;
	use Validator;

	//	use Illuminate\Http\Request;


	class UserController extends Controller
	{
		/**
		 * @var  Unit\Transformers\userTransfomer
		 */
		protected $userTrans;

		/**
		 * UserController constructor.
		 * @param userTransfomer $userTrans
		 */
		public function __construct (userTransfomer $userTrans)
		{
			$this->userTrans = $userTrans;

			$this->content = array ();

			$this->middleware ( 'auth:api' )->except ( 'login' , 'logout' , 'store' , 'index' );


		}

		/**
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function index ()
		{
			return UserServices::getAllUser ();
		}


		/**
		 * @param null $id
		 * @return mixed
		 */
		public function show ($id = null)
		{
//				$userObj= new UserServices();
//			 return	$userObj->getUserById ($id);
			return UserServices::getUserById ( $id );
//			return $this->getOneUser ( $id );

		}

		/**
		 * @param Request $request
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function store (Request $request)
		{

//			return $this->createUser ( $request );
			return UserServices::createUser ( $request );
		}


		/**
		 * @param $id
		 * @return mixed
		 */
		public function destroy ($id)
		{

//			return $this->deleteUser ( $id );
			return UserServices::deleteUser ( $id );
		}

		/**
		 * @param Request $request
		 * @param $id
		 * @return mixed
		 */
		public function update (Request $request , $id)
		{
//			return $this->updateUser ( $request , $id );
			return UserServices::updateUser ( $request , $id );
		}

		/**
		 * @param Request $request
		 * @return mixed
		 */
		public function getUserByPhone (Request $request)
		{
//				if($request->has ('phone'))
//			return $this->getPhoneQuery ( $request );
			return UserServices::getPhoneQuery ( $request );
		}


		/**
		 * @param Request $request
		 * @return mixed
		 */
		public function getUserByFlightDate (Request $request)
		{
			return UserServices::getDateQuery ( $request );
//			return $this->getDateQuery ( $request );

		}


		/**
		 * @param Request $request
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function getUserBySingleDate (Request $request)
		{
//			return $this->getOneUserDate ( $request );
			return UserServices::getUserSingleDate ( $request );
		}

		/**
		 * @return mixed
		 */
		public function login ()
		{
			if ( Auth::attempt ( ['email' => request ( 'email' ) , 'password' => request ( 'password' )] ) ) {

//				dd(Auth::user ()->type);

				$user = Auth::user ();
				$path='com.example.novapp_tasneem.serviceexpress.userFragments.userProfileFragment';
				$this->content['token'] = $user->createToken ( 'Noventapp' )->accessToken;
				if ( Auth::user ()->type == 0 ) {
					$user_i = UserModel::all ()->where ( 'email' , request ( 'email' ) )->first ()->toArray ();
				/*
						$address= UserModel::find ( $user_i['id'] )->address ()->get ()->first ();
					if($address)
						$path='com.example.novapp_tasneem.serviceexpress.userFragments.userCategoryFragment';
					elseif (!$address)
						$path='com.example.novapp_tasneem.serviceexpress.userFragments.userProfileFragment';*/

						if ( $user_i['status'] == 1 )
						$user_i = $this->return_r ( $user_i , $this->content , $path );//,$path );
					else {
//						return $this->respondWithError ( 'ACCOUNT IS SUSPENDED || الحساب مقفل' , self::fail );
						$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Deactivated_User_Error_en , ResponseStatus::$fail , ResponseCode::$HTTP_UNAUTHORIZED );
						return $objResponse->returnWithOutData ();

					}
//				dd($user_i);
				} //dd($user->getRememberToken ('Noventapp')->accessToken);
				else
				{
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_NOT_User_Error_en , ResponseStatus::$fail , ResponseCode::$HTTP_UNAUTHORIZED );
					return $objResponse->returnWithOutData ();
				}

				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Login_en , ResponseStatus::$success , ResponseCode::$HTTP_OK);
				return $objResponse->returnWithData ($user_i);
			} else {
				$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_LOGIN_USER_EMAIL_PASSWORD , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST);
				return $objResponse->returnWithOutData ();
			}

		}

		/**
		 * @param $userObject
		 * @param $userToken
		 * @param $path
		 * @return array

		 */
		private function return_r ($userObject , $userToken , $path)
		{

			return [
				'user_id' => $userObject['id'] ,
				'token' => $userToken['token'] ,
				'path' => $path
			];

		}

		/**
		 * @param Request $request
		 * @return mixed
		 */
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
				return $this->responedCreated200 ( 'logout success ' , self::success );
		}


		/**
		 * @param Request $request
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function getUserByEmailAddress (Request $request)
		{

//			return $this->getUserByEmail ( $request );
			return UserServices::getUserByEmail ( $request );

		}

		/**
		 * @param Request $request
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function getUserByPhoneAndEmailAddress (Request $request)
		{
//			return $this->getUserEmailPhoneNum ( $request );
			return UserServices::getUserEmailPhoneNum ( $request );
		}

		/**
		 * @param Request $request
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function getInactiveUsers (Request $request)
		{
			return UserServices::getInactiveUsers ( $request );
//			return $this->getInactiveUsers ( $request );
		}

	}