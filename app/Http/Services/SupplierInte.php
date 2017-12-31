<?php

	use Illuminate\Http\Request;

	interface SupplierInte
	{
		public static function getAllSupplier();
		public static function getSupplierById($id);
		public static function createSupplier(Request $request);//create_user
		public static function deleteSupplier($id);//delete_user
		public static function updateSupplier(Request $request,$id);//update_user
		public static function getPhoneQuery(Request $request);//get_phone_Query
		public static function getSuppliersSingleDate(Request $request);//get_one_user_date
		public static function getDateQuery(Request $request);//get_date_Query
		public static function getSupplierByEmail(Request $request);// get_user_by_email
		public static function getSupplierEmailPhoneNum(Request $request);// get_user_email_phonenum
		public static function getInactiveSuppliers(Request $request);// get_inactive_users
	}