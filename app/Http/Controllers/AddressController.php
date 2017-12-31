<?php

	namespace App\Http\Controllers\api;

	use App\adressModel;
	use App\Http\Controllers\AddressServices;
	use App\Http\Controllers\Controller;
	use Illuminate\Support\Facades\Response;
	use Unit\Transformers\AddressTransfomer;
	use Illuminate\Http\Request;

	class AddressController extends Controller
	{
		public function index ()
		{
			return AddressServices::getAllAddress ();
		}

		public function createAddress (Request $request)
		{
			return AddressServices::addAddress ($request);
		}

		public function update (Request $request , $id)
		{
			return AddressServices::updateAddress($request,$id);

		}
	}
