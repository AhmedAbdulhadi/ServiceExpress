<?php

	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;

	/*
	|--------------------------------------------------------------------------
	| API Routes
	|--------------------------------------------------------------------------
	|
	| Here is where you can register API routes for your application. These
	| routes are loaded by the RouteServiceProvider within a group which
	| is assigned the "api" middleware group. Enjoy building your API!
	|
	*/


	/*	Route::middleware ('auth:api')->get ('/supplier', function (Request $request) {
			return $request->user ();
		});*/

	Route::middleware ( 'auth:api' )->get ( '/user' , function (Request $request) {
		return $request->user ();
	} );
	Route::group ( ['namespace' => 'api'] , function () {
		Route::get ( 'v1/address' , 'AddressController@index' );
		Route::post ( 'v1/address' , 'AddressController@createAddress' );
		Route::put ( 'v1/address/{id}' , 'AddressController@update' );

		Route::post ( 'v1/users/login' , 'UserController@login' );
		Route::post ( 'v1/users/logout' , 'UserController@logout' );
		//		Route::post ('users/details', 'UserController@details');
		Route::resource ( 'v1/users' , 'UserController' );

		if ( Input::has ( 'phone' ) and !Input::has ( 'email' ) )
			Route::get ( 'v1/users' , 'UserController@getUserByPhone' );
		else if ( Input::has ( 'start_date' ) or Input::has ( 'end_date' ) and Input::has ( 'status' ) )
			Route::get ( 'v1/users' , 'UserController@getUserByFlightDate' );
		else if ( Input::has ( 'date' ) )
			Route::get ( 'v1/users' , 'UserController@getUserBySingleDate' );
		else if ( Input::has ( 'email' ) and !Input::has ( 'phone' ) )
			Route::get ( 'v1/users' , 'UserController@getUserByEmailAddress' );
		else if ( Input::has ( 'phone' ) and Input::has ( 'email' ) )
			Route::get ( 'v1/users' , 'UserController@getUserByPhoneAndEmailAddress' );
		else if ( Input::has ( 'status' ) )
			Route::get ( 'v1/users' , 'UserController@getInactiveUsers' );
		else
			Route::resource ( 'v1/users' , 'UserController' );


		Route::post ( 'v1/suppliers/login' , 'SupplierController@login' );
		Route::post ( 'v1/suppliers/logout' , 'SupplierController@logout' );
		//		Route::post ('users/details', 'UserController@details');
		Route::resource ( 'v1/suppliers' , 'SupplierController' );

		if ( Input::has ( 'phone' ) and !Input::has ( 'email' ) )
			Route::get ( 'v1/suppliers' , 'SupplierController@getPhoneQuery' );
		else if ( Input::has ( 'start_date' ) or Input::has ( 'end_date' ) and Input::has ( 'status' ) )
			Route::get ( 'v1/suppliers' , 'SupplierController@getDateQuery' );
		else if ( Input::has ( 'date' ) )
			Route::get ( 'v1/suppliers' , 'SupplierController@getSuppliersSingleDate' );
		else if ( Input::has ( 'email' ) and !Input::has ( 'phone' ) )
			Route::get ( 'v1/suppliers' , 'SupplierController@getSupplierByEmail' );
		else if ( Input::has ( 'phone' ) and Input::has ( 'email' ) )
			Route::get ( 'v1/suppliers' , 'SupplierController@getSupplierEmailPhoneNum' );
		else if ( Input::has ( 'status' ) )
			Route::get ( 'v1/suppliers' , 'SupplierController@getInactiveSuppliers' );

		else if ( Input::has ( 'service_id' ) )
			Route::get ( 'v1/suppliers' , 'SupplierController@getSupplierByServiceId' );
		else
			Route::resource ( 'v1/suppliers' , 'SupplierController' );


		Route::post ( 'v1/admins/login' , 'AdminController@login' );
		Route::post ( 'v1/admins/logout' , 'AdminController@logout' );
		//		Route::post ('users/details', 'UserController@details');
		Route::resource ( 'v1/admins' , 'AdminController' );

		if ( Input::has ( 'phone' ) and !Input::has ( 'email' ) )
			Route::get ( 'v1/admins' , 'AdminController@getAdminByPhoneNum' );
		else if ( Input::has ( 'start_date' ) or Input::has ( 'end_date' ) and Input::has ( 'status' ) )
			Route::get ( 'v1/admins' , 'AdminController@getDateQuery' );
		else if ( Input::has ( 'date' ) )
			Route::get ( 'v1/admins' , 'AdminController@getAdminSingleDate' );
		else if ( Input::has ( 'email' ) and !Input::has ( 'phone' ) )
			Route::get ( 'v1/admins' , 'AdminController@getAdminByEmail' );
		else if ( Input::has ( 'phone' ) and Input::has ( 'email' ) )
			Route::get ( 'v1/admins' , 'AdminController@getAdminEmailPhoneNum' );
		else if ( Input::has ( 'status' ) )
			Route::get ( 'v1/admins' , 'AdminController@getInactiveAdmin' );
		else
			Route::resource ( 'v1/admins' , 'AdminController' );


		//		Route::post ('users/details', 'UserController@details');
		Route::resource ( 'v1/services' , 'ServicesController' );

		if ( Input::has ( 'section_id' ) and !Input::has ( 'supplier_id' ) )
			Route::get ( 'v1/services' , 'ServicesController@getOneServicesBySectionId' );
		else if ( Input::has ( 'suppliers' ) and !Input::has ( 'service_id' ) )
			Route::get ( 'v1/services' , 'ServicesController@getServicesSuppliers' );
		else if ( Input::has ( 'supplier_id' ) and !Input::has ( 'section_id' ) )
			Route::get ( 'v1/services' , 'ServicesController@getServicesWithSupplierId' );

		else if ( Input::has ( 'supplier_id' ) and Input::has ( 'section_id' ) )
			Route::get ( 'v1/services' , 'ServicesController@getServicesSectionIdSupplierId' );

//		  else if(Input::has('supplier_id') and Input::has('service_id'))
		else
			Route::resource ( 'v1/services' , 'ServicesController' );


		Route::post ( 'v1/services/assign' , 'ServicesController@assignServices' );

		Route::post ( 'v1/services/unassigned' , 'ServicesController@unAssignServices' );


		//		Route::post ('users/details', 'UserController@details');

		if ( Input::has ( 'services' ) )
			Route::get ( 'v1/section/{id}' , 'SectionController@get_section_id' );

		else if ( Input::has ( 'service' ) )
			Route::get ( 'v1/section' , 'SectionController@section_with_service' );
		else
			Route::resource ( 'v1/section' , 'SectionController' );

		Route::resource ( 'v1/orders' , 'OrderController' );
		if ( Input::has ( 'supplier_id' ) and Input::has ( 'active' ) )
			Route::get ( 'v1/orders' , 'OrderController@getOrderSupplierId' );
		else if ( Input::has ( 'user_id' ) and Input::has ( 'active' ) )
			Route::get ( 'v1/orders' , 'OrderController@getOrderUserId' );
		/*else if (Input::has ('date'))
Route::get ('section', 'SectionController@get_date_one_Query');
else
Route::resource ('section', 'SectionController');*/
		Route::get ( 'v1/dashboard' , 'DashboardController@index' );
		if ( Input::has ( 'start_date' ) or Input::has ( 'end_date' ) )
			Route::get ( 'v1/dashboard' , 'DashboardController@get_date_Query' );
		else
			if ( Input::has ( 'all' ) )
				Route::get ( 'v1/dashboard' , 'DashboardController@all' );

	} );