<?php


	namespace App\Http\Controllers\api;

	use App\Http\Controllers\SupplierServices;
//	use App\Http\Controllers\UserServices;
	use Illuminate\Http\JsonResponse;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Lcobucci\JWT\Parser;
	use function MongoDB\BSON\toJSON;
	use Novent\Transformers\SupplierTransform;
	use Psy\Util\Json;
	use Response;
	use Validator;

	//	use Illuminate\Http\Request;


	class SupplierController extends SupplierServices
	{
		/**
		 * @var  Novent\Transformers\SupplierTransform
		 */
		protected $userTrans;

		public function __construct (SupplierTransform $userTrans)
		{
			$this->userTrans = $userTrans;

			$this->content = array ();

			$this->middleware ( 'auth:api' )->except ( 'login' , 'logout' );


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

			return $this->get_one_user ( $id );

		}

		public function store (Request $request)
		{

			return $this->create_user ( $request );

		}


		/**
		 * @param $id
		 * @return mixed
		 */
		public function destroy ($id)
		{

			return $this->delete_user ( $id );

		}

		/**
		 * @param Request $request
		 * @param $id
		 * @return mixed
		 */
		public function update (Request $request , $id)
		{
			return $this->update_user ( $request , $id );

		}

		/**
		 * @return mixed
		 */
		public function get_phone (Request $request)
		{
//				if($request->has ('phone'))
			return $this->get_phone_Query ( $request );

		}


		/**
		 * @return mixed
		 */
		public function get_date (Request $request)
		{

			return $this->get_date_Query ( $request );

		}

		public function get_user_date ()
		{
			return $this->get_user_date ();
		}

		public function get_user_by_date (Request $request)
		{
			return $this->get_one_user_date ( $request );
		}

		public function login ()
		{
			if ( Auth::attempt ( ['email' => request ( 'email' ) , 'password' => request ( 'password' )] ) ) {
				$user = Auth::user ();

				$this->content['token'] = $user->createToken ( 'Noventapp' )->accessToken;

//dd($user->getRememberToken ('Noventapp')->accessToken);
				return $this->responedFound200ForOneUser
				( 'Supplier login success' , self::success , $this->content );
//				return ($this->content);
//				return response()->json($this->content, self::HTTP_CREATED);
			} else {
				return $this->respondWithError ( 'wrong email or password' , self::fail );
//				return response  ('application/json')->json ("ahmad");
//				response()->json('ahmad')->setStatusCode(Response::HTTP_OK);
//			return new JsonResponse('hello  ');
//				return dd('asd');
//				return response()->json(['wrong email or password' => 'asd'], $this->statusCode);
			}

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


		public function get_user_email (Request $request)
		{

			return $this->get_user_by_email ( $request );

		}


		public function get_email_phone (Request $request)
		{
			return $this->get_user_email_phonenum ( $request );
		}

		public function get_inactives_users (Request $request)
		{
			return $this->get_inactive_users ( $request );
		}


		public function suppliers_services_id_s (Request $request)
		{
			return $this->suppliers_services_id ( $request );
		}
	}