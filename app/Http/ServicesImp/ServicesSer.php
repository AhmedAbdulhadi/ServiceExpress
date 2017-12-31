<?php
	/**
	 * Created by PhpStorm.
	 * UserModel: dark-
	 * Date: 10/24/2017
	 * Time: 4:17 PM
	 */

	namespace App\Http\Controllers;


	use App\SectionModel;
	use App\ServicesModel;
	use App\SupplierModel;
	use Carbon\Carbon;
	use CommonValidation;
	use Illuminate\Contracts\Pagination\Paginator;
	use Illuminate\Database\QueryException;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;

//	use Novent\Transformers\servicesTransform;
//	use Novent\Transformers\supplier_servicesTrans;
//	use Novent\Transformers\SupplierTransform;
	use ServicesIntr;
	use Unit\Transformers\servicesIsAddedTrans;
	use Unit\Transformers\servicesTransform;
	use Unit\Transformers\supplier_servicesTrans;
	use Validator;


	class ServicesSer implements ServicesIntr
	{

//		public function __construct (servicesTransform $userTrans , SupplierTransform $use , supplier_servicesTrans $supplier_servicesT)
//		{
//			$this->userTrans = $userTrans;
//			$this->use = $use;
//			$this->supplier_servicesT = $supplier_servicesT;
//			$this->middleware ( 'auth:api' );
//
//		}
//


		public static function getAllServices ()
		{
//get all services
			$listServices = new ServicesModel();
			$listServices = $listServices->getServicesByStatus ( true );
			if ( $listServices->first () ) {
				$objTransform = new servicesTransform();
				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objTransform->transformCollection ( $listServices->all () ) );
			} else {
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail ,
					ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			}

//			$section = ServicesModel::with ( 'sections' )->where ( 'status' , '=' , '1' )->get ();
//			$services = SectionModel::with ( 'services' )->where ( 'status' , '=' , '1' )->get ();


		}


		public static function getServicesById ($id = null)
		{
			// get one services
			$serviceObject = new ServicesModel();
			$serviceObject = $serviceObject->getServicesById ( $id );


			if ( !$serviceObject ) {
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail ,
					ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();

			} else {

				$objTransform = new servicesTransform();
				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objTransform->transform ( $serviceObject ) );
			}

//			return $this->responedFound200ForOneUser ( 'Service found' , self::success , $this->userTrans->transform ( $Service ) );

		}

//done

		/**
		 * @param Request $request
		 * @return mixed
		 */
		public static function createServices (Request $request)
		{
			$requestErrorService = CommonValidation::createServiceValidation ( $request );

			$tsCurrentDate = Carbon::now ( "GMT+2" );
			if ( !$requestErrorService ) {


				$sectionObject = new SectionModel();
				$sectionObject = $sectionObject->getSectionByID ( $request->input ( 'section_id' ) );

				if ( $sectionObject ) {


					$image_path = url ( '/icons/404.png' );

					if ( $request->input ( 'image' ) ) //				if (fileExists ( public_path ( "icons\\" .$request->input ( 'icon' ))))
					{
						if ( file_exists ( base_path ( 'icons//' . $request->input ( 'image' ) ) ) ) {

							$image_path = url ( 'icons/' . $request->input ( 'image' ) );
						} else {
							$image_path = url ( '/icons/404.png' );
						}
					} elseif ( !$request->input ( 'image' ) )

						$image_path = url ( '/icons/404.png' );

					$serviceObject = new ServicesModel();
					$serviceObject->name_en = $request->input ( 'name_en' );
					$serviceObject->name_ar = $request->input ( 'name_ar' );
					$serviceObject->desc_en = $request->input ( 'desc_en' );
					$serviceObject->desc_ar = $request->input ( 'desc_ar' );
					$serviceObject->status = 1;
					$serviceObject->image = $image_path;
					$serviceObject->created_at = $tsCurrentDate;
					$serviceObject->timestamps = false;

					$serviceObject->save ();


					$sectionObject->services ()->save ( $serviceObject );
					$objTransformer = new servicesTransform();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Created_en , ResponseStatus::$success , ResponseCode::$HTTP_CREATED );

					return $objResponse->returnWithData ( $objTransformer->transform ( $serviceObject ) );


				} else {
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();

				}

			} else {
				return $requestErrorService;
			}

		}

