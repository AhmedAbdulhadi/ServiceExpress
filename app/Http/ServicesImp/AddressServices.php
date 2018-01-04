<?php

	namespace App\Http\Controllers;

	use AddressIntr;
	use Carbon\Carbon;
	use CommonValidation;
	use Illuminate\Http\Request;
	use App\adressModel;
	use App\UserModel;

//	use App\Http\Controllers\UserServices;
//	use App\Http\Controllers\Controller;
//	use Illuminate\Support\Facades\Response;
//	use Novent\Transformers\AddressTransfomer;
	use function Sodium\add;
	use Unit\Transformers\addressTrans;
	use \Validator;
	use Illuminate\Support\Facades\DB;


	class AddressServices implements AddressIntr
	{


		public static function getAllAddress ()
		{
			/* GET ALL ADDRESS*/
			$addressList = adressModel::getAllAddress ();

			$objUserTransformer = new  addressTrans();
			$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

			return $objResponse->returnWithData ( $objUserTransformer->transformCollection ( $addressList->toArray () ) );


		}


		public static function addAddress (Request $request)
		{
			/* CREATE ADDRESS WITH USER ID */
			$requestError = CommonValidation::addressCreateValidation ( $request );
			$userID = $request->input ( 'user_id' );


			$type0 = adressModel::getAddressByUserIdAddressType ( $userID , $request->input ( 'address_type' ) );
			$type1 = adressModel::getAddressByUserIdAddressType ( $userID , $request->input ( 'address_type' ) );
//			$type2 = adressModel::getAddressByUserIdAddressType ($userID,2);


			if ( $type0 !== null and $request->input ( 'address_type' ) == '0' ) {
				$resp = new ResponseDisplay( ResponseMassage::$FAIL_ADDRESS_TYPE_EXISTS_0 , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			} else if ( $type1 !== null and $request->input ( 'address_type' ) == '1' ) {

				$resp = new ResponseDisplay( ResponseMassage::$FAIL_ADDRESS_TYPE_EXISTS_1 , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			} elseif ( $requestError ) {
				return ($requestError);
			} else {
				$tsCurrentDate = Carbon::now ( 'GMT+2' );
				$addressObject = new adressModel();
				$addressObject->longitude = $request->input ( 'longitude' );
				$addressObject->latitude = $request->input ( 'latitude' );
				$addressObject->city = $request->input ( 'city' );
				$addressObject->street = $request->input ( 'street' );
				$addressObject->country = $request->input ( 'country' );
				$addressObject->neighborhood = $request->input ( 'neighborhood' );
				$addressObject->building_number = $request->input ( 'building_number' );
				$addressObject->apartment_number = $request->input ( 'apartment_number' );
				$addressObject->floor = $request->input ( 'floor' );
				$addressObject->address_type = $request->input ( 'address_type' );
				$addressObject->created_at = $tsCurrentDate;
				$addressObject->timestamps = false;
				$addressObject->save ();

				$userObject = new UserModel();
				$userObject = $userObject->getUserByID ( $userID );
				$userObject->address ()->attach ( $addressObject->id , ['address_type' =>
					$request->input ( 'address_type' ) , 'created_at' => $tsCurrentDate] );

				$objUserTransformer = new  addressTrans();
				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Created_en , ResponseStatus::$success , ResponseCode::$HTTP_CREATED );

				return $objResponse->returnWithData ( $objUserTransformer->transform ( $addressObject ) );
			}


		}


		public static function updateAddress (Request $request , $id)
		{
			/* UPDATE ADDRESS  */
			$requestError = CommonValidation::updateAddressValidation ( $request );

			$addressObject = adressModel::getAddressByID ( $id );

			$tsCurrentDate = Carbon::now ( 'GMT+2' );

			if ( !$requestError ) {
				if ( !$addressObject ) {
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				} else {
					$objUserTransformer = new addressTrans();
					$addressObject->update ( $request->all () );
					$addressObject->updated_at = $tsCurrentDate;
					$addressObject->timestamps = false;
					$addressObject->save ();

					$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Update_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $objUserTransformer->transform ( $addressObject ) );
				}
			} else {
				return $requestError;
			}

		}

	}
