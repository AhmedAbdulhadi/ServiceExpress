<?php

	namespace App\Http\Controllers\api;

	use App\Http\Controllers\Controller;
	use App\Http\Controllers\OrderServices;
	use App\OrderModel;
	use Illuminate\Http\Request;

	class OrderController extends Controller
	{
		public function __construct ()
		{

			$this->middleware ( 'auth:api' );

		}

		public function index ()
		{
			return OrderServices::getAllOrder ();
		}


		public function store (Request $request)
		{
			return OrderServices::createOrder ( $request );
		}

		public function show ($id)
		{
			return OrderServices::getOneOrder ($id);
		}

		public function update (Request $request , $id)
		{
			return OrderServices::updateOrder ( $request , $id );

		}

		public function getOrderSupplierId (Request $request)
		{
			return OrderServices::getOrderSupplierId ($request);
		}

		public function getOrderUserId (Request $request)
		{
			return OrderServices::getOrderUserId ($request);
		}

		public function destroy ($id)
		{
			return OrderServices::deleteOrder ( $id );
		}
	}
