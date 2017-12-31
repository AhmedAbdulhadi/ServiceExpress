<?php
//namespace App;
	namespace App\Http\Controllers;
	class ResponseMassage
	{

		//success Massages en
		public static $Success_Found_en = 'Found';
		public static $Success_Created_en = 'Created';
		public static $SUCCESS_Update_en = "Updated";
		public static $SUCCESS_Deleted_en = "Deleted";
		public static $SUCCESS_Login_en = "logged on success";

		//success Massages en
		public static $Success_Found_ar = 'موجود';
		public static $Success_Created_ar = 'تم انشاء بنجاح';
		public static $SUCCESS_Update_ar = "تم التعديل بنجاح";
		public static $SUCCESS_Deleted_ar = "تم الحذف بنجاح";
		public static $SUCCESS_Login_ar = "تم تسجيل الدخول بنجاح";

		//Fail DB massages en
		public static $FAILED_Not_Found_Massages_en = "Not Found";
		public static $FAILED_Deleted_Massages_en = "User Deleted";
		public static $FAILED_Login_Massages_en = "Login Fail";
		//date error
		public static $FAILED_Date_Format_Massages_en = "Invalid Date Format must be 2017-05-05";
		public static $FAILED_Date_Required_Massages_en = "Date Is Required";

		public static $FAILED_Date_Start_Format_Massages_en = "Invalid start_date Format must be 2017-05-05";//start date
		public static $FAILED_Date_Start_Required_Massages_en = "start_date Is Required";

		public static $FAILED_Date_End_Format_Massages_en = "Invalid end_date Format must be 2017-05-05";//end date
		public static $FAILED_Date_End_Required_Massages_en = "end_date Is Required";

		public static $FAILED_Date_Before_Massages_en = 'Start Date Must Be Before End Date';
		public static $FAILED_Date_After_Massages_en = 'End Date Must Be After Start Date';

		public static $FAILED_Longitude_FORMAT = 'Longitude must be Double';
		public static $FAILED_latitude_FORMAT = 'Latitude must be Double';
		public static $FAIEL_Exp_Year = 'exp_year must be integer';
		public static $FAIEL_Status_1_0 = 'Status must be 0 or 1 ';
//	public static $FAIEL_Exp_Year = 'exp_year must be integer';

		public static $FAILED_Status_Required_Massages_en = "Status Is Required";
		public static $FAILED_Create_Massages_en = "Failed To Create";
		public static $FAILED_Update_Massages_en = "Failed To Update";
		public static $FAILED_LOGIN_USER_EMAIL_PASSWORD = 'wrong email or password';
		public static $FAILED_Create_Duplicate_Massages_en = " assigned services_id and supplier_id already existing ";
		//Fail DB massages ar
		public static $FAILED_Not_Found_Massages_ar = "غير موجود";
		public static $FAILED_Deleted_Massages_ar = "تم الحذف";
		public static $FAILED_Login_Massages_ar = "لم يتم تسجيل الدخول";
		public static $FAILED_Date_Format_Massages_ar = "تم ادخال تاريخ مرفوض يرجى التاكد منه مثال  05-05-2017";
		public static $FAILED_Create_Massages_ar = "لم يتم انشاء بنجاح يرجى التاكد من المعلومات";
		public static $FAILED_Update_Massages_ar = "لم يتم التعديل بنجاح يرجى التأكد من المعلومات";

		// Error massages En
		public static $FAIL_Update_en = "Please Check your Entered info !";
		public static $FAIL_Error_en = "Unexpected Error !";
		public static $FAIL_Not_Found_en = "Not Found";
		public static $FAIL_Deleted_en = "Item Was Deleted";


		// Validation Error for name en
		public static $FAIL_Name_Required_en = "Name Is Required ";
		public static $FAIL_Name_Format_en = "Name Is Not Valid ";
		public static $FAIL_Name_Format_max_len_en = "Name Max Length is 30 ";
		public static $FAIL_Name_Format_min_ken_en = "Name Min Length is 3 ";


		// Validation Error  for Description  en
		public static $FAIL_Desc_Required_en = "Enter description_en ";
		public static $FAIL_Desc_Format_en = "Name Is Not Valid ";
		public static $FAIL_Desc_Format_max_len_en = "The description_en  min is 3.";
		public static $FAIL_Desc_Format_min_len_en = "The description_en  max is 140.";

		//section_Validation
		public static $FAIL_SectionId_Required_en = "Section_id is required";
		public static $FAIL_SectionId_Format_en = "Section_id invalid input";
		public static $FAIL_SectionId_NotExists_en = "Section_id not Exists";

		//Supplier_idValidation
		public static $FAIL_SupplierID_Required_en = "SupplierID is required  ";
		public static $FAIL_SupplierID_Format_en = "SupplierID invalid input";
		public static $FAIL_SupplierID_NotExists_en = "SupplierID not Exists";

		//Service_idValidation
		public static $FAIL_ServiceID_Required_en = "ServiceID is required  ";
		public static $FAIL_ServiceID_Format_en = "ServiceID invalid input";
		public static $FAIL_ServiceID_NotExists_en = "ServiceID not Exists";

		//user_id Validation
		public static $FAIL_UserID_Required_en = "UserID is required  ";
		public static $FAIL_UserID_Format_en = "UserID invalid input";
		public static $FAIL_UserID_NotExists_en = "UserID not Exists";

		public static $FAIL_Password_Required_en = "Password Is Required";
		public static $FAIL_Password_min_len_en = "Password Min Length is 8";
		public static $FAIL_Password_max_len_en = "Password Max Length is 30";

		public static $FAIL_Email_Required_en = "Email Is Required";
		public static $FAIL_Email_Format_Error_en = "Please Enter Valid Email Address";
		public static $FAIL_Email_Exists_Error_en = "Email Address Is Exists";

		public static $FAIL_Phone_Format_country_code_Error_en = "Phone number prefix must be 962";
		public static $FAIL_PhoneNum_Required_en = "Phone Number Is Required";
		public static $FAIL_PhoneNum_Format_Error_en = "Please Enter Valid Phone Number";
		public static $FAIL_PhoneNum_Exists_Error_en = "Phone Number Is Exists";

		public static $FAIL_Deactivated_Admin_Error_en = "Deactivated Admin";
		public static $FAIL_Deactivated_Supplier_Error_en = "Deactivated Supplier";
		public static $FAIL_Deactivated_User_Error_en = "Deactivated User";

		public static $FAIL_NOT_Admin_Error_en = "NOT ADMIN";
		public static $FAIL_NOT_Supplier_Error_en = "NOT SUPPLIER";
		public static $FAIL_NOT_User_Error_en = "NOT USER";

		public static $FAIL_ADDRESS_TYPE_EXISTS_0 = 'address_type 0 already exists';
		public static $FAIL_ADDRESS_TYPE_EXISTS_1 = 'address_type 1 already exists';
		public static $FAIL_ADDRESS_TYPE_EXISTS_2 = 'address_type 2 already exists';

		public static $FAIL_ADDRESS_Longitude_REQUIRED = 'longitude is required';
		public static $FAIL_ADDRESS_Longitude_FORMATE = 'Enter valid longitude';

		public static $FAIL_ADDRESS_latitude_REQUIRED = 'latitude is required';
		public static $FAIL_ADDRESS_latitude_FORMATE = 'Enter valid latitude';

		public static $FAIL_ADDRESS_city_REQUIRED = 'city is required';
		public static $FAIL_ADDRESS_city_FORMATE = 'Enter valid city';

		public static $FAIL_ADDRESS_street_REQUIRED = 'street is required';
		public static $FAIL_ADDRESS_street_FORMATE = 'Enter valid street';

		public static $FAIL_ADDRESS_country_REQUIRED = 'country is required';
		public static $FAIL_ADDRESS_country_FORMATE = 'Enter valid country';
		public static $FAIL_ADDRESS_address_type_REQUIRED = 'address_type is required';

		public static $FAIL_ADDRESS_neighborhood_FORMATE = 'Enter valid neighborhood';
		public static $FAIL_ADDRESS_building_number_FORMATE = 'Enter valid building_number';

		public static $FAIL_ADDRESS_apartment_number_FORMATE = 'Enter valid apartment_number';
		public static $FAIL_ADDRESS_floor_FORMATE = 'Enter valid floor';

		// Error massages Ar
		public static $FAIL_Update_ar = "يرجى التأكد من المعلومات المدخله";
		public static $FAIL_Error_ar = "حدث خطأ غير متوقع";
		public static $FAIL_Not_Found_ar = "غير موجود";
		public static $FAIL_Deleted_ar = "العنصر محذوف بالفعل";
		//name ar
		public static $FAIL_Name_Required_ar = "يرجى ادخال الاسم";
		public static $FAIL_Name_Format_ar = "يرجى ادخال الاسم بالغة العربية";
		public static $FAIL_Name_Min_ar = "اقل عدد احرف للأسم 3 احرف";
		public static $FAIL_Name_Max_ar = " اكثر عدد احرف مسموح هو 30 حرف";

		public static $FAIL_Desc_Required_ar = "يرجى ادخال الوصف ";
		public static $FAIL_Desc_Format_ar = "يرجى ادخال الاسم بالغة العربية";
		public static $FAIL_Desc_Min_ar = "اقل عدد حروف للوصف هو 3 احرف";
		public static $FAIL_Desc_Max_ar = "اكثر عدد احرف للوصف هو 140 حرف";

		public static $FAIL_Email_Required_ar = "يرجى ادخال البريد الالكتروني";
		public static $FAIL_Email_Format_Error_ar = "يرجى ادخال بريد الالكتروني صحيح";
		public static $FAIL_Email_Exists_Error_ar = "البريد الالكتروني موجود بالفعل";

		public static $FAIL_PhoneNum_Required_ar = "يرجى ادخال رقم الهاتف";
		public static $FAIL_Phone_Format_country_code_Error_ar = "يرجى ادخال رمز الدولة كالتالي 962";
		public static $FAIL_PhoneNum_Format_Error_ar = "يرجى ادخال رقم هاتف صحيح ";
		public static $FAIL_PhoneNum_Exists_Error_ar = "رقم الهاتف موجود بالفعل";

		public static $FAIL_Password_Required_ar = "يرجى ادخال كلمة المرور";

		public static $FAIL_Deactivated_Admin_Error_ar = "المسؤول غير فعال";
		public static $FAIL_Deactivated_Supplier_Error_ar = "المزود غير فعال";
		public static $FAIL_Deactivated_User_Error_ar = "المستخدم غير فعال";


	}