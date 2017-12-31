<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;

//	use Novent\Transformers\OrderTrans;

//use Symfony\Component\EventDispatcher\Tests\Service;

	class OrderModel extends Model
	{
		protected $table = 'orders';

		protected $fillable = [
			'service_id' , 'supplier_id' , 'user_id' , 'rate' , 'status' , 'desc' , 'image' , 'is_rated' , 'path'
		];


		public static function getUserById ($userID)
		{

//			dd($arr['adressModel']);
			$userObject = new UserModel();
			$userObject = $userObject->getUserByID ( $userID );
			$addressObject['address'] = $userObject->address ()->get ()->toArray ();
//			dd();
//			dd($userObject);
			$addressUserObject = array_merge ( $userObject->toArray () , $addressObject );

			return $addressUserObject;

		}



		public static function getSupplierByID ($supplierID)
		{

			$supplierObject = new SupplierModel();
			$supplierObject = $supplierObject->getSupplierByID ( $supplierID );


			return $supplierObject->toArray ();


		}

		public static function getOrderBySupplierIdWithStatusNotDelivered ($supplierID)
		{
			$orderList = OrderModel::where ( 'supplier_id' , $supplierID )->where ( 'status' , '<' , '2' )->get ();

			return $orderList;
		}

		public static function getOrderBySupplierIdWithStatusDelivered ($supplierID)
		{
			$orderList = OrderModel::where ( 'supplier_id' , $supplierID )->where ( 'status' , '=' , '2' )->get ();

			return $orderList;
		}


		public static function getOrderByUserIdWithStatusNotDelivered ($userID)
		{
			$orderList = OrderModel::where ( 'user_id' , $userID )->where ( 'status' , '<' , '2' )->get ();

			return $orderList;
		}

		public static function getOrderByUserIdWithStatusDelivered ($userID)
		{
			$orderList = OrderModel::where ( 'user_id' , $userID )->where ( 'status' , '=' , '2' )->get ();

			return $orderList;
		}

		public static function getServiceByID ($serviceID)
		{

			$serviceObject = new ServicesModel();
			$serviceObject = $serviceObject->getServicesById ( $serviceID );


			return $serviceObject->toArray ();


		}

		public static function service ($userID , $supplierID , $serviceID)
		{

//				dd($a['section']);

			$userObject = new UserModel();


			$supplierObject = new SupplierModel();

//			dd();
			$arr['address'] =$userObject->getUserByID ( $userID )->address ()->get ()->toArray ();
			$serviceObject = new ServicesModel();
			$serviceObject = $serviceObject->getServicesById ( $serviceID );

			$sectionObject = new SectionModel();

//				dd($serviceObject);
			return [
				'user' => array_merge ( $userObject->getUserByID ( $userID )->toArray () , $arr ) ,

				'supplier' => $supplierObject->getSupplierByID ( $supplierID )->toArray () ,
				'service' => $serviceObject->getServicesById ( $serviceID )->toArray () ,
//						'section'=>SectionModel::find($aa)->toArray ()
//$ass
				'section' => $sectionObject->getSectionByID ( $serviceObject['section_model_id'] )->toArray ()
			];
		}

		public function getCreatedAt ()
		{
			return $this->created_at;

		}

		public function getUpdatedAt ()
		{
			return $this->updated_at;

		}

		public function getNameEN ()
		{
			return $this->name_en;

		}

		public function getNameAr ()
		{
			return $this->name_ar;

		}

		public function getDesc_En ()
		{
			return $this->desc_en;
		}

		public function getDescAr ()
		{
			return $this->desc_ar;
		}

		public function getImage ()
		{
			return $this->image;
		}

		public function getStatus ()
		{
			return $this->status;
		}

		public function getOrderById ($id)
		{
//			dd('asdasdq1');
//			$sectionObject = SectionModel::where ( 'id' , $id )->where ( 'status' , true )->first ();
			$orderObject = OrderModel::find ( $id );

			return $orderObject;

		}

		public function getOrdersByStatus ($status)
		{
			$listOrders = OrderModel::where ( 'status' , $status )->get ();

			return $listOrders;
		}

		public function setCreatedAt ($value)
		{
			$this->attributes['created_at'] = $value;
		}

		public function setUpdatedAt ($value)
		{
			$this->attributes['updated_at'] = $value;
		}

		private function setNameEn ($value)
		{
			$this->attributes['name_en'] = $value;

		}

		private function setNameAr ($value)
		{
			$this->attributes['name_ar'] = $value;
		}

		private function setDescAr ($value)
		{
			$this->attributes['desc_ar'] = $value;
		}

		private function setDescEn ($value)
		{
			$this->attributes['desc_en'] = $value;
		}

		private function setStatus ($value)
		{
			$this->attributes['status'] = $value;
		}


	}