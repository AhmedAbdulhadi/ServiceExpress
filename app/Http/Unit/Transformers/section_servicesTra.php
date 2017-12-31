<?php
	/**
	 * Created by PhpStorm.
	 * UserModel: dark-
	 * Date: 9/16/2017
	 * Time: 3:17 PM
	 */
	//namespace Novent\Transfroers;
	namespace Unit\Transformers;
	class section_servicesTra extends Transfomer
	{

		public function transform ($user)
		{

			return [
				'id' => $user['id'],

				'name_en' => $user['name_en'],
				'name_ar' => $user['name_ar'],
				'desc_en' => $user['desc_en'],
				'desc_ar' => $user['desc_ar'],


			];

		}

	}