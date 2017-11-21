<?php

	namespace App\Http\Controllers\api;

	use App\address;
	use App\Http\Controllers\AddressServices;
	use App\Http\Controllers\Controller;
	use Illuminate\Support\Facades\Response;
	use Novent\Transformers\AddressTransfomer;
	use Illuminate\Http\Request;

	class AddressController extends AddressServices
	{
		public function index ()
		{
			return $this->get_all_address ();
		}

		public function add_addresss (Request $request)
		{
			return $this->add_address ( $request );
		}

		public function update (Request $request , $id)
		{
			return $this->update_address ( $request , $id );

		}
	}
