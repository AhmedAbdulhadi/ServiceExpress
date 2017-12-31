<?php
	/**
	 * Created by PhpStorm.
	 * User: dark-
	 * Date: 9/16/2017
	 * Time: 3:17 PM
	 */
	//namespace Novent\Transfroers;
	namespace Unit\Transformers;
	class sectionTransform extends Transfomer
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
				'id' => $user['id'],
				'name_en' => $user['name_en'],
				'name_ar' => $user['name_ar'],
				'desc_en' => $user['desc_en'],
				'desc_ar' => $user['desc_ar'],
				'image'=>$user['image'],
				'status' => (boolean)$user['status'],
				'admin_id'=>$user['admin_model_id'],
				'created_at' =>  date('Y-m-d', strtotime($user['created_at'])) ,
				'updated_at' =>  $up ,
				'deleted_at' =>  $del,

			];

		}

	}