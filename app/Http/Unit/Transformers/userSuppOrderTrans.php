<?php
	/**
	 * Created by PhpStorm.
	 * UserModel: dark-
	 * Date: 9/16/2017
	 * Time: 3:17 PM
	 */

	//namespace Novent\Transfroers;
	namespace Unit\Transformers;
	class userSuppOrderTrans extends Transfomer
	{

//		protected $address_trans;



		public function transform ($user)
		{
			$addressTrans = new addressTrans();
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
			if($user['info'])
				$info=$user['info'];
//dd($user->toArray());
//dd($user->toArray());
			return [
				'id' => $user['id'] ,
				'desc' => $user['desc'] ,
				'path' => $user['path'] ,
				'rate' => $user['rate'] ,
				'status' => $user['status'] ,
				'is_rated' => (boolean)$user['is_rated'] ,
				'delivered_at' => $del ,
				'created_at' => date ( 'Y-m-d' , strtotime ( $user['created_at'] ) ) ,
				'updated_at' => $up ,
				'user'=>$user['user'],
				'supplier'=>$user['supplier'],
				'service'=>$user['services']


			];

		}

	}