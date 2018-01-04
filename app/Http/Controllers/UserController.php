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

		public function __construct ()
		{

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
			return UserServices::getUserById ( $id );
		}

		/**
		 * @param Request $request
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function store (Request $request)
		{

			return UserServices::createUser ( $request );
		}


		/**
		 * @param $id
		 * @return mixed
		 */
		public function destroy ($id)
		{

			return UserServices::deleteUser ( $id );
		}

		/**
		 * @param Request $request
		 * @param $id
		 * @return mixed
		 */
		public function update (Request $request , $id)
		{
			return UserServices::updateUser ( $request , $id );
		}

		/**
		 * @param Request $request
		 * @return mixed
		 */
		public function getUserByPhone (Request $request)
		{
			return UserServices::getPhoneQuery ( $request );
		}


		/**
		 * @param Request $request
		 * @return mixed
		 */
		public function getUserByFlightDate (Request $request)
		{
			return UserServices::getDateQuery ( $request );


		}


		/**
		 * @param Request $request
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function getUserBySingleDate (Request $request)
		{
			return UserServices::getUserSingleDate ( $request );
		}

		/**
		 * @return mixed
		 */
		public function login ()
		{
			if ( Auth::attempt ( ['email' => request ( 'email' ) , 'password' => request ( 'password' )] ) ) {

				$userObject = Auth::user ();
				$path = 'com.example.novapp_tasneem.serviceexpress.userFragments.userProfileFragment';
				$this->content['token'] = $userObject->createToken ( 'Noventapp' )->accessToken;
				if ( Auth::user ()->type == 0 ) {
					$userInfo = UserModel::all ()->where ( 'email' , request ( 'email' ) )->first ()->toArray ();

					$address = UserModel::find ( $userInfo['id'] )->address ()->get ()->first ();
					if ( $address )
						$path = 'com.example.novapp_tasneem.serviceexpress.userFragments.userCategoryFragment';
					elseif ( !$address )
						$path = 'com.example.novapp_tasneem.serviceexpress.userFragments.userProfileFragment';

					if ( $userInfo['status'] == 1 )
						$userInfo = $this->return_r ( $userInfo , $this->content , $path );//,$path );
					else {

						$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Deactivated_User_Error_en , ResponseStatus::$fail
							, ResponseCode::$HTTP_UNAUTHORIZED );

						return $objResponse->returnWithOutData ();

					}

				} else {
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_NOT_User_Error_en , ResponseStatus::$fail , ResponseCode::$HTTP_UNAUTHORIZED );

					return $objResponse->returnWithOutData ();
				}

				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Login_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $userInfo );
			} else {
				$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_LOGIN_USER_EMAIL_PASSWORD , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

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


		/**
		 * @param Request $request
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function getUserByEmailAddress (Request $request)
		{

			return UserServices::getUserByEmail ( $request );

		}

		/**
		 * @param Request $request
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function getUserByPhoneAndEmailAddress (Request $request)
		{

			return UserServices::getUserEmailPhoneNum ( $request );
		}

		/**
		 * @param Request $request
		 * @return \Illuminate\Http\JsonResponse
		 */
		public function getInactiveUsers (Request $request)
		{
			return UserServices::getInactiveUsers ( $request );

		}

	}