<?php
//	namespace App\Http\Controllers;

	use App\Http\Controllers\ResponseCode;
	use App\Http\Controllers\ResponseStatus;
	use Illuminate\Http\Request;

	use App\Http\Controllers\ResponseMassage;
	use App\Http\Controllers\ResponseDisplay;

	class CommonValidation extends \Validator
	{

		public static function checkActorModel (Request $request)
		{
			$rules = [
				'name' => 'required|regex:/^(?!.*\d)[a-z\p{Arabic}\s]+$/iu|min:3|max:30' ,
				'email' => 'required|email|unique:users|unique:suppliers|unique:admins' ,
				'phone' => 'required|phone:JO|unique:users|unique:suppliers' ,
				'password' => 'required|min:8|max:30'
			];
			$massages = [
				'name.regex' => ResponseMassage::$FAIL_Name_Format_en ,
				'name.required' => ResponseMassage::$FAIL_Name_Required_en ,
				'name.min' => ResponseMassage::$FAIL_Name_Format_min_ken_en ,
				'name.max' => ResponseMassage::$FAIL_Name_Format_max_len_en ,

				'email.required' => ResponseMassage::$FAIL_Email_Required_en ,
				'email.email' => ResponseMassage::$FAIL_Email_Format_Error_en ,
				'email.unique' => ResponseMassage::$FAIL_Email_Exists_Error_en ,

				'phone.required' => ResponseMassage::$FAIL_PhoneNum_Required_en ,
				'phone.unique' => ResponseMassage::$FAIL_PhoneNum_Exists_Error_en ,
				'phone.phone:JO' => ResponseMassage::$FAIL_Phone_Format_country_code_Error_en ,
				'phone.phone' => ResponseMassage::$FAIL_PhoneNum_Format_Error_en ,

				'password.required' => ResponseMassage::$FAIL_Password_Required_en ,
				'password.min' => ResponseMassage::$FAIL_Password_min_len_en ,
				'password.max' => ResponseMassage::$FAIL_Password_max_len_en ,

			];


			$validator = Validator::make ( $request->all () , $rules , $massages );
			$errors = $validator->errors ();
			if ( $validator->fails () )

				if ( $errors->first ( 'name' ) ) {

					$resp = new ResponseDisplay( $errors->first ( 'name' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();

				}
			if ( $errors->first ( 'email' ) ) {

				$resp = new ResponseDisplay( $errors->first ( 'email' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'phone' ) ) {

				$resp = new ResponseDisplay( $errors->first ( 'phone' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}

			if ( $errors->first ( 'password' ) ) {

				$resp = new ResponseDisplay( $errors->first ( 'password' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
		}

		public static function checkActorModelForUpdatedActor (Request $request)
		{
			$rules = [
				'name' => 'regex:/^(?!.*\d)[a-z\p{Arabic}\s]+$/iu|min:3|max:30' ,
				'email' => 'email' ,
				'phone' => 'phone:JO' ,
				'password' => 'min:8|max:30'
			];
			$massages = [
				'name.regex' => ResponseMassage::$FAIL_Name_Format_en ,
//				'name.required' => ResponseMassage::$FAIL_Name_Required_en ,
				'name.min' => ResponseMassage::$FAIL_Name_Format_min_ken_en ,
				'name.max' => ResponseMassage::$FAIL_Name_Format_max_len_en ,

//				'email.required' => ResponseMassage::$FAIL_Email_Required_en,
				'email.email' => ResponseMassage::$FAIL_Email_Format_Error_en ,
				'email.unique' => ResponseMassage::$FAIL_Email_Exists_Error_en ,

//				'phone.required' => ResponseMassage::$FAIL_PhoneNum_Required_en,
				'phone.unique' => ResponseMassage::$FAIL_PhoneNum_Exists_Error_en ,
				'phone.phone:JO' => ResponseMassage::$FAIL_Phone_Format_country_code_Error_en ,
				'phone.phone' => ResponseMassage::$FAIL_PhoneNum_Format_Error_en ,

//				'password.required' => ResponseMassage::$FAIL_Password_Required_en ,
				'password.min' => ResponseMassage::$FAIL_Password_min_len_en ,
				'password.max' => ResponseMassage::$FAIL_Password_max_len_en ,

			];


			$validator = Validator::make ( $request->all () , $rules , $massages );
			$errors = $validator->errors ();
			if ( $validator->fails () )

				if ( $errors->first ( 'name' ) ) {

					$resp = new ResponseDisplay( $errors->first ( 'name' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();

				}
			if ( $errors->first ( 'email' ) ) {

				$resp = new ResponseDisplay( $errors->first ( 'email' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'phone' ) ) {

				$resp = new ResponseDisplay( $errors->first ( 'phone' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}

			if ( $errors->first ( 'password' ) ) {

				$resp = new ResponseDisplay( $errors->first ( 'password' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
		}

		public static function checkPhoneValidation (Request $request)
		{
			$rules = array (

				'phone' => 'required|phone:JO' ,

			);
			$messages = array (
				'phone.phone:JO' => ResponseMassage::$FAIL_Phone_Format_country_code_Error_en ,
				'phone.phone' => ResponseMassage::$FAIL_PhoneNum_Format_Error_en ,
				'phone.required' => ResponseMassage::$FAIL_PhoneNum_Required_en ,
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'phone' ) ) {
					$resp = new ResponseDisplay( $errors->first ( 'phone' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();
				}
			if ( $errors->first ( 'phone' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'phone' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
		}

		public static function dateValidation (Request $request)
		{

			$rules = array (
				'date' => 'required|date_format:Y-m-d' ,

			);
			$messages = array (
				'date.date_format' => ResponseMassage::$FAILED_Date_Format_Massages_en ,
				'date.required' => ResponseMassage::$FAILED_Date_Required_Massages_en ,

			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'date' ) ) {
//					dd('asd');
					$resp = new ResponseDisplay( $errors->first ( 'date' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

//					dd( $resp->returnWithOutData ());
					return $resp->returnWithOutData ();
				}
			if ( $errors->first ( 'date' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'date' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}

		}


		public static function getUsersByFlightDate (Request $request)
		{
			$rules = array (
				'start_date' => 'required|date_format:Y-m-d' ,
				'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date' ,
				'status' => 'required'
			);
			$messages = array (
				'start_date.date_format' => ResponseMassage::$FAILED_Date_Start_Format_Massages_en ,
				'start_date.required' => ResponseMassage::$FAILED_Date_Start_Required_Massages_en ,
				'status.required' => ResponseMassage::$FAILED_Status_Required_Massages_en ,
				'end_date.date_format' => ResponseMassage::$FAILED_Date_End_Format_Massages_en ,
				'end_date.required' => ResponseMassage::$FAILED_Date_End_Required_Massages_en ,
				'start_date.before' => ResponseMassage::$FAILED_Date_Before_Massages_en ,
				'end_date.after' => ResponseMassage::$FAILED_Date_After_Massages_en ,
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'start_date' ) ) {
					$resp = new ResponseDisplay( $errors->first ( 'start_date' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();
				}
			if ( $errors->first ( 'end_date' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'end_date' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
			if ( $errors->first ( 'status' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'status' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
		}

		public static function emailValidation (Request $request)
		{
			$rules = array (

				'email' => 'required|email' ,

			);
			$messages = array (
				'email.required' => ResponseMassage::$FAIL_Email_Required_en ,
				'email.email' => ResponseMassage::$FAIL_Email_Format_Error_en ,

			);

			$validator = Validator::make ( $request->all () , $rules , $messages );

			$errors = $validator->errors ();


			if ( $validator->fails () )

				if ( $errors->first ( 'email' ) ) {
					$resp = new ResponseDisplay( $errors->first ( 'email' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();
				}
		}

		public static function CheckSupplierActorCreate (Request $request)
		{
//				$request= $request->input ('name');
			$rules = [
				'name' => 'required|regex:/^(?!.*\d)[a-z\p{Arabic}\s]+$/iu|min:3|max:30' ,
				'email' => 'required|email|unique:users|unique:suppliers|unique:admins' ,
				'phone' => 'required|phone:JO|unique:users|unique:suppliers' ,
				'password' => 'required|min:8|max:30' ,
				'longitude' => 'numeric' ,
				'latitude' => 'numeric' ,
			];
			$massages = [
				'name.regex' => ResponseMassage::$FAIL_Name_Format_en ,
				'name.required' => ResponseMassage::$FAIL_Name_Required_en ,
				'name.min' => ResponseMassage::$FAIL_Name_Format_min_ken_en ,
				'name.max' => ResponseMassage::$FAIL_Name_Format_max_len_en ,

				'email.required' => ResponseMassage::$FAIL_Email_Required_en ,
				'email.email' => ResponseMassage::$FAIL_Email_Format_Error_en ,
				'email.unique' => ResponseMassage::$FAIL_Email_Exists_Error_en ,

				'phone.required' => ResponseMassage::$FAIL_PhoneNum_Required_en ,
				'phone.unique' => ResponseMassage::$FAIL_PhoneNum_Exists_Error_en ,
				'phone.phone:JO' => ResponseMassage::$FAIL_Phone_Format_country_code_Error_en ,
				'phone.phone' => ResponseMassage::$FAIL_PhoneNum_Format_Error_en ,

				'password.required' => ResponseMassage::$FAIL_Password_Required_en ,
				'password.min' => ResponseMassage::$FAIL_Password_min_len_en ,
				'password.max' => ResponseMassage::$FAIL_Password_max_len_en ,
				'longitude.numeric' => ResponseMassage::$FAILED_Longitude_FORMAT ,
				'latitude.numeric' => ResponseMassage::$FAILED_latitude_FORMAT ,

			];


			$validator = Validator::make ( $request->all () , $rules , $massages );
			$errors = $validator->errors ();
//			dd($errors);
			if ( $validator->fails () )

				if ( $errors->first ( 'name' ) ) {
//					dd($errors->first('S'));
					$resp = new ResponseDisplay( $errors->first ( 'name' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();

				}
			if ( $errors->first ( 'email' ) ) {

				$resp = new ResponseDisplay( $errors->first ( 'email' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'phone' ) ) {

				$resp = new ResponseDisplay( $errors->first ( 'phone' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}

			if ( $errors->first ( 'password' ) ) {

				$resp = new ResponseDisplay( $errors->first ( 'password' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}

			if ( $errors->first ( 'longitude' ) ) {

				$resp = new ResponseDisplay( $errors->first ( 'longitude' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'latitude' ) ) {

				$resp = new ResponseDisplay( $errors->first ( 'latitude' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
		}

		public static function checkSupplierUpdate (Request $request)
		{

			$rules = array (
				'name' => 'regex:/^(?!.*\d)[a-z\p{Arabic}\s]+$/iu|min:3|max:30' ,
//				'email' => 'email|unique:users|unique:admins|unique:logins' ,
				'email' => 'email' ,
				'phone' => 'phone:JO' ,
				'password' => 'min:8|max:30' ,
				'longitude' => 'numeric' ,
				'latitude' => 'numeric' ,
				'exp_year' => 'integer' ,
				'status' => 'integer|min:0|max:1' ,
			);
			$messages = array (
				'name.regex' => ResponseMassage::$FAIL_Name_Format_en ,

				'name.min' => ResponseMassage::$FAIL_Name_Format_min_ken_en ,
				'name.max' => ResponseMassage::$FAIL_Name_Format_max_len_en ,

				'email.email' => ResponseMassage::$FAIL_Email_Format_Error_en ,
				'email.unique' => ResponseMassage::$FAIL_Email_Exists_Error_en ,

				'phone.unique' => ResponseMassage::$FAIL_PhoneNum_Exists_Error_en ,
				'phone.phone:JO' => ResponseMassage::$FAIL_Phone_Format_country_code_Error_en ,
				'phone.phone' => ResponseMassage::$FAIL_PhoneNum_Format_Error_en ,

				'password.min' => ResponseMassage::$FAIL_Password_min_len_en ,
				'password.max' => ResponseMassage::$FAIL_Password_max_len_en ,

				'longitude.numeric' => ResponseMassage::$FAILED_Longitude_FORMAT ,
				'latitude.numeric' => ResponseMassage::$FAILED_latitude_FORMAT ,
				'exp_year' => ResponseMassage::$FAIEL_Exp_Year ,
				'status' => ResponseMassage::$FAIEL_Status_1_0
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );

			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'name' ) ) {
					$resp = new ResponseDisplay( $errors->first ( 'name' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();

				}
			if ( $errors->first ( 'email' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'email' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'phone' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'phone' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'password' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'password' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'longitude' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'longitude' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'latitude' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'latitude' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'exp_year' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'exp_year' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'status' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'status' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}

		}


		public static function createSectionValidation (Request $request)
		{
			$rules = array (
				'admin_id' => 'required|integer|exists:admins,id' ,
				'name_en' => 'required|regex:/^[\p{L}\s\.-]+$/|min:3|max:30' ,
				'name_ar' => 'required|regex:/^(?!.*\d)[a-z\p{Arabic}\s]+$/iu|min:3|max:50' ,
				'desc_en' => 'required|min:3|max:140' ,
				'desc_ar' => 'required|min:3|max:140' ,

				'image' => 'string' ,
			);
			$messages = array (
				'name_en.regex' => ResponseMassage::$FAIL_Name_Format_en ,

				'name_en.required' => ResponseMassage::$FAIL_Name_Required_en ,
				'name_en.min' => ResponseMassage::$FAIL_Name_Format_min_ken_en ,
				'name_en.max' => ResponseMassage::$FAIL_Name_Format_max_len_en ,
//				'name_ar.regex' => 'name_ar please Enter Name with only real char' ,

				'name_ar.required' => ResponseMassage::$FAIL_Name_Required_ar ,
				'name_ar.regex' => ResponseMassage::$FAIL_Name_Format_ar ,
				'name_ar.min' => ResponseMassage::$FAIL_Name_Min_ar ,
				'name_ar.max' => ResponseMassage::$FAIL_Name_Max_ar ,

				'desc_en.required' => ResponseMassage::$FAIL_Desc_Required_en ,
				'desc_en.min' => ResponseMassage::$FAIL_Desc_Format_min_len_en ,
				'desc_en.max' => ResponseMassage::$FAIL_Desc_Format_max_len_en ,

				'desc_ar.required' => ResponseMassage::$FAIL_Name_Required_ar ,
				'desc_ar.min' => ResponseMassage::$FAIL_Name_Min_ar ,
				'desc_ar.max' => ResponseMassage::$FAIL_Name_Max_ar ,
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'admin_id' ) ) {
					$resp = new ResponseDisplay( $errors->first ( 'admin_id' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();

				}
			if ( $errors->first ( 'name_en' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'name_en' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'name_ar' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'name_ar' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
//			if ( $validator->fails () )
			if ( $errors->first ( 'desc_en' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'desc_en' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'desc_ar' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'desc_ar' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}

			if ( $errors->first ( 'image' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'image' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}


		}

		public static function updateSectionValidation (Request $request)
		{

			$rules = array (

				'name_en' => 'regex:/^[\p{L}\s\.-]+$/|min:3|max:30' ,
				'name_ar' => 'regex:/^(?!.*\d)[a-z\p{Arabic}\s]+$/iu|min:3|max:50' ,
				'desc_en' => 'min:3|max:140' ,
				'desc_ar' => 'min:3|max:140' ,
				'image' => 'string' ,
			);

			$messages = array (
				'name_en.regex' => ResponseMassage::$FAIL_Name_Format_en ,

				'name_en.min' => ResponseMassage::$FAIL_Name_Format_min_ken_en ,
				'name_en.max' => ResponseMassage::$FAIL_Name_Format_max_len_en ,

				'name_ar.regex' => ResponseMassage::$FAIL_Name_Format_ar ,
				'name_ar.min' => ResponseMassage::$FAIL_Name_Min_ar ,
				'name_ar.max' => ResponseMassage::$FAIL_Name_Max_ar ,


				'desc_en.min' => ResponseMassage::$FAIL_Desc_Format_min_len_en ,
				'desc_en.max' => ResponseMassage::$FAIL_Desc_Format_max_len_en ,


				'desc_ar.min' => ResponseMassage::$FAIL_Desc_Min_ar ,
				'desc_ar.max' => ResponseMassage::$FAIL_Desc_Max_ar ,
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );

			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'name_en' ) ) {
					$resp = new ResponseDisplay( $errors->first ( 'name_en' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();

				}
			if ( $errors->first ( 'name_ar' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'name_ar' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'desc_en' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'desc_en' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'desc_ar' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'desc_ar' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'image' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'image' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}

		}

		public static function createServiceValidation (Request $request)
		{
			$rules = array (
				'section_id' => 'required|integer|exists:sections,id' ,
				'name_en' => 'required|regex:/^(?!.*\d)[a-z\p{Arabic}\s]+$/iu|min:3|max:30' ,
				'name_ar' => 'required|min:3|max:50' ,
				'desc_en' => 'required|min:3|max:140' ,
				'desc_ar' => 'required|min:3|max:140' ,
				'image' => 'string' ,
			);
			$messages = array (
				'section_id.required' => ResponseMassage::$FAIL_SectionId_Required_en ,
				'section_id.integer' => ResponseMassage::$FAIL_SectionId_Format_en ,
				'section_id.exists' => ResponseMassage::$FAIL_SectionId_NotExists_en ,

				'name_en.regex' => ResponseMassage::$FAIL_Name_Format_en ,
				'name_en.required' => ResponseMassage::$FAIL_Name_Required_en ,
				'name_en.min' => ResponseMassage::$FAIL_Name_Format_min_ken_en ,
				'name_en.max' => ResponseMassage::$FAIL_Name_Format_max_len_en ,


				'name_ar.required' => ResponseMassage::$FAIL_Name_Required_ar ,
				'name_ar.min' => ResponseMassage::$FAIL_Name_Min_ar ,
				'name_ar.max' => ResponseMassage::$FAIL_Name_Max_ar ,

				'desc_en.required' => ResponseMassage::$FAIL_Desc_Format_en ,
				'desc_en.min' => ResponseMassage::$FAIL_Desc_Format_min_len_en ,
				'desc_en.max' => ResponseMassage::$FAIL_Desc_Format_max_len_en ,

				'desc_ar.required' => ResponseMassage::$FAIL_Desc_Required_ar ,
				'desc_ar.min' => ResponseMassage::$FAIL_Desc_Min_ar ,
				'desc_ar.max' => ResponseMassage::$FAIL_Desc_Max_ar ,
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );

			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'section_id' ) ) {
					$resp = new ResponseDisplay( $errors->first ( 'section_id' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();

				}
			if ( $errors->first ( 'name_en' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'name_en' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'name_ar' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'name_ar' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'desc_en' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'desc_en' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'desc_ar' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'desc_ar' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}

			if ( $errors->first ( 'image' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'image' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}

		}

		public static function updateServicesValidation (Request $request)
		{
			$rules = array (
				'name_en' => 'regex:/^(?!.*\d)[a-z\p{Arabic}\s]+$/iu|min:3|max:30' ,
				'name_ar' => 'min:3|max:50' ,
				'desc_en' => 'min:3|max:140' ,
				'desc_ar' => 'min:3|max:140' ,
				'image' => 'string' ,
			);
			$messages = array (

				'name_en.regex' => ResponseMassage::$FAIL_Name_Format_en ,
//				'name_en.required' => ResponseMassage::$FAIL_Name_Required_en ,
				'name_en.min' => ResponseMassage::$FAIL_Name_Format_min_ken_en ,
				'name_en.max' => ResponseMassage::$FAIL_Name_Format_max_len_en ,


//				'name_ar.required' => ResponseMassage::$FAIL_Name_Required_ar ,
				'name_ar.min' => ResponseMassage::$FAIL_Name_Min_ar ,
				'name_ar.max' => ResponseMassage::$FAIL_Name_Max_ar ,

//				'desc_en.required' => ResponseMassage::$FAIL_Desc_Format_en ,
				'desc_en.min' => ResponseMassage::$FAIL_Desc_Format_min_len_en ,
				'desc_en.max' => ResponseMassage::$FAIL_Desc_Format_max_len_en ,

//				'desc_ar.required' => ResponseMassage::$FAIL_Desc_Required_ar ,
				'desc_ar.min' => ResponseMassage::$FAIL_Desc_Min_ar ,
				'desc_ar.max' => ResponseMassage::$FAIL_Desc_Max_ar ,

			);

			$validator = Validator::make ( $request->all () , $rules , $messages );

			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'name_en' ) ) {
					$resp = new ResponseDisplay( $errors->first ( 'name_en' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();

				}
			if ( $errors->first ( 'name_ar' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'name_ar' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'desc_en' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'desc_en' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'desc_ar' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'desc_ar' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}
			if ( $errors->first ( 'image' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'image' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();

			}


		}

		public static function sectionIdValidation (Request $request)
		{
			$rules = array (
				'section_id' => 'required|integer|exists:sections,id' ,

			);
			$messages = array (
				'section_id.required' => ResponseMassage::$FAIL_SectionId_Required_en ,
				'section_id.exists' => ResponseMassage::$FAIL_SectionId_NotExists_en ,
				'section_id.integer' => ResponseMassage::$FAIL_SectionId_Format_en ,

			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();

			if ( $validator->fails () )
				if ( $errors->first ( 'section_id' ) ) {
					$resp = new ResponseDisplay( $errors->first ( 'section_id' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();
				}
			if ( $errors->first ( 'section_id' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'section_id' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}


		}

		public static function assignServicesValidation (Request $request)
		{
			$rules = array (
				'service_id' => 'required|integer|exists:services,id' ,
				'supplier_id' => 'required|integer|exists:suppliers,id' ,

			);
			$messages = array (
				'service_id.required' => ResponseMassage::$FAIL_ServiceID_Required_en ,
				'service_id.integer' => ResponseMassage::$FAIL_ServiceID_Format_en ,
				'service_id.exists' => ResponseMassage::$FAIL_ServiceID_NotExists_en ,
				'supplier_id.required' => ResponseMassage::$FAIL_SupplierID_Required_en ,
				'supplier_id.integer' => ResponseMassage::$FAIL_SupplierID_Format_en ,
				'supplier_id.exists' => ResponseMassage::$FAIL_SupplierID_NotExists_en ,

			);

			$validator = Validator::make ( $request->all () , $rules , $messages );

			$errors = $validator->errors ();

			if ( $validator->fails () )
				if ( $errors->first ( 'service_id' ) ) {
					$resp = new ResponseDisplay( $errors->first ( 'service_id' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();
				}
			if ( $errors->first ( 'supplier_id' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'supplier_id' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
		}

		public static function getServicesSectionSupplierIdValidation (Request $request)
		{
			$rules = array (
				'section_id' => 'required|integer|exists:sections,id' ,
				'supplier_id' => 'required|integer|exists:suppliers,id' ,

			);
			$messages = array (
				'section_id.required' => ResponseMassage::$FAIL_SectionId_Required_en ,
				'section_id.integer' => ResponseMassage::$FAIL_SectionId_Format_en ,
				'section_id.exists' => ResponseMassage::$FAIL_SectionId_NotExists_en ,
				'supplier_id.required' => ResponseMassage::$FAIL_SupplierID_Required_en ,
				'supplier_id.integer' => ResponseMassage::$FAIL_SupplierID_Format_en ,
				'supplier_id.exists' => ResponseMassage::$FAIL_SupplierID_NotExists_en ,


			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();

			if ( $validator->fails () )
				if ( $errors->first ( 'section_id' ) ) {
					$resp = new ResponseDisplay( $errors->first ( 'section_id' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();
				}
			if ( $errors->first ( 'supplier_id' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'supplier_id' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}

		}


		public static function createOrderValidation (Request $request)
		{


			$rules = array (

				"user_id" => "required|integer|exists:users,id" ,
				"supplier_id" => "required|integer|exists:suppliers,id" ,
				"service_id" => "required|integer|exists:services,id" ,
				"status" => "integer|min:0|max:3" ,
				"rate" => "numeric|min:0|max:5" ,
			);
			$messages = array (
				"user_id.required" => ResponseMassage::$FAIL_UserID_Required_en ,
				"user_id.integer" => ResponseMassage::$FAIL_UserID_Format_en ,
				"user_id.exists" => ResponseMassage::$FAIL_UserID_NotExists_en ,

				"supplier_id.required" => ResponseMassage::$FAIL_SupplierID_Required_en ,
				"supplier_id.integer" => ResponseMassage::$FAIL_SupplierID_Format_en ,
				"supplier_id.exists" => ResponseMassage::$FAIL_SupplierID_NotExists_en ,

				"service_id.required" => ResponseMassage::$FAIL_ServiceID_Required_en ,
				"service_id.integer" => ResponseMassage::$FAIL_ServiceID_Format_en ,
				"service_id.exists" => ResponseMassage::$FAIL_ServiceID_NotExists_en ,


//				"description.required" => "desc is required" ,

			);


			$validator = Validator::make ( $request->all () , $rules , $messages );

			$errors = $validator->errors ();


			if ( $validator->fails () )
				if ( $errors->first ( 'user_id' ) ) {
					$resp = new ResponseDisplay( $errors->first ( 'user_id' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();
				}

			if ( $errors->first ( 'supplier_id' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'supplier_id' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}

			if ( $errors->first ( 'service_id' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'service_id' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}

			if ( $errors->first ( 'status' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'status' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
			if ( $errors->first ( 'rate' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'rate' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
		}

		public static function updateOrderValidation (Request $request)
		{

			$rules = array (

				"user_id" => "integer|exists:users,id" ,
				"supplier_id" => "integer|exists:suppliers,id" ,
				"service_id" => "integer|exists:services,id" ,

				"status" => "integer|min:0|max:3" ,
				"is_rated" => "integer|min:0|max:1" ,
				"rate" => "numeric|min:0|max:5" ,
			);
			$messages = array (

//				"user_id.required" => ResponseMassage::$FAIL_UserID_Required_en ,
				"user_id.integer" => ResponseMassage::$FAIL_UserID_Format_en ,
				"user_id.exists" => ResponseMassage::$FAIL_UserID_NotExists_en ,

//				"supplier_id.required" => ResponseMassage::$FAIL_SupplierID_Required_en,
				"supplier_id.integer" => ResponseMassage::$FAIL_SupplierID_Format_en ,
				"supplier_id.exists" => ResponseMassage::$FAIL_SupplierID_NotExists_en ,

//				"service_id.required" =>ResponseMassage::$FAIL_ServiceID_Required_en,
				"service_id.integer" => ResponseMassage::$FAIL_ServiceID_Format_en ,
				"service_id.exists" => ResponseMassage::$FAIL_ServiceID_NotExists_en ,
			);


			$validator = Validator::make ( $request->all () , $rules , $messages );
			$errors = $validator->errors ();


			if ( $validator->fails () )

				if ( $errors->first ( 'user_id' ) ) {
					$resp = new ResponseDisplay( $errors->first ( 'user_id' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();
				}

			if ( $errors->first ( 'supplier_id' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'supplier_id' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}

			if ( $errors->first ( 'service_id' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'service_id' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}

			if ( $errors->first ( 'description' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'description' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}

			if ( $errors->first ( 'status' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'status' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
			if ( $errors->first ( 'rate' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'rate' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
			if ( $errors->first ( 'is_rated' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'is_rated' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
		}

		public static function addressCreateValidation (Request $request)
		{
			//validtion rule
			$rules = array (
				'longitude' => 'required|numeric' ,
				'latitude' => 'required|numeric' ,
				'city' => 'required|string' ,
				'street' => 'required|string' ,
				'country' => 'required|string' ,
				'neighborhood' => 'string' ,
				'building_number' => 'integer' ,
				'apartment_number' => 'integer' ,
				'floor' => 'integer' ,
				'address_type' => 'required|integer|between:0,2' ,
				'user_id' => 'required|exists:users,id'
			);
			$messages = array (
				'longitude.required' => ResponseMassage::$FAIL_ADDRESS_Longitude_REQUIRED ,
				'longitude.numeric' => ResponseMassage::$FAIL_ADDRESS_Longitude_FORMATE ,

				'latitude.required' => ResponseMassage::$FAIL_ADDRESS_latitude_REQUIRED ,
				'latitude.numeric' => ResponseMassage::$FAIL_ADDRESS_latitude_FORMATE ,

				'city.string' => ResponseMassage::$FAIL_ADDRESS_city_FORMATE ,
				'city.required' => ResponseMassage::$FAIL_ADDRESS_city_REQUIRED ,

				'street.string' => ResponseMassage::$FAIL_ADDRESS_street_FORMATE ,
				'street.required' => ResponseMassage::$FAIL_ADDRESS_street_REQUIRED ,

				'country.string' => ResponseMassage::$FAIL_ADDRESS_country_FORMATE ,
				'country.required' => ResponseMassage::$FAIL_ADDRESS_country_REQUIRED ,

				'neighborhood.string' => ResponseMassage::$FAIL_ADDRESS_neighborhood_FORMATE ,
				'building_number.integer' => ResponseMassage::$FAIL_ADDRESS_building_number_FORMATE ,

				'apartment_number.integer' => ResponseMassage::$FAIL_ADDRESS_apartment_number_FORMATE ,
				'floor.integer' => ResponseMassage::$FAIL_ADDRESS_floor_FORMATE ,

				'address_type.required' => ResponseMassage::$FAIL_ADDRESS_address_type_REQUIRED
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );
			//			$errors= $validator;
			$errors = $validator->errors ();


			if ( $validator->fails () )


				if ( $errors->first ( 'longitude' ) ) {
					$resp = new ResponseDisplay( $errors->first ( 'longitude' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $resp->returnWithOutData ();
				}
			if ( $errors->first ( 'latitude' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'latitude' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
			if ( $errors->first ( 'city' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'city' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
			if ( $errors->first ( 'street' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'street' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
			if ( $errors->first ( 'country' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'country' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
			if ( $errors->first ( 'neighborhood' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'neighborhood' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
			if ( $errors->first ( 'building_number' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'building_number' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
			if ( $errors->first ( 'apartment_number' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'apartment_number' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
			if ( $errors->first ( 'floor' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'floor' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
			if ( $errors->first ( 'address_type' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'address_type' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
			if ( $errors->first ( 'user_id' ) ) {
				$resp = new ResponseDisplay( $errors->first ( 'user_id' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $resp->returnWithOutData ();
			}
		}
public static function updateAddressValidation(Request $request)
{$rules = array (
	'longitude' => 'numeric' ,
	'latitude' => 'numeric' ,
	'city' => 'string' ,
	'street' => 'string' ,
	'country' => 'string' ,
	'neighborhood' => 'string' ,
	'building_number' => 'integer' ,
	'apartment_number' => 'integer' ,
	'floor' => 'integer' ,
);
	$messages = array (
		'longitude.numeric' => ResponseMassage::$FAIL_ADDRESS_Longitude_FORMATE,

		'latitude.numeric' => ResponseMassage::$FAILED_latitude_FORMAT ,

		'city.string' =>  ResponseMassage::$FAIL_ADDRESS_city_FORMATE,

		'street.string' => ResponseMassage::$FAIL_ADDRESS_street_FORMATE ,

		'country.string' => ResponseMassage::$FAIL_ADDRESS_country_FORMATE ,

		'neighborhood.string' => ResponseMassage::$FAIL_ADDRESS_neighborhood_FORMATE ,

		'building_number.integer' => ResponseMassage::$FAIL_ADDRESS_building_number_FORMATE ,

		'apartment_number.integer' =>  ResponseMassage::$FAIL_ADDRESS_apartment_number_FORMATE,

		'floor.integer' =>  ResponseMassage::$FAIL_ADDRESS_floor_FORMATE,
	);

	$validator = Validator::make ( $request->all () , $rules , $messages );
	//			$errors= $validator;
	$errors = $validator->errors ();


	if ( $validator->fails () )

		if ( $errors->first ( 'longitude' ) )
		{
			$resp = new ResponseDisplay( $errors->first ( 'longitude' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

			return $resp->returnWithOutData ();
		}
	if ( $errors->first ( 'latitude' ) )
	{
		$resp = new ResponseDisplay( $errors->first ( 'latitude' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

		return $resp->returnWithOutData ();
	}
	if ( $errors->first ( 'city' ) )
	{
		$resp = new ResponseDisplay( $errors->first ( 'city' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

		return $resp->returnWithOutData ();
	}
	if ( $errors->first ( 'street' ) )
	{
		$resp = new ResponseDisplay( $errors->first ( 'street' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

		return $resp->returnWithOutData ();
	}
	if ( $errors->first ( 'country' ) )
	{
		$resp = new ResponseDisplay( $errors->first ( 'country' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

		return $resp->returnWithOutData ();
	}
	if ( $errors->first ( 'neighborhood' ) )
	{
		$resp = new ResponseDisplay( $errors->first ( 'neighborhood' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

		return $resp->returnWithOutData ();
	}
	if ( $errors->first ( 'building_number' ) )
	{
		$resp = new ResponseDisplay( $errors->first ( 'building_number' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

		return $resp->returnWithOutData ();
	}
	if ( $errors->first ( 'apartment_number' ) )
	{
		$resp = new ResponseDisplay( $errors->first ( 'apartment_number' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

		return $resp->returnWithOutData ();
	}
	if ( $errors->first ( 'floor' ) )
	{
		$resp = new ResponseDisplay( $errors->first ( 'floor' ) , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

		return $resp->returnWithOutData ();
	}
}

	}