<?php
	/**
	 * Created by PhpStorm.
	 * UserModel: dark-
	 * Date: 9/16/2017
	 * Time: 3:17 PM
	 */

	//namespace Novent\Transfroers;
	namespace Unit\Transformers;
	class orderTransOneC extends Transfomer
	{

//		protected $userTrans;
//		protected $address_trans;

//		public function __construct ( addressTrans $address_trans)
//		{
////			$this->userTrans = $userTrans;
//			$this->address_trans = $address_trans;
//			//$this->middleware('auth.basic', ['only' => 'store']);
//
//		}

		public function transform ($user)
		{
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
				'delivered_at' => $del ,
				'created_at' => date ( 'Y-m-d' , strtotime ( $user['created_at'] ) ) ,
				'updated_at' => $up ,



			];

		}

	}