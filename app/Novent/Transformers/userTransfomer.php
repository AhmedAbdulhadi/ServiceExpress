<?php
	/**
	 * Created by PhpStorm.
	 * User: dark-
	 * Date: 9/16/2017
	 * Time: 3:17 PM
	 */

	//namespace Novent\Transfroers;
	namespace Novent\Transformers;
	class userTransfomer extends Transfomer
	{

		public function transform ($user)
		{

			return [
				'id' => $user['id'] ,
				'name' => $user['name'] ,
				'email' => $user['email'] ,
				'phone' => $user['phone'] ,
				'status' => (boolean)$user['status'] ,
				'created_at' => date ( 'Y-m-d' , strtotime ( $user['created_at'] ) ) ,
//				'add'=>$user['address']['country']
//				'active' => (boolean)$user['is_active'] ,
			];

		}

	}