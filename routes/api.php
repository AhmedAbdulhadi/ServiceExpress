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
		Route::post ( 'v1/address' , 'AddressController@add_addresss' );
		Route::put ( 'v1/address/{id}' , 'AddressController@update_address' );
		Route::post ( 'v1/users/login' , 'UserController@login' );
		Route::post ( 'v1/users/logout' , 'UserController@logout' );
		//		Route::post ('users/details', 'UserController@details');
		Route::resource ( 'v1/users' , 'UserController' );

		if ( Input::has ( 'phone' ) and !Input::has ( 'email' ) )
			Route::get ( 'v1/users' , 'UserController@get_phone' );
		else if ( Input::has ( 'start_date' ) or Input::has ( 'end_date' ) and Input::has ( 'status' ) )
			Route::get ( 'v1/users' , 'UserController@get_date' );
		else if ( Input::has ( 'date' ) )
			Route::get ( 'v1/users' , 'UserController@get_user_by_date' );
		else if ( Input::has ( 'email' ) and !Input::has ( 'phone' ) )
			Route::get ( 'v1/users' , 'UserController@get_user_email' );
		else if ( Input::has ( 'phone' ) and Input::has ( 'email' ) )
			Route::get ( 'v1/users' , 'UserController@get_email_phone' );
		else if ( Input::has ( 'status' ) )
			Route::get ( 'v1/users' , 'UserController@get_inactives_users' );
		else
			Route::resource ( 'v1/users' , 'UserController' );


		Route::post ( 'v1/suppliers/login' , 'SupplierController@login' );
		Route::post ( 'v1/suppliers/logout' , 'SupplierController@logout' );
		//		Route::post ('users/details', 'UserController@details');
		Route::resource ( 'v1/suppliers' , 'SupplierController' );

		if ( Input::has ( 'phone' ) and !Input::has ( 'email' ) )
			Route::get ( 'v1/suppliers' , 'SupplierController@get_phone' );
		else if ( Input::has ( 'start_date' ) or Input::has ( 'end_date' ) and Input::has ( 'status' ) )
			Route::get ( 'v1/suppliers' , 'SupplierController@get_date' );
		else if ( Input::has ( 'date' ) )
			Route::get ( 'v1/suppliers' , 'SupplierController@get_user_by_date' );
		else if ( Input::has ( 'email' ) and !Input::has ( 'phone' ) )
			Route::get ( 'v1/suppliers' , 'SupplierController@get_user_email' );
		else if ( Input::has ( 'phone' ) and Input::has ( 'email' ) )
			Route::get ( 'v1/suppliers' , 'SupplierController@get_email_phone' );
		else if ( Input::has ( 'status' ) )
			Route::get ( 'v1/suppliers' , 'SupplierController@get_inactives_users' );

		else if ( Input::has ( 'service_id' ) )
			Route::get ( 'v1/suppliers' , 'SupplierController@suppliers_services_id_s' );
		else
			Route::resource ( 'v1/suppliers' , 'SupplierController' );


		Route::post ( 'v1/admins/login' , 'AdminController@login' );
		Route::post ( 'v1/admins/logout' , 'AdminController@logout' );
		//		Route::post ('users/details', 'UserController@details');
		Route::resource ( 'v1/admins' , 'AdminController' );

		if ( Input::has ( 'phone' ) and !Input::has ( 'email' ) )
			Route::get ( 'v1/admins' , 'AdminController@get_phone' );
		else if ( Input::has ( 'start_date' ) or Input::has ( 'end_date' ) and Input::has ( 'status' ) )
			Route::get ( 'v1/admins' , 'AdminController@get_date' );
		else if ( Input::has ( 'date' ) )
			Route::get ( 'v1/admins' , 'AdminController@get_user_by_date' );
		else if ( Input::has ( 'email' ) and !Input::has ( 'phone' ) )
			Route::get ( 'v1/admins' , 'AdminController@get_user_email' );
		else if ( Input::has ( 'phone' ) and Input::has ( 'email' ) )
			Route::get ( 'v1/admins' , 'AdminController@get_email_phone' );
		else if ( Input::has ( 'status' ) )
			Route::get ( 'v1/admins' , 'AdminController@get_inactives_users' );
		else
			Route::resource ( 'v1/admins' , 'AdminController' );


		//		Route::post ('users/details', 'UserController@details');
		Route::resource ( 'v1/services' , 'ServicesController' );

		if ( Input::has ( 'section_id' ) and !Input::has ( 'supplier_id' ) )
			Route::get ( 'v1/services' , 'ServicesController@show_by_section_id' );
		else if ( Input::has ( 'suppliers' ) and !Input::has ( 'service_id' ) )
			Route::get ( 'v1/services' , 'ServicesController@services_suppiler' );
		else if ( Input::has ( 'supplier_id' ) and !Input::has ( 'section_id' ) )
			Route::get ( 'v1/services' , 'ServicesController@services_supplier_id' );

		else if ( Input::has ( 'supplier_id' ) and Input::has ( 'section_id' ) )
			Route::get ( 'v1/services' , 'ServicesController@services_section_supplier_id_s' );

//		  else if(Input::has('supplier_id') and Input::has('service_id'))
		else
			Route::resource ( 'v1/services' , 'ServicesController' );


		Route::post ( 'v1/services/assign' , 'ServicesController@assigned_services' );

		Route::post ( 'v1/services/unassigned' , 'ServicesController@unAssigned_services' );


		//		Route::post ('users/details', 'UserController@details');
		Route::resource ( 'v1/section' , 'SectionController' );

		if ( Input::has ( 'section_id' ) )
			Route::get ( 'v1/section' , 'SectionController@get_section_id' );

		else if ( Input::has ( 'service' ) )
			Route::get ( 'v1/section' , 'SectionController@section_with_service' );


		Route::resource ( 'v1/orders' , 'OrderController' );
		if( Input::has ( 'supplier_id' ) and Input::has ( 'active' ))
			Route::get ( 'v1/orders' , 'OrderController@get_orderSupplier' );
		else if( Input::has ( 'user_id' ) and Input::has ( 'active' ))
			Route::get ( 'v1/orders' , 'OrderController@get_order_Users' );
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