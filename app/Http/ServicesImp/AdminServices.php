<?php
	/**
	 * Created by PhpStorm.
	 * UserModel: dark-
	 * Date: 10/24/2017
	 * Time: 4:17 PM
	 */

	namespace App\Http\Controllers;

	use AdminIntr;
	use App\login;
	use App\AdminModel;
	use Carbon\Carbon;
	use CommonValidation;
	use Illuminate\Contracts\Pagination\Paginator;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\Response;


	use Unit\Transformers\AdminTransfom;
	use \Validator;

	class AdminServices implements AdminIntr
	{

		public static function getAllAdmin ()
		{
			/* GET ALL USERS */
			$objTransformUser = new AdminTransfom();
			$listAdmin = new AdminModel();
			$listAdmin = $listAdmin->getAdminByStatus ( true );// 1 assagin to true
			if ( $listAdmin->first () ) {
				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objTransformUser->transformCollection ( $listAdmin->all () ) );
			} else {
				/* RETURN NOT FOUND MESSAGE */
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			}

		}


		public static function getOneAdminById ($id = null)
		{
			/* GET  USER BY ID */
			$objTransformUser = new AdminTransfom();
			$objAdmin = new AdminModel();
			$objAdmin = $objAdmin->getAdminByID ( $id );
			if ( $objAdmin ) {
				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objTransformUser->transform ( $objAdmin ) );
			} else {
				/* RETURN NOT FOUND MESSAGE */
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			}
		}


		public static function createAdmin (Request $request)
		{
			//to create admin

			$requestErrorAdmin = CommonValidation::checkActorModel ( $request );
			if ( !$requestErrorAdmin ) {
				/* CREATE USER MODEL */
				$objAdmin = new AdminModel();
				$objLoginAdmin = new login();
				$objUserTransformer = new AdminTransfom();
				$tsCurrentDate = Carbon::now ( 'GMT+2' );
				$objAdmin->email = $request->input ( 'email' );
				$objAdmin->name = $request->input ( 'name' );
				$objAdmin->password = bcrypt ( $request->input ( 'password' ) );
				$objAdmin->phone = $request->input ( 'phone' );
				$objAdmin->status = 1;
				$objAdmin->role = $request->input ( 'role' );
				$objAdmin->created_at = $tsCurrentDate;
				$objAdmin->timestamps = false;
				$objAdmin->save ();

				/* CREATE LOGIN MODEL */
				$objLoginAdmin->email = $request->input ( 'email' );
				$objLoginAdmin->password = bcrypt ( $request->input ( 'password' ) );
				$objLoginAdmin->timestamps = false;
				$objLoginAdmin->created_at = $tsCurrentDate;
				$objLoginAdmin->status = 1;
				$objLoginAdmin->type = '2';
				$objLoginAdmin->save ();

				/* CREATE RESPONSE OBJECT */
				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Created_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objUserTransformer->transform ( $objAdmin ) );
			} else {
				/* RETURN ERROR MESSAGE */
				return $requestErrorAdmin;
			}

		}

		public static function deleteAdmin ($id)
		{

			/* CHANGE STATUS TO 0 TO DELETED*/

			$adminObject = new AdminModel();
			$adminObject = $adminObject->getAdminByID ( $id );
			$adminLoginObject = new login();
			$adminLoginObject = $adminLoginObject->getUserByEmail ( $adminObject->getEmail () );


			if ( !$adminObject ) {
				/* USER NOT FOUND*/
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			} else {
				/* CHANGE STATUS TO 0 TO DELETED WITH RETURN SUCCESS*/
				$adminObject->status = 0;
				$adminObject->save ();
				$adminLoginObject->status = 0;
				$adminLoginObject->save ();
				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Deleted_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithOutData ();
			}

		}

		public static function updateAdmin (Request $request , $id)
		{

			/* Admin MODEL UPDATE*/

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


			$adminObject = new AdminModel();
			$adminObject = $adminObject->getAdminByID ( $id );
			$tsCurrentDate = Carbon::now ( 'GMT+2' );
			$adminObjectPHone = $adminObject->getAdminByPhoneNum ( $request->input ( 'phone' ) );

			$loginObject = new login();
			$loginObject = $loginObject->getUserByEmail ( $adminObject->getEmail () );
			$adminObjectNotEmail = $loginObject->getUserByEmail ( $request->input ( 'email' ) );

			$requestErrorAdmin = CommonValidation::checkActorModelForUpdatedActor ( $request );

			if ( !$adminObject ) {
				/*  SUPPLIER MODEL NOT EXISTS */
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			} elseif ( !$requestErrorAdmin ) {

				/* UPDATE SUPPLIER MODEL IF EXISTS */

				if ( $request->has ( 'email' ) ) {
					if ( $loginObject->getEmail () == $request->input ( 'email' ) or $adminObjectNotEmail == null ) {
						$adminObject->email = $request->input ( 'email' );
						$loginObject->email = $request->input ( 'email' );

					} else {

						$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Email_Exists_Error_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

						return $objResponse->returnWithOutData ();
					}
				}

				if ( $request->has ( 'phone' ) ) {
					if ( $adminObjectPHone == null or $adminObject->getPhone () == $request->input ( 'phone' ) ) {
						$adminObject->phone = $request->input ( 'phone' );

					} else {
						$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_PhoneNum_Exists_Error_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

						return $objResponse->returnWithOutData ();
					}
				}


				$objUserTransformer = new AdminTransfom();
				$adminObject->update ( $request->all () );
				$adminObject->updated_at = $tsCurrentDate;
				$adminObject->save ();

				$loginObject->update ( [$request->input ( 'email' ) , $request->input ( 'password' ) , $request->input ( 'status' )] );
				$loginObject->updated_at = $tsCurrentDate;
				$loginObject->save ();

				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Update_en , ResponseStatus::$success , ResponseCode::$HTTP_CREATED );

				return $objResponse->returnWithData ( $objUserTransformer->transform ( $adminObject ) );
			} else
				/*RETURN ERROR MASSAGE */
				return $requestErrorAdmin;


		}

		public static function getAdminByPhoneNum (Request $request)
		{
			/* GET ADMIN MODEL BY PHONE NUMBER */
			$requestErrorAdmin = CommonValidation::checkPhoneValidation ( $request );

			$adminObject = new AdminModel();
			$adminObject = $adminObject->getAdminByPhoneNum ( $request->input ( 'phone' ) );


			if ( !$requestErrorAdmin )
				if ( $adminObject ) {
					/* GET USER  MODEL BY PHONE NUMBER*/
					$userTransformObj = new AdminTransfom();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $userTransformObj->transform ( $adminObject ) );
				} else {

					/* RETURN ERROR MASSAGE NOT FOUNDED*/
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}
			else {
				/*RETURN ERROR MASSAGE */
				return $requestErrorAdmin;
			}
		}


		public static function getAdminSingleDate (Request $request)
		{
			/* GET USERS MODEL BY SINGLE DATE*/

			$requestErrorAdmin = CommonValidation::dateValidation ( $request );

			$listAdmin = new AdminModel();
			$listAdmin = $listAdmin->getAdminBySingleDate ( $request->input ( 'date' ) );

			if ( !$requestErrorAdmin ) {
				if ( $listAdmin->first () ) {
					/* GET USERS MODEL BY SINGLE DATE IF EXISTS*/
					$userTransformObj = new AdminTransfom();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $userTransformObj->transformCollection ( $listAdmin->all () ) );
				} else {
					/* RETURN NOT FOUND ERROR*/
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}
			} else {
				/* RETURN ERROR MASSAGE*/
				return $requestErrorAdmin;
			}

		}

		public static function getDateQuery (Request $request)
		{
			/*GET USERS MODEL BY FLIGHT DATE */
			$requestErrorAdmin = CommonValidation::getUsersByFlightDate ( $request );
			$listAdmin = new AdminModel();
			$listAdmin = $listAdmin->getAdminByFlightDate ( $request );

			if ( !$requestErrorAdmin ) {
				if ( $listAdmin->first () ) {
					/*GET USERS MODEL IF EXISTS*/
					$userTransformObj = new AdminTransfom();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $userTransformObj->transformCollection ( $listAdmin->all () ) );
				} else {
					/* RETURN NOT FOUND ERROR*/
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}

			} else {
				/* RETURN ERROR MASSAGE*/
				return $requestErrorAdmin;
			}


		}

		public static function getAdminByEmail (Request $request)
		{

			/* GET USER MODEL BY EMAIL ADDRESS*/

			$requestErrorAdmin = CommonValidation::emailValidation ( $request );
			$adminObject = new AdminModel();
			$adminObject = $adminObject->getAdminByEmailAndPhone ( $request );


			if ( !$requestErrorAdmin ) {
				if ( $adminObject->first () ) {
					/* GET USER MODEL BY EMAIL ADDRESS IF EXISTS*/
					$userTransformObj = new AdminTransfom();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $userTransformObj->transform ( $adminObject->first () ) );
				} else {
					/* RETURN NOT FOUND IF NOT EXISTS */
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}

			} else {
				/*RETURN ERROR MASSAGE*/
				return $requestErrorAdmin;
			}
		}

		public static function getAdminEmailPhoneNum (Request $request)
		{
			/* GET USER MODEL BY PHONE AND EMAIL ADDRESS*/

			$requestErrorAdminEmail = CommonValidation::emailValidation ( $request );
			$requestErrorAdminPhone = CommonValidation::checkPhoneValidation ( $request );

			$adminObject = new AdminModel();
			$adminObject = $adminObject->getAdminByEmailAndPhone ( $request );

			if ( !$requestErrorAdminEmail and !$requestErrorAdminPhone ) {
				if ( $adminObject->first () ) {
					/* GET USER MODEL IF EXISTS*/
					$userTransformObj = new AdminTransfom();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $userTransformObj->transform ( $adminObject->first () ) );
				} elseif ( !$adminObject->first () ) {
					/*RETURN NOT FOUND*/
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}

			} /*RETURN ERROR MASSAGE FOR PHONE OR EMAIL */
			elseif ( $requestErrorAdminPhone ) {
				return $requestErrorAdminPhone;
			} elseif ( $requestErrorAdminEmail ) {
				return $requestErrorAdminEmail;
			}


		}

		public static function getInactiveAdmin (Request $request)
		{

			/* GET INACTIVE USERS MODEL*/
			$listAdmin = new AdminModel();
			$listAdmin = $listAdmin->getInActiveAdmin ( $request );
			if ( $listAdmin->first () ) {
				$userTransformObj = new AdminTransfom();

				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $userTransformObj->transformCollection ( $listAdmin->all () ) );
			} else {
				/* RETURN NOT FOUND MESSAGE */
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			}
		}

	}