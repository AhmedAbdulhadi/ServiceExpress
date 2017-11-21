<?php
	/**
	 * Created by PhpStorm.
	 * User: dark-
	 * Date: 9/16/2017
	 * Time: 3:17 PM
	 */

	//namespace Novent\Transfroers;
	namespace Novent\Transformers;
	class orderTransOneC extends Transfomer
	{
		/**
		 * @var  Novent\Transformers\OrderTrans
		 * @var  Novent\Transformers\OrderTrans
		 */
//		protected $userTrans;
		protected $address_trans;

		public function __construct ( addressTrans $address_trans)
		{
//			$this->userTrans = $userTrans;
			$this->address_trans = $address_trans;
			//$this->middleware('auth.basic', ['only' => 'store']);

		}

		public function transform ($user)
		{
			if ( !$user['desc'] )
				$user['desc'] = "";

			return [
				'id' => $user['id'] ,
				'desc' => $user['desc'] ,
				'path' => $user['path'] ,
				'rate' => $user['rate'] ,
				'status' => $user['status'] ,

				'is_rated' => (boolean)$user['is_rated'] ,
				'delivered_at' => date ( 'Y-m-d' , strtotime ( $user['delivered_at'] ) ) ,
				'created_at' => date ( 'Y-m-d' , strtotime ( $user['created_at'] ) ) ,
				'updated_at' => date ( 'Y-m-d' , strtotime ( $user['updated_at'] ) ) ,



			];

		}

	}