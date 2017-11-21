<?php

	namespace App\Http\Controllers\api;

	use App\Http\Controllers\UserServices;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Lcobucci\JWT\Parser;
	use Novent\Transformers\userTransfomer;
	use Response;
	use Validator;

	//	use Illuminate\Http\Request;


	class UserController extends UserServices
	{
		/**
		 * @var  Novent\Transformers\userTransfomer
		 */
		protected $userTrans;

		public function __construct (userTransfomer $userTrans)
		{
			$this->userTrans = $userTrans;

			$this->content = array ();

			$this->middleware ('auth:api')->except ('login', 'logout');


		}

		public function index ()
		{

			return $this->getAllUser ();

		}


		/**
		 * @param null $id
		 * @return mixed
		 */
		public function show ($id = null)
		{

			return $this->get_one_user ($id);

		}

		public function store (Request $request)
		{

			return $this->create_user ($request);

		}


		/**
		 * @param $id
		 * @return mixed
		 */
		public function destroy ($id)
		{

			return $this->delete_user ($id);

		}

		/**
		 * @param Request $request
		 * @param $id
		 * @return mixed
		 */
		public function update (Request $request, $id)
		{
			return $this->update_user ($request, $id);

		}

		/**
		 * @return mixed
		 */
		public function get_phone (Request $request)
		{
//				if($request->has ('phone'))
			return $this->get_phone_Query ($request);

		}


		/**
		 * @return mixed
		 */
		public function get_date (Request $request)
		{

			return $this->get_date_Query ($request);

		}

		public function get_user_date ()
		{
			return $this->get_user_date ();
		}

		public function get_user_by_date (Request $request)
		{
			return $this->get_one_user_date ($request);
		}

		public function login ()
		{
			if (Auth::attempt (['email' => request ('email'), 'password' => request ('password')])) {
				$user = Auth::user ();

				$this->content['token'] = $user->createToken ('Noventapp')->accessToken;

//dd($user->getRememberToken ('Noventapp')->accessToken);
				return $this->responedFound200ForOneUser
				('user login success', self::success, $this->content);
			} else {
				return $this->respondWithError ('wrong email or password', self::fail);
			}

		}

		public function logout (Request $request)
		{
//			dd('asd');
			$value = $request->bearerToken ();
			$id = (new Parser())->parse ($value)->getHeader ('jti');

			$token = DB::table ('oauth_access_tokens')
				->where ('id', '=', $id)
				->update (['revoked' => true]);


//			Auth::guard ()->logout();

			if (Auth::check ())
				return $this->respondWithError ('logout fail', self::fail);
			else
				return $this->responedCreated200 ('logout success', self::success);
		}

		public function details ()
		{

			return $this->responedFound200ForOneUser ('user found', self::success, $this->userTrans->transform (Auth::user ()));
		}


		public function get_user_email (Request $request)
		{

			return $this->get_user_by_email ($request);

		}


		public function get_email_phone (Request $request)
		{
			return $this->get_user_email_phonenum ($request);
		}

		public function get_inactives_users (Request $request)
		{
			return $this->get_inactive_users ($request);
		}
	}