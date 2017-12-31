<?php

	namespace App\Http\Controllers;

	use App\adressModel;
	use App\SectionModel;
	use App\ServicesModel;
	use App\SupplierModel;
	use App\UserModel;
	use Carbon\Carbon;
//	use Faker\Provider\Image;
	use CommonValidation;
	use Illuminate\Http\Request;
	use Illuminate\Validation\Rule;

	use Illuminate\Support\Facades\Input;

	use OrderIntr;
	use Unit\Transformers\OrderTrans;
	use Unit\Transformers\orderTransOne;
	use Unit\Transformers\orderTransOneC;
	use Unit\Transformers\userSuppOrderTrans;
	use \Validator;
	use Illuminate\Support\Facades\DB;
	use App\OrderModel;

//use Illuminate\Http\Request;

	class OrderServices implements OrderIntr
	{


//done
		public static function createOrder (Request $request)
		{
// create order

			$userID = $request->input ( 'user_id' );
			$supplierID = $request->input ( 'supplier_id' );
			$serviceID = $request->input ( 'service_id' );
//			$description = $request->input ( 'description' );
			$status = $request->input ( 'status' );
			$rate = $request->input ( 'rate' );
			$requestErrorOrder = CommonValidation::createOrderValidation ( $request );
			$tsCurrentDate = Carbon::now ( 'GMT+2' );
			if ( !$requestErrorOrder ) {
				$rated = false;
				if ( $rate )
					$rated = true;

				if ( !$rate )
					$rate = 0;


				$serviceObjectWithStatus = new ServicesModel();
				$serviceObjectWithStatus = $serviceObjectWithStatus->getServicesByIdWithStatus ( $serviceID , true );
//				dd($serviceObjectWithStatus->toArray ());

				if ( !$serviceObjectWithStatus ) {

				} else {
					$data = Input::all ();
					if ( Input::has ( 'image' ) ) {
						$png_url = "/orders-" . time () . ".png";
						$path = base_path ( "images\orders\\" ) . $png_url;
						$data = $data['image'];
//						list( $type , $data ) = explode ( ';' , $data );
						list( , $data ) = explode ( ',' , $data );
						$data = base64_decode ( $data );


						$success = file_put_contents ( $path , $data );
						$path = url ( '/images/orders' . $png_url );
					} else
						$path = "";

					$success = null;

					if ( $success or !$success ) {
						$orderObject = new OrderModel();
						$orderObject->user_id = $userID;
						$orderObject->supplier_id = $supplierID;
						$orderObject->service_id = $serviceID;
						$orderObject->path = $path;
						$orderObject->delivered_at = null;
						$orderObject->status = $status;
						$orderObject->rate = $rate;
						$orderObject->is_rated = $rated;
						$orderObject->created_at = $tsCurrentDate;
						$orderObject->timestamps = false;
						$orderObject->save ();

						/* CREATE RESPONSE OBJECT */
						$objResponse = new ResponseDisplay( ResponseMassage::$Success_Created_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

						return $objResponse->returnWithData ( $orderObject );

					} else {
						$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

						return $objResponse->returnWithOutData ();
					}
				}
			} else {
				return $requestErrorOrder;
			}
		}

//done
		public static function updateOrder (Request $request , $id)
		{
			//update 1 order
			/*
		=================================================================================
			don't forget to change  services_id in db to service_id
		=================================================================================

			*/
			$requestErrorOrder = CommonValidation::updateOrderValidation ( $request );
			if ( !$requestErrorOrder ) {
				$orderObject = new OrderModel();
				$orderObject = $orderObject->getOrderById ( $id );
//			dd($orderObject->toArray ());
				$tsCurrentDate = Carbon::now ( 'GMT+2' );

				$status = $request->input ( 'status' );
				$rate = $request->input ( 'rate' );
				$is_rated = $request->input ( 'is_rated' );

				$image = $request->input ( 'image' );
//dd($orderObject->toArray ());
				if ( !$orderObject ) {
					$responseObject = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $responseObject->returnWithOutData ();
				} else {
					if ( $image !== null ) {
						$data = Input::all ();
						if ( Input::has ( 'image' ) ) {
							$png_url = "/orders-" . time () . ".png";
							$path = base_path ( "images\orders\\" ) . $png_url;
							$data = $data['image'];
//						list( $type , $data ) = explode ( ';' , $data );
							list( , $data ) = explode ( ',' , $data );
							$data = base64_decode ( $data );


							$success = file_put_contents ( $path , $data );
							$path = url ( '/images/orders' . $png_url );
						} else
							$path = "";
					} else {
						$path = $orderObject->path;
					}
//dd($path);
					if ( $orderObject->status !== $status and $status !== null )
						if ( $status == 0 or $status == 1
							or $status == 2 or $status == 3 ) {

							if ( $status == 2 ) {
								$orderObject->status = $status;
								$orderObject->delivered_at = $tsCurrentDate;
							} else {
								$orderObject->status = $status;
								$orderObject->delivered_at = null;
							}
						}

					if ( $orderObject->rate !== $rate and $rate !== null )
						if ( $rate == 0 ) {
							$orderObject->rate = $rate;
							$orderObject->is_rated = 0;
						} else {
							$orderObject->rate = $rate;
							$orderObject->is_rated = 1;
						}


					if ( $orderObject->is_rated !== $is_rated and $is_rated !== null )
						if ( $is_rated == 0 ) {
							//convart rate to 0 if is rated ==0
							$orderObject->rate = 0;
							$orderObject->is_rated = false;
						} else if ( $is_rated == 1 ) {
							//update is rated
							$orderObject->is_rated = true;
						}
//					dd($orderObject->toArray ());
//dd($request->all ());
					$orderObject->update ( $request->except ( 'image' ) );
					$orderObject->path = $path;
//				$orderObject->is_rated;
					$orderObject->updated_at = $tsCurrentDate;
					$orderObject->timestamps = false;
					$orderObject->save ();
//				dd($orderObject->toArray ());
					$objTransformer = new orderTransOneC();
					$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Update_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $objTransformer->transform ( $orderObject ) );

				}
			} else {
				return $requestErrorOrder;
			}
//			$data = OrderModel::find ( $id );


		}

//done
		public static function deleteOrder ($id)
		{
//delete order
			$orderObject = new OrderModel();
			$orderObject = $orderObject->getOrderById ( $id );
			if ( !$orderObject ) {
				{
					$responseObject = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en ,
						ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $responseObject->returnWithOutData ();
				}
			} else {
				$orderObject->delete ();
				$responseObject = new ResponseDisplay( ResponseMassage::$SUCCESS_Deleted_en ,
					ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $responseObject->returnWithOutData ();

			}


		}

//done
		public static function getAllOrder ()
		{
			//get all order
			$ordersList = new OrderModel();
			$ordersList = $ordersList->getOrdersByStatus ( true );

			foreach ($ordersList as $orderObject) {

				$orderObject->order_info = OrderModel::service ( $orderObject->user_id , $orderObject->supplier_id , $orderObject->service_id );
			}

			// to transform order and user supplier and services section  but not wit full object Data
			$objTransformer = new OrderTrans();
			$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

			return $objResponse->returnWithData ( $objTransformer->transformCollection ( $ordersList->toArray () ) );
		}

//done
		public static function getOneOrder ($id)
		{
			//get order by id
			$orderObject = new OrderModel();
			$orderObject = $orderObject->getOrderById ( $id );

			if ( $orderObject ) {
				$orderObject->order_info = OrderModel::service ( $orderObject->user_id , $orderObject->supplier_id , $orderObject->service_id );

				$objTransformer = new OrderTrans();
				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Update_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objTransformer->transform ( $orderObject ) );
			} else {
				$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_Not_Found_Massages_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			}

		}

		public static function getOrderSupplierId (Request $request)
		{
			// get order with supplier_id and active 1 or 0
			$supplierId = $request->input ( 'supplier_id' );
			$active = $request->input ( 'active' );

			$activeList = [];
			$nonActiveList = [];

			if ( $active == 1 ) {
				$orderList = OrderModel::getOrderBySupplierIdWithStatusNotDelivered ( $supplierId );

				foreach ($orderList as $orderObject) {
					$userObject = OrderModel::getUserById ( $orderObject['user_id'] );
					$supplierObject = OrderModel::getSupplierByID ( $supplierId );
					$serviceObject = OrderModel::getServiceByID ( $orderObject['service_id'] );
					$orderObject->user = $userObject;
					$orderObject->supplier = $supplierObject;
					$orderObject->services = $serviceObject;
					$activeList[] = $orderObject;
				}
				if ( $activeList ) {

					$objTransformer = new userSuppOrderTrans();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $objTransformer->transformCollection ( $activeList ) );

				} else if ( !$activeList and $active == 1 ) {
					$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_Not_Found_Massages_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}
			}
			if ( $active == 0 ) {
				$orderList = OrderModel::getOrderBySupplierIdWithStatusDelivered ( $supplierId );

				foreach ($orderList as $orderObject) {

					$userObject = OrderModel::getUserById ( $orderObject['user_id'] );
					$supplierObject = OrderModel::getSupplierByID ( $supplierId );
					$serviceObject = OrderModel::getServiceByID ( $orderObject['service_id'] );
					$orderObject->user = $userObject;
					$orderObject->supplier = $supplierObject;
					$orderObject->services = $serviceObject;
					$nonActiveList[] = $orderObject;
				}
				if ( $nonActiveList ) {
					$objTransformer = new userSuppOrderTrans();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $objTransformer->transformCollection ( $nonActiveList ) );
				} else if ( !$nonActiveList and $active == 0 ) {
					$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_Not_Found_Massages_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}
			}
		}


		public static function getOrderUserId (Request $request)
		{
			// get order with user_id and active 1 or 0
			$userID = $request->input ( 'user_id' );
			$active = $request->input ( 'active' );

			$activeList = [];
			$nonActiveList = [];
			if ( $active == 1 ) {
				$orderList = OrderModel::getOrderByUserIdWithStatusNotDelivered ( $userID );
				foreach ($orderList as $orderObject) {
					$userObject = OrderModel::getUserById ( $userID );

					$supplierObject = OrderModel::getSupplierByID ( $orderObject['supplier_id'] );
					$serviceObject = OrderModel::getServiceByID ( $orderObject['service_id'] );
					$orderObject->user = $userObject;
					$orderObject->supplier = $supplierObject;
					$orderObject->services = $serviceObject;
					$activeList[] = $orderObject;
				}

				if ( $activeList ) {
					$objTransformer = new userSuppOrderTrans();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $objTransformer->transformCollection ( $activeList ) );
				} else if ( !$activeList and $active == 1 ) {
					$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_Not_Found_Massages_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}
			}
			if ( $active == 0 ) {
				$orderList = OrderModel:: getOrderByUserIdWithStatusDelivered ( $userID );
				foreach ($orderList as $orderObject) {

					$userObject = OrderModel::getUserById ( $userID );

					$supplierObject = OrderModel::getSupplierByID ( $orderObject['supplier_id'] );
					$serviceObject = OrderModel::getServiceByID ( $orderObject['service_id'] );
					$orderObject->user = $userObject;
					$orderObject->supplier = $supplierObject;
					$orderObject->services = $serviceObject;
					$nonActiveList[] = $orderObject;
				}
				if ( $nonActiveList ) {
					$objTransformer = new userSuppOrderTrans();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $objTransformer->transformCollection ( $nonActiveList ) );
				} else if ( !$nonActiveList and $active == 0 ) {
					$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_Not_Found_Massages_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}

			}
		}

	}
