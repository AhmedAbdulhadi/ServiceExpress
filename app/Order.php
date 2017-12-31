<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;
	use Novent\Transformers\OrderTrans;

//use Symfony\Component\EventDispatcher\Tests\Service;

	class Order extends Model
	{


		protected $table = 'orders';
//    protected $hidden=['order_info'];

//[User::find($user_id)->toArray ().'address'=>User::find($user_id)->address ()->get()->toArray ()],

		public function service ($user_id , $supp_id , $serv)
		{
			$sections = section_services::all ()->where ( 'services_id' , $serv )->toArray ();
//				dd($ass);$sec

			foreach ($sections as $section) {
				$section['section'] = Section::find
				( $section['section_id'] )->toArray ();
			}
//				dd($a['section']);

			$arr['address'] = User::find ( $user_id )->address ()->get ()->toArray ();

			return [
				'user' => array_merge
				( User::find ( $user_id )->toArray () , $arr ) ,

				'supplier' => Supplier::find ( $supp_id )->toArray () ,
				'service' => Services::find ( $serv )->toArray () ,
//						'section'=>Section::find($aa)->toArray ()
//$ass
				'section' => $section['section']
			];
		}

//		public static getOrdersBySupplierId($supplier_id, $status){


		public static function get_user_id ($user_id)
		{

			$arr['address'] = User::find ( $user_id )->address ()->get ()->toArray ();
//			dd($arr['address']);
			$user = User::find ( $user_id )->toArray ();
			$arr3 = array_merge ($user,$arr);

			return $arr3;

		}

		public static function get_supplier_id ($supp_id)
		{

			$supplier = Supplier::find ( $supp_id )->toArray ();


			return $supplier;





		}
		public static function get_service_id ($service_id)
		{

			$services = Services::find ($service_id )->toArray ();
//				dd($services);

			return $services;

		}

		public  static function get_section_id($service_id)
		{

			$sections = section_services::all ()->where ( 'services_id' , $service_id )->toArray ();
			foreach ($sections as $section) {
				$section['section'] = Section::find
				( $section['section_id'] )->toArray ();
			}

				return $section['section'];
		}



	}