<?php

	use Illuminate\Http\Request;

	interface ServicesIntr
{

	public static function getAllServices();//get_all
	public static function getServicesById($id);//get_all
	public static function deleteServices($id);//delete services
	public static function getOneServicesBySectionId(Request $request);//get_one_services_by_section_id
	public static function getServicesWithSupplierId(Request $request);//services_supplier_id_s
	public static function createServices(Request $request);// create_services
	public static function updateServices(Request $request , $id);// update_services
	public static function getServicesSuppliers(Request $request);//services_suppilers
	public static function getServicesSectionIdSupplierId(Request $request);//services_section_supplier_id
	public static function assignServices(Request $request);//assign_services
	public static function unAssignServices(Request $request);//unAssign_services


}