//done
		public static function updateServices (Request $request , $id)
		{
			$requestErrorService = CommonValidation::updateServicesValidation ( $request );
			$serviceObject = new ServicesModel();
			$serviceObject = $serviceObject->getServicesById ( $id );
			$tsCurrentDate = Carbon::now ( 'GMT+2' );


			if ( !$requestErrorService ) {
				if ( !$serviceObject ) {
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				} else {
					if ( $request->has ( 'image' ) !== $serviceObject->image ) {
						$image_path = url ( '/icons/404.png' );
						if ( $request->input ( 'image' ) ) {
							if ( file_exists ( base_path ( 'icons//' . $request->input ( 'image' ) ) ) ) {

								$image_path = url ( 'icons/' . $request->input ( 'image' ) );
							} else {
								$image_path = url ( '/icons/404.png' );
							}

						} elseif ( !$request->input ( 'image' ) )

							$image_path = $serviceObject->image;

//						DB::table ( 'services' )
//							->where ( 'id' , $id )
//							->update ( ['image' => $image_path , 'updated_at' => $now] );

					}
//					dd($request->input ('name_en'));
					$objTransformer = new servicesTransform();
					$serviceObject->update ( $request->all () );
					$serviceObject->image = $image_path;
					$serviceObject->updated_at = $tsCurrentDate;
					$serviceObject->timestamps = false;
					$serviceObject->save ();

					$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Update_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $objTransformer->transform ( $serviceObject ) );
				}
			} else {
				return $requestErrorService;
			}
		}

//done
		public static function deleteServices ($id)
		{
			// delete services
			$tsCurrentDate = Carbon::now ( 'GMT+2' );
			$serviceObject = new ServicesModel();
			$serviceObject = $serviceObject->getServicesById ( $id );
			if ( !$serviceObject ) {
				{
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}
			} else {
				$serviceObject->status = 0;
				$serviceObject->deleted_at = $tsCurrentDate;
				$serviceObject->timestamps = false;
				$serviceObject->save ();
				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Deleted_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithOutData ();

			}


		}

//done
		public static function getOneServicesBySectionId (Request $request)
		{
			//get services by section id

			$requestErrorServicesSectionID = CommonValidation::sectionIdValidation ( $request );

			$sectionObject = new SectionModel();
			$sectionObject = $sectionObject->getSectionByID ( $request->input ( 'section_id' ) )->services;


			if ( $sectionObject ) {
				$objTransformer = new servicesTransform();

				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objTransformer->transformCollection ( $sectionObject->all () ) );
			} elseif ( !$sectionObject ) {
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			} elseif ( $requestErrorServicesSectionID ) {
				return $requestErrorServicesSectionID;
			}
		}

//done
		public static function getServicesWithSupplierId (Request $request)
		{
//			dd('asdq1');
			// get services with supplier id
			$supplierId = $request->input ( 'supplier_id' );

			$supplierObject = new SupplierModel();
			$supplierObject = $supplierObject->getSupplierByID ( $supplierId );
			$servicesList = $supplierObject->services;
//			return $supplierObject;
//dd($supplierObject->toArray ());
			$objTransformer = new supplier_servicesTrans();
			$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

			return $objResponse->returnWithData ( $objTransformer->transform ( $supplierObject ) );

		}

//done
		public static function getServicesSuppliers (Request $request)
		{
			// get services  with supppliers = true
			if ( $request->input ( 'suppliers' ) == 1 or $request->input ( 'suppliers' ) == 'true' ) {
				$supplierServices = new SupplierModel();
				$supplierServices = $supplierServices->getServicesForAllSupplier ( true );
				$objTransform = new supplier_servicesTrans();


				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objTransform->transformCollection ( $supplierServices->all () ) );

			} else {
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail ,
					ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			}


		}

