<?php
	/**
	 * Created by PhpStorm.
	 * UserModel: dark-
	 * Date: 9/16/2017
	 * Time: 3:17 PM
	 */
	//namespace Novent\Transfroers;
	namespace Unit\Transformers;
	class Services_suppliersTrans extends Transfomer
	{

		public function transform ($user)
		{
			$supplierTrans= new SupplierTranformD();

			if ( !$user['updated_at'] )
				$up = "";
			else
				$up = date ( 'Y-m-d' , strtotime ( $user['updated_at'] ) );

			if ( !$user['deleted_at'] )
				$del = "";
			else
				$del = date ( 'Y-m-d' , strtotime ( $user['deleted_at'] ) );
//			dd($user);
			return [
				'id' => $user['id'] ,
				'section_id'=>$user['section_model_id'],
				'name_en' => $user['name_en'] ,
				'name_ar' => $user['name_ar'] ,
				'desc_en' => $user['desc_en'] ,
				'desc_ar' => $user['desc_ar'] ,
				'status' => (boolean)$user['status'] ,
				'image' => $user['image'] ,
				'created_at' => date ( 'Y-m-d' , strtotime ( $user['created_at'] ) ) ,
				'updated_at' => $up ,
				'deleted_at' => $del,
				'suppliers'=>$supplierTrans->transformCollection  ($user['suppliers'])


			];

		}

	}