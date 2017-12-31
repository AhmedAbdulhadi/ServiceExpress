<?php
	/**
	 * Created by PhpStorm.
	 * UserModel: dark-
	 * Date: 9/16/2017
	 * Time: 3:17 PM
	 */

	//namespace Novent\Transfroers;
	namespace Unit\Transformers;
	class OrderTrans extends Transfomer
	{
		/**
		 * @var  Novent\Transformers\OrderTrans
		 * @var  Novent\Transformers\OrderTrans
		 */
		protected $userTrans;
		protected $address_trans;



		public function transform ($user)
		{
//			dd($user);
			$userTranstorm= new userTransfomer();
			$addressTransform = new addressTrans();
			if ( !$user['desc'] )
				$user['desc'] = "";
			if( !$user['delivered_at'] )
				$del="";
			else
			$del=	date ( 'Y-m-d' , strtotime ( $user['delivered_at'] ) );
			if( !$user['updated_at'] )
				$up="";
			else
				$up=	date ( 'Y-m-d' , strtotime ( $user['updated_at'] ) );

			return [
				'id' => $user['id'] ,
				'desc' => $user['desc'] ,
				'path' => $user['path'] ,
				'rate' => $user['rate'] ,
				'status' => $user['status'] ,
				'is_rated' => (boolean)$user['is_rated'] ,
				'delivered_at' =>$del ,
				'created_at' => date ( 'Y-m-d' , strtotime ( $user['created_at'] ) ) ,
				'updated_at' =>$up ,

				'user' => [
					'id' => $user['order_info']['user']['id'] ,
					'name' => $user['order_info']['user']['name'] ,
					'email' => $user['order_info']['user']['email'] ,
					'phone' => $user['order_info']['user']['phone'] ,
					'address' =>
						$addressTransform->transformCollection ( $user['order_info']['user']['address'] )

				] , 'supplier' => [
					'id' => $user['order_info']['supplier']['id'] ,
					'name' => $user['order_info']['supplier']['name'] ,
					'email' => $user['order_info']['supplier']['email'] ,
					'phone' => $user['order_info']['supplier']['phone'] ,
					'longitude' => $user['order_info']['supplier']['longitude'] ,
					'latitude' => $user['order_info']['supplier']['latitude'] ,
				]
				, 'service' => [
					'id' => $user['order_info']['service']['id'] ,
					'name_en' => $user['order_info']['service']['name_en'] ,
					'name_ar' => $user['order_info']['service']['name_ar'] ,
				] ,
				'section' => [
					'id' => $user['order_info']['section']['id'] ,
					'name_en' => $user['order_info']['section']['name_en'] ,
					'name_ar' => $user['order_info']['section']['name_ar'] ,
				]

			];

		}

	}