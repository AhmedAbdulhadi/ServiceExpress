<?php
//	namespace App\Http\Controllers;
	use Illuminate\Http\Request;
	interface UsersInte
	{
		public static function getAllUser();

		public static function getUserById($id);

		public static function createUser(Request $request);

		public static function deleteUser($id);

		public static function updateUser(Request $request,$id);

		public static function getPhoneQuery(Request $request);

		public static function getUserSingleDate(Request $request);//single date

		public static function getDateQuery(Request $request);// flight date

		public static function getUserByEmail(Request $request);// email address

		public static function getUserEmailPhoneNum(Request $request);// get_user_email_phonenum

		public static function getInactiveUsers(Request $request);// get_inactive_users



	}