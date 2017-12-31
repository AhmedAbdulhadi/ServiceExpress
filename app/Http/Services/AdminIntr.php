<?php
	use Illuminate\Http\Request;
interface AdminIntr
{

	public static function getAllAdmin();

	public static function getOneAdminById($id);

	public static function createAdmin(Request $request);

	public static function deleteAdmin($id);

	public static function updateAdmin(Request $request,$id);

	public static function getAdminByPhoneNum(Request $request);

	public static function getAdminSingleDate(Request $request);//single date

	public static function getDateQuery(Request $request);// flight date

	public static function getAdminByEmail(Request $request);// email address

	public static function getAdminEmailPhoneNum(Request $request);// get_user_email_phonenum

	public static function getInactiveAdmin(Request $request);// get_inactive_users




}