//done

		/**
		 * @param Request $request
		 * @return mixed
		 */
		public static function getServicesSectionIdSupplierId (Request $request)
		{
			$sectionID = $request->input ( 'section_id' );
			$supplierID = $request->input ( 'supplier_id' );

			$requestError = CommonValidation::getServicesSectionSupplierIdValidation ( $request );
			if ( !$requestError ) {

				$supplierObject = new SupplierModel();
				$supplierObject = $supplierObject->getSupplierServicesInSectionByID ( $supplierID , $sectionID );

				$sectionObject = new SectionModel();
				$sectionObject = $sectionObject->getSectionByID ( $sectionID );
				$serviceSectionList = $sectionObject->services;

//				$activeList = [];
//				$nonActiveList = [];
				if ( $sectionObject['services']->first () ) {
					foreach ($sectionObject['services'] as $key => $serviceSection)
						foreach ($supplierObject as $key2 => $serviceSupp) {
							if ( $serviceSupp['id'] == $serviceSection['id'] ) {

								$serviceSection->is_added = true;
//								$activeList[] = $serviceSupp;
								unset ( $supplierObject[$key2] );
							} else {
								$serviceSection->is_added = false;
							}
						}

//					foreach ($sectionObject['services'] as $section) {
//						$section->is_added = false;
//						$nonActiveList[] = $section;
//					}

//					return($sectionObject['services']);
					$objTransform = new servicesIsAddedTrans();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $objTransform->transformCollection ( $sectionObject['services']->all () ) );
				} else {
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail ,
						ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}
			} else {
				return $requestError;
			}

		}


//done
		public static function assignServices (Request $request)
		{
			//assign services to supplier
			$service_id = $request->input ( 'service_id' );
			$supplier_id = $request->input ( 'supplier_id' );

			$requestErrorAssign = CommonValidation::assignServicesValidation ( $request );

			if ( !$requestErrorAssign ) {

//				$serviceObject = new SectionModel();
//				$serviceObject = $serviceObject->getSectionByID ( $service_id );

				$supplierObject = new SupplierModel();
				$supplierObject = $supplierObject->getSupplierByID ( $supplier_id );


//				$supplierObject->services () ->attach ( $service_id );
//
//				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Created_en , ResponseStatus::$success , ResponseCode::$HTTP_CREATED );
//
//				return $objResponse->returnWithOutData ();


				try {
					$supplierObject->services ()->attach ( $service_id );
//
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Created_en , ResponseStatus::$success , ResponseCode::$HTTP_CREATED );

//
					return $objResponse->returnWithOutData ();

				} catch (QueryException $e) {
					if ( preg_match ( '/Duplicate entry/' , $e->getMessage () ) ) {
						$objResponse = new ResponseDisplay
						( ResponseMassage::$FAILED_Create_Duplicate_Massages_en , ResponseStatus::$fail , ResponseCode::$HTTP_INTERNAL_SERVER_ERROR );

//
						return $objResponse->returnWithOutData ();
					}
				}

			}

			return $requestErrorAssign;
		}

//done
		public static function unAssignServices (Request $request)
		{
			//			unAssign_services
			$service_id = $request->input ( 'service_id' );
			$supplier_id = $request->input ( 'supplier_id' );

			$requestErrorUnAssign = CommonValidation::assignServicesValidation ( $request );

			if ( !$requestErrorUnAssign ) {
				$supplierObject = new SupplierModel();
				$supplierObject = $supplierObject->getSupplierByID ( $supplier_id );

				$supplierObject->services ()
					->wherePivot ( 'supplier_model_id' , $supplier_id )->wherePivot ( 'services_model_id' , $service_id )->detach ();


				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Update_en , ResponseStatus::$success , ResponseCode::$HTTP_CREATED );

//
				return $objResponse->returnWithOutData ();

			} else {
				return $requestErrorUnAssign;
			}
		}
	}



