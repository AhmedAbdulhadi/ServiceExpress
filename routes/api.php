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
		Route::get ( 'address' , 'AddressController@index' );
		Route::post ( 'address' , 'AddressController@add_addresss' );
		Route::put ( 'address/{id}' , 'AddressController@update_address' );
		Route::post ( 'users/login' , 'UserController@login' );
		Route::post ( 'users/logout' , 'UserController@logout' );
		//		Route::post ('users/details', 'UserController@details');
		Route::resource ( 'users' , 'UserController' );

		if ( Input::has ( 'phone' ) and !Input::has ( 'email' ) )
			Route::get ( 'users' , 'UserController@get_phone' );
		else if ( Input::has ( 'start_date' ) or Input::has ( 'end_date' ) and Input::has ( 'status' ) )
			Route::get ( 'users' , 'UserController@get_date' );
		else if ( Input::has ( 'date' ) )
			Route::get ( 'users' , 'UserController@get_user_by_date' );
		else if ( Input::has ( 'email' ) and !Input::has ( 'phone' ) )
			Route::get ( 'users' , 'UserController@get_user_email' );
		else if ( Input::has ( 'phone' ) and Input::has ( 'email' ) )
			Route::get ( 'users' , 'UserController@get_email_phone' );
		else if ( Input::has ( 'status' ) )
			Route::get ( 'users' , 'UserController@get_inactives_users' );
		else
			Route::resource ( 'users' , 'UserController' );


		Route::post ( 'suppliers/login' , 'SupplierController@login' );
		Route::post ( 'suppliers/logout' , 'SupplierController@logout' );
		//		Route::post ('users/details', 'UserController@details');
		Route::resource ( 'suppliers' , 'SupplierController' );

		if ( Input::has ( 'phone' ) and !Input::has ( 'email' ) )
			Route::get ( 'suppliers' , 'SupplierController@get_phone' );
		else if ( Input::has ( 'start_date' ) or Input::has ( 'end_date' ) and Input::has ( 'status' ) )
			Route::get ( 'suppliers' , 'SupplierController@get_date' );
		else if ( Input::has ( 'date' ) )
			Route::get ( 'suppliers' , 'SupplierController@get_user_by_date' );
		else if ( Input::has ( 'email' ) and !Input::has ( 'phone' ) )
			Route::get ( 'suppliers' , 'SupplierController@get_user_email' );
		else if ( Input::has ( 'phone' ) and Input::has ( 'email' ) )
			Route::get ( 'suppliers' , 'SupplierController@get_email_phone' );
		else if ( Input::has ( 'status' ) )
			Route::get ( 'suppliers' , 'SupplierController@get_inactives_users' );

		else if ( Input::has ( 'service_id' ) )
			Route::get ( 'suppliers' , 'SupplierController@suppliers_services_id_s' );
		else
			Route::resource ( 'suppliers' , 'SupplierController' );


		Route::post ( 'admins/login' , 'AdminController@login' );
		Route::post ( 'admins/logout' , 'AdminController@logout' );
		//		Route::post ('users/details', 'UserController@details');
		Route::resource ( 'admins' , 'AdminController' );

		if ( Input::has ( 'phone' ) and !Input::has ( 'email' ) )
			Route::get ( 'admins' , 'AdminController@get_phone' );
		else if ( Input::has ( 'start_date' ) or Input::has ( 'end_date' ) and Input::has ( 'status' ) )
			Route::get ( 'admins' , 'AdminController@get_date' );
		else if ( Input::has ( 'date' ) )
			Route::get ( 'admins' , 'AdminController@get_user_by_date' );
		else if ( Input::has ( 'email' ) and !Input::has ( 'phone' ) )
			Route::get ( 'admins' , 'AdminController@get_user_email' );
		else if ( Input::has ( 'phone' ) and Input::has ( 'email' ) )
			Route::get ( 'admins' , 'AdminController@get_email_phone' );
		else if ( Input::has ( 'status' ) )
			Route::get ( 'admins' , 'AdminController@get_inactives_users' );
		else
			Route::resource ( 'admins' , 'AdminController' );


		//		Route::post ('users/details', 'UserController@details');
		Route::resource ( 'services' , 'ServicesController' );

		if ( Input::has ( 'section_id' ) and !Input::has ( 'supplier_id' ) )
			Route::get ( 'services' , 'ServicesController@show_by_section_id' );
		else if ( Input::has ( 'suppliers' ) and !Input::has ( 'service_id' ) )
			Route::get ( 'services' , 'ServicesController@services_suppiler' );
		else if ( Input::has ( 'supplier_id' ) and !Input::has ( 'section_id' ) )
			Route::get ( 'services' , 'ServicesController@services_supplier_id' );

		else if ( Input::has ( 'supplier_id' ) and Input::has ( 'section_id' ) )
			Route::get ( 'services' , 'ServicesController@services_section_supplier_id_s' );

//		  else if(Input::has('supplier_id') and Input::has('service_id'))
		else
			Route::resource ( 'services' , 'ServicesController' );


		Route::post ( 'services/assign' , 'ServicesController@assigned_services' );

		Route::post ( 'services/unassigned' , 'ServicesController@unAssigned_services');


		//		Route::post ('users/details', 'UserController@details');
		Route::resource ( 'section' , 'SectionController' );

		if ( Input::has ( 'section_id' ) )
			Route::get ( 'section' , 'SectionController@get_section_id' );

		else if ( Input::has ( 'service' ) )
			Route::get ( 'section' , 'SectionController@section_with_service' );




		Route::resource ( 'orders' , 'OrderController' );

		/*else if (Input::has ('date'))
Route::get ('section', 'SectionController@get_date_one_Query');
else
Route::resource ('section', 'SectionController');*/

	} );