<?php
	/**
	 * Created by PhpStorm.
	 * User: dark-
	 * Date: 9/16/2017
	 * Time: 3:17 PM
	 */

	//namespace Novent\Transfroers;
	namespace Novent\Transformers;
	class orderTransOne extends Transfomer
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

				'user' => [
					'id' => $user['user']['id'] ,
					'name' => $user['user']['name'] ,
					'email' => $user['user']['email'] ,
					'phone' => $user['user']['phone'] ,
					'address' =>
						$this->address_trans->transformCollection ( $user['user']['address'] )

				]


				, 'supplier' => [
					'id' => $user['supplier']['id'] ,
					'name' => $user['supplier']['name'] ,
					'email' => $user['supplier']['email'] ,
					'phone' => $user['supplier']['phone'] ,
					'longitude' => $user['supplier']['longitude'] ,
					'latitude' => $user['supplier']['latitude'] ,
				]
				, 'service' => [
					'id' => $user['service']['id'] ,
					'name_en' => $user['service']['name_en'] ,
					'name_ar' => $user['service']['name_ar'] ,
				] ,
				'section' => [
					'id' => $user['section']['id'] ,
					'name_en' => $user['section']['name_en'] ,
					'name_ar' => $user['section']['name_ar'] ,
				]

			];

		}

	}