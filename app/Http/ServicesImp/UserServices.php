<?php
	/**
	 * Created by PhpStorm.
	 * UserModel: dark-
	 * Date: 9/17/2017
	 * Time: 10:28 AM
	 */

	namespace App\Http\Controllers;

//	use App\Http\Controllers\;
//	use App\Http\Unit\ResponseMassage;
	use App\login;
	use App\UserModel;
	use Carbon\Carbon;
	use CommonValidation;
	use Illuminate\Http\Request;
	use Unit\Transformers\userTransfomer;
	use Validator;

	//	use \CommonValidation;
//	use Illuminate\Contracts\Pagination\Paginator;

//	use Novent\Transformers\AddressTransfomer;
//	use Novent\Transformers\userAddress;
//	use Novent\Transformers\userTransfomer;
	//	use Illuminate\Support\Facades\Auth;


	class UserServices implements \UsersInte
	{

//		protected $statusCode = 400;


		public static function getAllUser ()
		{
			/* GET ALL USERS */
			$objTransformUser = new userTransfomer();
			$listUsers = new UserModel();
			$listUsers = $listUsers->getUsersByStatus ( true );// 1 assagin to true
			if ( $listUsers->first () ) {
				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objTransformUser->transformCollection ( $listUsers->all () ) );
			} else {
				/* RETURN NOT FOUND MESSAGE */
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			}
		}

		public static function getUserById ($id = null)
		{
			/* GET  USER BY ID */
			$objTransformUser = new userTransfomer();
			$objUser = new UserModel();
			$objUser = $objUser->getUserByID ( $id );
//			dd($objUser->toArray ());
			if ( $objUser ) {
				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

//dd($objUser->toArray ());
				return $objResponse->returnWithData ( $objTransformUser->transform ( $objUser ) );
			} else {
				/* RETURN NOT FOUND MESSAGE */
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			}
		}


		public static function createUser (Request $request)
		{
//			$type = '0';
			$requestErrorUser = CommonValidation::checkActorModel ( $request );
			if ( !$requestErrorUser ) {
				/* CREATE USER MODEL */

				$objUser = new UserModel();
				$objLoginUser = new login();
				$objUserTransformer = new userTransfomer();

				$tsCurrentDate = Carbon::now ( 'GMT+2' );
				$objUser->email = $request->input ( 'email' );
				$objUser->name = $request->input ( 'name' );
				$objUser->password = bcrypt ( $request->input ( 'password' ) );
				$objUser->phone = $request->input ( 'phone' );
				$objUser->status = 1;
				$objUser->created_at = $tsCurrentDate;
				$objUser->timestamps = false;
//				$objUser->updated_at = null;
				$objUser->save ();

				/* CREATE LOGIN MODEL */
				$objLoginUser->email = $request->input ( 'email' );
				$objLoginUser->password = bcrypt ( $request->input ( 'password' ) );
				$objLoginUser->created_at = $tsCurrentDate;
//				$objLoginUser->updated_at = null;
				$objLoginUser->timestamps = false;
				$objLoginUser->status = 1;
				$objLoginUser->type = '0';
				$objLoginUser->save ();

				/* CREATE RESPONSE OBJECT */
				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Created_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objUserTransformer->transform ( $objUser ) );
			} else {
				/* RETURN ERROR MESSAGE */
				return $requestErrorUser;
			}
		}


		public static function deleteUser ($id)
		{
			/* CHANGE STATUS TO 0 TO DELETED*/

			$userObject = new UserModel();
			$userObject = $userObject->getUserByID ( $id );
			$userLoginObject = new login();
			$userLoginObject = $userLoginObject->getUserByEmail ( $userObject->getEmail () );
			$tsCurrantDate = Carbon::now ( "GMT+2" );

			if ( !$userObject ) {
				/* USER NOT FOUND*/
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			} else {
				/* CHANGE STATUS TO 0 TO DELETED WITH RETURN SUCCESS*/
				$userObject->status = 0;
				$userObject->deleted_at = $tsCurrantDate;
				$userObject->timestamps = false;
				$userObject->save ();

				$userLoginObject->status = 0;
				$userLoginObject->deleted_at = $tsCurrantDate;
				$userLoginObject->timestamps = false;
				$userLoginObject->save ();
				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Deleted_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithOutData ();
			}


		}

		public static function updateUser (Request $request , $id)
		{
			/* UPDATE USER MODEL*/
			$requestErrorUser = CommonValidation::checkActorModelForUpdatedActor ( $request );
//dd('asduser');
			$userObject = new UserModel();
			$userObject = $userObject->getUserByID ( $id );
			$userObjectPHone = $userObject->getUserByPhoneNum
			( $request->input ( 'phone' ) );
//dd($userObject->toArray ());
			$loginObject = new login();
			$loginObject = $loginObject->getUserByEmail ( $userObject->getEmail () );
			$userObjectNotEmail = $loginObject->getUserByEmail ( $request->input ( 'email' ) );


			$tsCurrentDate = Carbon::now ( 'GMT+2' );

			if ( !$userObject ) {
				/*  USER MODEL NOT EXISTS */
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			} elseif ( !$requestErrorUser ) {

				if ( $request->has ( 'email' ) ) {
					if ( $loginObject->getEmail () == $request->input ( 'email' ) or $userObjectNotEmail == null ) {
						$userObject->email = $request->input ( 'email' );
						$loginObject->email = $request->input ( 'email' );

					} else {

						$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Email_Exists_Error_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

						return $objResponse->returnWithOutData ();
					}
				}

				if ( $request->has ( 'phone' ) ) {
					if ( $userObjectPHone == null or $userObject->getPhone () == $request->input ( 'phone' ) ) {
						$userObject->phone = $request->input ( 'phone' );

					} else {
						$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_PhoneNum_Exists_Error_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

						return $objResponse->returnWithOutData ();
					}
				}
				$objUserTransformer = new userTransfomer();
				$userObject->update ( $request->all () );
				$userObject->updated_at = $tsCurrentDate;
				$userObject->timestamps = false;
				$userObject->save ();

				$loginObject->update ( [$request->input ( 'email' ) , $request->input ( 'password' ) , $request->input ( 'status' )] );
				$loginObject->updated_at = $tsCurrentDate;
				$loginObject->save ();

				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Update_en , ResponseStatus::$success , ResponseCode::$HTTP_CREATED );

				return $objResponse->returnWithData ( $objUserTransformer->transform ( $userObject ) );
			} else
				/*RETURN ERROR MASSAGE */
				return $requestErrorUser;

		}


		public static function getPhoneQuery (Request $request)
		{
			/* GET USER MODEL BY PHONE NUMBER */
			$requestErrorUser = \CommonValidation::checkPhoneValidation ( $request );

			$userObject = new UserModel();
			$userObject = $userObject->getUserByPhoneNum ( $request->input ( 'phone' ) );


			if ( !$requestErrorUser )
				if ( $userObject ) {
					/* GET USER  MODEL BY PHONE NUMBER*/
					$userTransformObj = new userTransfomer();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $userTransformObj->transform ( $userObject ) );
				} else {

					/* RETURN ERROR MASSAGE NOT FOUNDED*/
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}
			else {
				/*RETURN ERROR MASSAGE */
				return $requestErrorUser;
			}
		}


		public static function getUserSingleDate (Request $request)
		{
			/* GET USERS MODEL BY SINGLE DATE*/

			$requestErrorUser = \CommonValidation::dateValidation ( $request );

			$listUsers = new UserModel();
			$listUsers = $listUsers->getUserBySingleDate ( $request->input ( 'date' ) );

			if ( !$requestErrorUser ) {
				if ( $listUsers->first () ) {
					/* GET USERS MODEL BY SINGLE DATE IF EXISTS*/
					$userTransformObj = new userTransfomer();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $userTransformObj->transformCollection ( $listUsers->all () ) );
				} else {
					/* RETURN NOT FOUND ERROR*/
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}
			} else {
				/* RETURN ERROR MASSAGE*/
				return $requestErrorUser;
			}

		}

		public static function getDateQuery (Request $request)
		{
			/*GET USERS MODEL BY FLIGHT DATE */
			$requestErrorUser = \CommonValidation::getUsersByFlightDate ( $request );
			$listUsers = new UserModel();
			$listUsers = $listUsers->getUsersByFlightDate ( $request );

			if ( !$requestErrorUser ) {
				if ( $listUsers->first () ) {
					/*GET USERS MODEL IF EXISTS*/
					$userTransformObj = new userTransfomer();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $userTransformObj->transformCollection ( $listUsers->all () ) );
				} else {
					/* RETURN NOT FOUND ERROR*/
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}

			} else {
				/* RETURN ERROR MASSAGE*/
				return $requestErrorUser;
			}

		}

		public static function getUserByEmail (Request $request)
		{
			/* GET USER MODEL BY EMAIL ADDRESS*/

			$requestErrorUser = \CommonValidation::emailValidation ( $request );
			$userObject = new UserModel();
			$userObject = $userObject->getUserByEmail ( $request );


			if ( !$requestErrorUser ) {
				if ( $userObject->first () ) {
					/* GET USER MODEL BY EMAIL ADDRESS IF EXISTS*/
					$userTransformObj = new userTransfomer();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $userTransformObj->transform ( $userObject->first () ) );
				} else {
					/* RETURN NOT FOUND IF NOT EXISTS */
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}

			} else {
				/*RETURN ERROR MASSAGE*/
				return $requestErrorUser;
			}
		}

		public static function getUserEmailPhoneNum (Request $request)
		{
			/* GET USER MODEL BY PHONE AND EMAIL ADDRESS*/

			$requestErrorUserEmail = \CommonValidation::emailValidation ( $request );
			$requestErrorUserPhone = \CommonValidation::checkPhoneValidation ( $request );

			$userObject = new UserModel();
			$userObject = $userObject->getUserByEmailAndPhone ( $request );

			if ( !$requestErrorUserEmail and !$requestErrorUserPhone ) {
				if ( $userObject->first () ) {
					/* GET USER MODEL IF EXISTS*/
					$userTransformObj = new userTransfomer();
					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $userTransformObj->transform ( $userObject->first () ) );
				} elseif ( !$userObject->first () ) {
					/*RETURN NOT FOUND*/
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}

			} /*RETURN ERROR MASSAGE FOR PHONE OR EMAIL */
			elseif ( $requestErrorUserPhone ) {
				return $requestErrorUserPhone;
			} elseif ( $requestErrorUserEmail ) {
				return $requestErrorUserEmail;
			}


		}

		public static function getInactiveUsers (Request $request)
		{
			/* GET INACTIVE USERS MODEL*/
			$listUsers = new UserModel();
			$listUsers = $listUsers->getInActiveUsers ( $request );
			if ( $listUsers->first () ) {
				$userTransformObj = new userTransfomer();

				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $userTransformObj->transformCollection ( $listUsers->all () ) );
			} else {
				/* RETURN NOT FOUND MESSAGE */
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			}
		}


	}