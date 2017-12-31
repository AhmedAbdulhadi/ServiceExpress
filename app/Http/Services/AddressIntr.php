<?php
	use Illuminate\Http\Request;
interface AddressIntr
{

	public static function getAllAddress();//get_all_address
	public static function addAddress(Request $request);//add_address
	public static function updateAddress(Request $request,$id);//update_address

}