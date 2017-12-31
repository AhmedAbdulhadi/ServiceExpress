<?php
	/**
	 * Created by PhpStorm.
	 * UserModel: dark-
	 * Date: 9/16/2017
	 * Time: 3:17 PM
	 */

	//namespace Novent\Transfroers;
	namespace Unit\Transformers;
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

//			if ( !$user['address'] )
//				$address= [];
//				else
//					$address="";
			return [
				'id' => $user['id'] ,
				'name' => $user['name'] ,
				'email' => $user['email'] ,
				'phone' => $user['phone'] ,
				'status' => (boolean)$user['status'] ,
				'created_at' => date ( 'Y-m-d' , strtotime ( $user['created_at'] ) ) ,
				'updated_at' => $up ,
				'deleted_at' => $del,
				'address'=>$user['address']
//				'active' => (boolean)$user['is_active'] ,
			];

		}

	}