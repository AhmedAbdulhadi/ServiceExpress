<?php
	/**
	 * Created by PhpStorm.
	 * UserModel: dark-
	 * Date: 9/16/2017
	 * Time: 3:17 PM
	 */
	//namespace Novent\Transfroers;
	namespace Unit\Transformers;
	class SectionServicesTrans extends Transfomer
	{

		public function transform ($user)
		{
			$serviceTrans= new servicesTransform();
			return [
				'id' => $user['id'],
				'admin_id' => $user['admin_model_id'],
				'name_en' => $user['name_en'],
				'name_ar' => $user['name_ar'],
				'desc_en' => $user['desc_en'],
				'desc_ar' => $user['desc_ar'],
				'image' => $user['image'],
				'status' => (boolean)$user['status'],
				'created_at' =>  date('Y-m-d', strtotime($user['created_at'])) ,
				'services'=>$serviceTrans->transformCollection  ($user['services']->all())

			];

		}

	}