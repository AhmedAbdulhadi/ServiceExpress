<?php

	namespace App\Http\Controllers;

//	use App\UserModel;
	use App\login;
	use App\ServicesModel;
	use App\supplier_Services;
	use App\SupplierModel;
	use Carbon\Carbon;
	use CommonValidation;
	use Illuminate\Http\Request;
	use SupplierInte;
	use Unit\Transformers\SupplierTransform;
	use Validator;

//	use App\SupplierModel;
	//	use Novent\Transformers\SupplierTransform;

	class SupplierServices extends Controller implements SupplierInte
	{


		public static function getAllSupplier ()
		{
			// to get all SUPPLIERS

			$objTransformUser = new SupplierTransform();
			$listSuppliers = new SupplierModel();
			$listSuppliers = $listSuppliers->getSupplierByStatus ( true );// 1 assagin to true
			if ( $listSuppliers->first () ) {
				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objTransformUser->transformCollection ( $listSuppliers->all () ) );
			} else {
				/* RETURN NOT FOUND MESSAGE */
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			}

		}


		public static function getSupplierById ($id = null)
		{
			/* GET  SUPPLIER BY ID */
			$objTransformUser = new SupplierTransform();
			$objSupplier = new SupplierModel();
			$objSupplier = $objSupplier->getSupplierByID ( $id );
			if ( $objSupplier ) {
				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objTransformUser->transform ( $objSupplier ) );
			} else {
				/* RETURN NOT FOUND MESSAGE */
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			}

		}

		public static function createSupplier (Request $request)
		{
			/* CREATE SUPPLIER  */
//			$type = '1';

			$requestErrorSupplier = CommonValidation::CheckSupplierActorCreate ( $request );

			if ( !$requestErrorSupplier ) {
				/* CREATE SUPPLIER MODEL */
				$objSupplier = new SupplierModel();
				$objLoginSupplier = new login();
				$objUserTransformer = new SupplierTransform();
				$tsCurrentDate = Carbon::now ( 'GMT+2' );
//				dd($tsCurrentDate);
				$objSupplier->email = $request->input ( 'email' );
				$objSupplier->name = $request->input ( 'name' );
				$objSupplier->password = bcrypt ( $request->input ( 'password' ) );
				$objSupplier->phone = $request->input ( 'phone' );
				$objSupplier->status = 1;
				$objSupplier->longitude = $request->input ( 'longitude' );
				$objSupplier->latitude = $request->input ( 'latitude' );
				$objSupplier->created_at = $tsCurrentDate;
				$objSupplier->	timestamps = false;

				$objSupplier->save ();

				/* CREATE LOGIN MODEL */
				$objLoginSupplier->email = $request->input ( 'email' );
				$objLoginSupplier->password = bcrypt ( $request->input ( 'password' ) );
				$objLoginSupplier->created_at = $tsCurrentDate;
				$objLoginSupplier->	timestamps = false;
				$objLoginSupplier->status = 1;
				$objLoginSupplier->type = '1';
				$objLoginSupplier->save ();

				/* CREATE RESPONSE OBJECT */
				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Created_en , ResponseStatus::$success , ResponseCode::$HTTP_CREATED );

				return $objResponse->returnWithData ( $objUserTransformer->transform ( $objSupplier ) );
			} else {
				/* RETURN ERROR MESSAGE */
				return $requestErrorSupplier;
			}
		}

		public static function deleteSupplier ($id)
		{
			/* CHANGE STATUS TO 0 TO DELETED*/

			$userObject = new SupplierModel();
			$userObject = $userObject->getSupplierByID ( $id );

			$userLoginObject = new login();
			$userLoginObject = $userLoginObject->getUserByEmail ( $userObject->getEmail () );
			$tsCurrantDate=Carbon::now ("GTM+2");
			if ( !$userObject ) {
				/* SUPPLIER NOT FOUND*/
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			} else {
				/* CHANGE STATUS TO 0 TO DELETED WITH RETURN SUCCESS*/
				$userObject->status = 0;
				$userObject->deleted_at= $tsCurrantDate;
				$userObject->timestamps = false;
				$userObject->save ();
				$userLoginObject->status = 0;
				$userLoginObject->deleted_at= $tsCurrantDate;
				$userLoginObject->timestamps = false;
				$userLoginObject->save ();
				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Deleted_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithOutData ();
			}


		}

		public static function updateSupplier (Request $request , $id)
		{
					/* SUPPLIER MODEL UPDATE*/

			//check if email in request
			//getSupplier by email to see if exist
			// set supplier email


			//check if phone in request
			//get supplier by phone
			//set supplier


			//set for request payload


			//set updated_at
			//save object
			// return


			$supplierObject = new SupplierModel();
			$supplierObject = $supplierObject->getSupplierByID ( $id );
			$supplierObjectPHone = $supplierObject->getSupplierByPhoneNum ( $request->input ( 'phone' ) );
			$tsCurrentDate = Carbon::now ( 'GMT+2' );

			$loginObject = new login();
			$loginObject = $loginObject->getUserByEmail ( $supplierObject->getEmail () );
			$supplierObjectNotEmail = $loginObject->getUserByEmail ( $request->input ( 'email' ) );


			$requestErrorSupplier = CommonValidation::checkSupplierUpdate ( $request );
			/*
			 "name":"ahmad suplier",
			"phone":"962790851075",
			"password":"12345678",
			"email":"mo@mao.com",
			"longitude":"0",
			"latitude":"0"
			 * */


			if ( !$supplierObject ) {
				/*  SUPPLIER MODEL NOT EXISTS */
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			} elseif ( !$requestErrorSupplier ) {

				/* UPDATE SUPPLIER MODEL IF EXISTS */


				if ( $request->has ( 'email' ) ) {
					if ( $loginObject->getEmail () == $request->input ( 'email' ) or $supplierObjectNotEmail == null ) {
						$supplierObject->email = $request->input ( 'email' );
						$loginObject->email = $request->input ( 'email' );

					} else {

						$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Email_Exists_Error_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

						return $objResponse->returnWithOutData ();
					}
				}

				if ( $request->has ( 'phone' ) ) {
					if ( $supplierObjectPHone == null or $supplierObject->getPhone () == $request->input ( 'phone' ) ) {
						$supplierObject->phone = $request->input ( 'phone' );

					} else {
						$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_PhoneNum_Exists_Error_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

						return $objResponse->returnWithOutData ();
					}
				}


				$objUserTransformer = new SupplierTransform();
				$supplierObject->update ( $request->all () );
				$supplierObject->updated_at = $tsCurrentDate;

				$supplierObject->timestamps = false;
				$supplierObject->save ();

				$loginObject->update ( [$request->input ( 'email' ) , $request->input ( 'password' ) , $request->input ( 'status' )] );
				$loginObject->updated_at = $tsCurrentDate;
				$supplierObject->timestamps = false;
				$loginObject->save ();

				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Update_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objUserTransformer->transform ( $supplierObject ) );
			} else
				/*RETURN ERROR MASSAGE */
				return $requestErrorSupplier;


		}

		public static function getPhoneQuery (Request $request)
		{
			/* GET SUPPLIER MODEL BY PHONE NUMBER */
			$requestErrorSupplier = \CommonValidation::checkPhoneValidation ( $request );

			$supplierObject = new SupplierModel();
			$supplierObject = $supplierObject->getSupplierByPhoneNum ( $request->input ( 'phone' ) );


			if ( !$requestErrorSupplier )
				if ( $supplierObject ) {
					/* GET SUPPLIER  MODEL BY PHONE NUMBER*/
					$userTransformObj = new SupplierTransform();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $userTransformObj->transform ( $supplierObject ) );
				} else {

					/* RETURN ERROR MASSAGE NOT FOUNDED*/
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}
			else {
				/*RETURN ERROR MASSAGE */
				return $requestErrorSupplier;
			}
		}

		public static function getSuppliersSingleDate (Request $request)
		{

			/* GET SUPPLIER MODEL BY SINGLE DATE*/

			$requestErrorSupplier = \CommonValidation::dateValidation ( $request );

			$listSupplier = new SupplierModel();
			$listSupplier = $listSupplier->getSupplierBySingleDate ( $request->input ( 'date' ) );

			if ( !$requestErrorSupplier ) {
				if ( $listSupplier->first () ) {
					/* GET SUPPLIER MODEL BY SINGLE DATE IF EXISTS*/
					$userTransformObj = new SupplierTransform();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $userTransformObj->transformCollection ( $listSupplier->all () ) );
				} else {
					/* RETURN NOT FOUND ERROR*/
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}
			} else {
				/* RETURN ERROR MASSAGE*/
				return $requestErrorSupplier;
			}
		}

		public static function getDateQuery (Request $request)
		{/*GET SUPPLIER MODEL BY FLIGHT DATE */
			$requestErrorSupplier = CommonValidation::getUsersByFlightDate ( $request );
			$listSupplier = new SupplierModel();
			$listSupplier = $listSupplier->getSupplierByFlightDate ( $request );

			if ( !$requestErrorSupplier ) {
				if ( $listSupplier->first () ) {
					/*GET SUPPLIER MODEL IF EXISTS*/
					$userTransformObj = new SupplierTransform();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $userTransformObj->transformCollection ( $listSupplier->all () ) );
				} else {
					/* RETURN NOT FOUND ERROR*/
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}

			} else {
				/* RETURN ERROR MASSAGE*/
				return $requestErrorSupplier;
			}

		}

		public static function getSupplierByEmail (Request $request)
		{
			/* GET SUPPLIER MODEL BY EMAIL ADDRESS*/

			$requestErrorSupplier = \CommonValidation::emailValidation ( $request );
			$supplierObject = new SupplierModel();
			$supplierObject = $supplierObject->getSupplierByEmail  ( $request );


			if ( !$requestErrorSupplier ) {
				if ( $supplierObject->first () ) {
					/* GET SUPPLIER MODEL BY EMAIL ADDRESS IF EXISTS*/
					$userTransformObj = new SupplierTransform();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $userTransformObj->transform ( $supplierObject->first () ) );
				} else {
					/* RETURN NOT FOUND IF NOT EXISTS */
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}

			} else {
				/*RETURN ERROR MASSAGE*/
				return $requestErrorSupplier;
			}
		}

		public static function getSupplierEmailPhoneNum (Request $request)
		{

			/* GET SUPPLIER MODEL BY PHONE AND EMAIL ADDRESS*/

			$requestErrorSupplierEmail = \CommonValidation::emailValidation ( $request );
			$requestErrorSupplierPhone = \CommonValidation::checkPhoneValidation ( $request );

			$supplierObject = new SupplierModel();
			$supplierObject = $supplierObject->getSupplierByEmailAndPhone  ( $request );

			if ( !$requestErrorSupplierEmail and !$requestErrorSupplierPhone ) {
				if ( $supplierObject->first () ) {
					/* GET SUPPLIER MODEL IF EXISTS*/
					$userTransformObj = new SupplierTransform();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $userTransformObj->transform ( $supplierObject->first () ) );
				} elseif ( !$supplierObject->first () ) {
					/*RETURN NOT FOUND*/
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}

			} /*RETURN ERROR MASSAGE FOR PHONE OR EMAIL */
			elseif ( $requestErrorSupplierPhone ) {
				return $requestErrorSupplierPhone;
			} elseif ( $requestErrorSupplierEmail ) {
				return $requestErrorSupplierEmail;
			}


		}

		public static function getInactiveSuppliers (Request $request)
		{
			/* GET INACTIVE SUPPLIER MODEL*/
			$listSupplier = new SupplierModel();
			$listSupplier = $listSupplier->getInActiveSupplier  ( $request );
			if ( $listSupplier->first () ) {
				$userTransformObj = new SupplierTransform();

				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $userTransformObj->transformCollection ( $listSupplier->all () ) );
			} else {
				/* RETURN NOT FOUND MESSAGE */
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			}
		}

		public static function getSupplierByServiceId (Request $request)
		{
			// to get services with supplier info
			$serviceID = $request->input ( 'service_id' );
			if ( $serviceID ) {
//check it
				$serviceList = ServicesModel::with ( 'suppliers' )
					->where ( 'id' , $serviceID )->where ( 'status' , 1 )->get ();

			}
			else
			{

			}

		}

		private function return_r ($supplierObject , $token)
		{

			//to spacifay and get the needed result
			//to spacifay and get the needed result
			//$x for user $y for token
			return [
				'supplier_id' => $supplierObject ,
				'token' => $token['token']
			];

		}


	}