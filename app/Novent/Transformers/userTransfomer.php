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
			if( !$user['deleted_at'] )
				$del="";
			else
				$del=	date ( 'Y-m-d' , strtotime ( $user['deleted_at'] ) );
			if( !$user['updated_at'] )
				$up="";
			else
				$up=	date ( 'Y-m-d' , strtotime ( $user['updated_at'] ) );

			return [
				'id' => $user['id'] ,
				'name' => $user['name'] ,
				'email' => $user['email'] ,
				'phone' => $user['phone'] ,
				'status' => (boolean)$user['status'] ,
				'created_at' => date ( 'Y-m-d' , strtotime ( $user['created_at'] ) ) ,
				'updated_at' => $up ,
				'deleted_at' => $del,
//				'add'=>$user['address']['country']
//				'active' => (boolean)$user['is_active'] ,
			];

		}

	}