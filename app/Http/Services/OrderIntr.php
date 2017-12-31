<?php

	use Illuminate\Http\Request;

	interface OrderIntr
{
	public static  function createOrder(Request $request);//create_order
	public static function updateOrder(Request $request,$id);//update_order
	public static function deleteOrder($id);//delete_order
	public static function getAllOrder();//getAllOrder
	public static function getOneOrder($id);//getOneOrder
	public static function getOrderSupplierId(Request $request);//get_order_Supplier
	public static function getOrderUserId(Request $request);//get_order_User


}