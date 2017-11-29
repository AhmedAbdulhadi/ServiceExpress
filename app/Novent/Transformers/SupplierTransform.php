<?php
	/**
	 * Created by PhpStorm.
	 * User: dark-
	 * Date: 9/16/2017
	 * Time: 3:17 PM
	 */
	//namespace Novent\Transfroers;
	namespace Novent\Transformers;
	class SupplierTransform extends Transfomer
	{

		public function transform ($user)
		{
//			dd($user['created_at']);
			return [
				'supplier_id' => $user['id'],
				'name' => $user['name'],
				'email' => $user['email'],
				'phone' => $user['phone'],
				'longitude' =>$user['longitude'],
				'latitude' =>$user['latitude'],
				'bio' => $user['bio'],
				'exp_year' => $user['exp_year'],
				'status' => (boolean)$user['status'],
				'created_at' =>  date('Y-m-d', strtotime($user['updated_at'])) ,
				'updated_at' => date('Y-m-d', strtotime($user['updated_at'])) ,
				//	'active' => (boolean)$user['is_active'],
			];

		}

	}