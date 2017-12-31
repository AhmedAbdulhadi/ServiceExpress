<?php

	namespace App\Http\Controllers\api;

//use App\Http\Controllers\Controller;
	use App\Http\Controllers\UserServices;
	use App\OrderModel;
	use App\SectionModel;
	use App\ServicesModel;
	use App\SupplierModel;
	use App\UserModel;
	use Illuminate\Http\Request;
	use Carbon\Carbon;
	use Illuminate\Support\Facades\DB;
	use \Validator;
	use Illuminate\Support\Facades\Input;

	class DashboardController extends UserServices
	{
		public function index ()
		{
			$now = Carbon::now ( 'GMT+2' );
//			$users = DB::table('users')->whereDate('created_at' ,raw('CURDATE()'))->get();
			$users = DB::table ( 'users' )->whereDate ( 'created_at' , DB::raw ( 'CURDATE()' ) )->get ();
			$supp = DB::table ( 'suppliers' )->whereDate ( 'created_at' , DB::raw ( 'CURDATE()' ) )->get ();
			$orders = DB::table ( 'orders' )->whereDate ( 'created_at' , DB::raw ( 'CURDATE()' ) )->get ();
			$services = DB::table ( 'services' )->whereDate ( 'created_at' , DB::raw ( 'CURDATE()' ) )->get ();
			$section = DB::table ( 'sections' )->whereDate ( 'created_at' , DB::raw ( 'CURDATE()' ) )->get ();
//    	dd(count ($services));

			$all = $this->return_r ( $users , $supp , $orders , $services , $section );

			return $this->responedFound200ForOneUser ( 'registration for today' , self::success , $all );


		}

		private function return_r ($users , $supp , $orders , $services , $section)
		{
			return [
				'users' => count ( $users ) ,
				'suppliers' => count ( $supp ) ,
				'orders' => count ( $orders ) ,
				'services' => count ( $services ) ,
				'sections' => count ( $section ) ,

			];

		}

		public function get_date_Query (Request $request)
		{
			$rules = array (
				'start_date' => 'required|date_format:Y-m-d' ,
				'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date' ,
//			'status' => 'required'
			);
			$messages = array (
				'start_date.date_format' => 'enter valid for  2017-09-05' ,
				'start_date.required' => 'start date required for me' ,
//			'status.required' => ' status required' ,
				'end_date.date_format' => 'enter valid for end date   2017-09-05' ,
				'end_date.required' => 'end date required for me' ,
				'start_date.before' => 'start date must be before end date' ,
				'end_date.after' => 'end date must be after start date' ,
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'start_date' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'start_date' ) );
			if ( $errors->first ( 'end_date' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'end_date' ) );


			$start = date ( "Y-m-d" , strtotime ( Input::get ( 'start_date' ) ) );
			$end = date ( "Y-m-d" , strtotime ( Input::get ( 'end_date' ) . "+1 day" ) );

			$user = UserModel::whereBetween ( 'created_at' , [$start , $end] )->get ();
			$supp = SupplierModel::whereBetween ( 'created_at' , [$start , $end] )->get ();
			$orders = OrderModel::whereBetween ( 'created_at' , [$start , $end] )->get ();
			$service = ServicesModel::whereBetween ( 'created_at' , [$start , $end] )->get ();
			$section = SectionModel::whereBetween ( 'created_at' , [$start , $end] )->get ();
//		dd(UserModel::count());

			$all = $this->return_r ( $user , $supp , $orders , $service , $section );

			return $this->responedFound200 ( 'registration between ' . $start . 'and ' . $end , self::success , $all );


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
		public function all (Request $request)
		{
			if ( $request->input ( 'all' ) == true ) {
				$user = UserModel::count ();
				$supp = SupplierModel::count ();
				$services = ServicesModel::count ();
				$sections = SectionModel::count ();
				$orders = OrderModel::count ();
//	    	dd($orders);
				$all = $this->return_for_all ( $user , $supp , $orders , $services , $sections );

				return $this->responedFound200 ( 'all registration' , self::success , $all );

			} else
				return $this->respondWithError ( 'all must be true' , self::fail );
		}

		private function return_for_all ($users , $supp , $orders , $services , $section)
		{
			return [
				'users' => $users ,
				'suppliers' => $supp ,
				'orders' => $orders ,
				'services' => $services ,
				'sections' => $section ,

			];

		}
	}
